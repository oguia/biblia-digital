import json
import time
import sys
import os
import datetime

# Tenta importar as bibliotecas e avisa se faltarem
try:
    import mysql.connector
    from mysql.connector import Error as MySQLError
    import google.generativeai as genai
    from google.api_core import exceptions as google_exceptions
except ImportError as e:
    print("\n[ERRO CRÍTICO] Faltam bibliotecas necessárias!")
    print(f"Detalhe: {e}")
    print("\nPARA RESOLVER:")
    print("1. Abra seu terminal/CMD.")
    print("2. Execute: pip install mysql-connector-python google-generativeai")
    print("3. Tente rodar este script novamente.\n")
    sys.exit(1)

# ==============================================================================
# CONFIGURAÇÕES (PREENCHA AQUI)
# ==============================================================================

# 1. Sua Chave de API do Google Gemini (Obtenha em https://aistudio.google.com/)
GEMINI_API_KEY = "SUA_CHAVE_API_AQUI"

# 2. Configurações do Banco de Dados (Hostinger)
DB_CONFIG = {
    'host': 'localhost',
    'user': 'seu_usuario',
    'password': 'sua_senha',
    'database': 'seu_banco',
    'raise_on_warnings': True,
    'charset': 'utf8mb4',
    'collation': 'utf8mb4_unicode_ci'
}

# 3. ID da Versão da Bíblia para leitura (6 = NVI, 5 = Almeida, etc)
VERSAO_ID_LEITURA = 6

# Arquivo de Log
LOG_FILE = "generation_log.txt"

# ==============================================================================
# CONFIGURAÇÃO DO MODELO AI
# ==============================================================================
if GEMINI_API_KEY == "SUA_CHAVE_API_AQUI":
    print("\n[ERRO] Você precisa configurar a GEMINI_API_KEY no script antes de rodar!")
    sys.exit(1)

genai.configure(api_key=GEMINI_API_KEY)

generation_config = {
  "temperature": 0.2,
  "top_p": 0.95,
  "top_k": 40,
  "max_output_tokens": 8192,
  "response_mime_type": "application/json",
}

model = genai.GenerativeModel(
  model_name="gemini-1.5-flash",
  generation_config=generation_config,
)

# ==============================================================================
# FUNÇÕES AUXILIARES
# ==============================================================================

def log(mensagem, tipo="INFO"):
    """Escreve mensagem no console e no arquivo de log"""
    timestamp = datetime.datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    formatted_msg = f"[{timestamp}] [{tipo}] {mensagem}"
    print(formatted_msg)
    with open(LOG_FILE, "a", encoding="utf-8") as f:
        f.write(formatted_msg + "\n")

def clean_json_response(text):
    """Limpa formatação Markdown do JSON se houver"""
    text = text.strip()
    if text.startswith("```json"):
        text = text[7:]
    if text.startswith("```"):
        text = text[3:]
    if text.endswith("```"):
        text = text[:-3]
    return text.strip()

def sanitize_list(value):
    """Converte listas em string separada por vírgula"""
    if isinstance(value, list):
        return ", ".join(str(v) for v in value)
    return str(value) if value is not None else ""

# ==============================================================================
# FUNÇÕES DE BANCO DE DADOS
# ==============================================================================

def get_db_connection():
    try:
        return mysql.connector.connect(**DB_CONFIG)
    except MySQLError as err:
        log(f"Erro ao conectar ao banco: {err}", "ERRO")
        sys.exit(1)

def get_livros(cursor):
    cursor.execute("SELECT liv_id, liv_nome FROM livros ORDER BY liv_id ASC")
    return cursor.fetchall()

def get_total_capitulos(cursor, liv_id):
    query = "SELECT MAX(ver_capitulo) FROM versiculos WHERE ver_liv_id = %s"
    cursor.execute(query, (liv_id,))
    result = cursor.fetchone()
    return result[0] if result and result[0] else 0

def get_texto_capitulo(cursor, liv_id, capitulo):
    query = """
    SELECT ver_versiculo, ver_texto
    FROM versiculos
    WHERE ver_liv_id = %s AND ver_capitulo = %s AND ver_vrs_id = %s
    ORDER BY ver_versiculo ASC
    """
    cursor.execute(query, (liv_id, capitulo, VERSAO_ID_LEITURA))
    rows = cursor.fetchall()

    if not rows:
        return None

    return " ".join([f"{row[0]}. {row[1]}" for row in rows])

def check_contexto_existe(cursor, liv_id, capitulo):
    # Verifica se existe registro na tabela principal de cronologia
    cursor.execute("SELECT id FROM cronologia WHERE liv_id = %s AND capitulo = %s", (liv_id, capitulo))
    return cursor.fetchone() is not None

def salvar_dados(conn, cursor, liv_id, capitulo, dados):
    """Salva os dados nas 3 tabelas com tratamento de erro transacional"""
    try:
        # 1. Cronologia
        crono = dados.get('cronologia', {})
        sql_crono = """
        INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus)
        VALUES (%s, %s, %s, %s, %s, %s, %s)
        ON DUPLICATE KEY UPDATE
            ano_estimado=VALUES(ano_estimado),
            periodo=VALUES(periodo),
            personagens=VALUES(personagens),
            eventos_mundiais=VALUES(eventos_mundiais),
            conexao_jesus=VALUES(conexao_jesus)
        """
        cursor.execute(sql_crono, (
            liv_id,
            capitulo,
            sanitize_list(crono.get('ano_estimado', '')),
            sanitize_list(crono.get('periodo', '')),
            sanitize_list(crono.get('personagens', '')),
            sanitize_list(crono.get('eventos_mundiais', '')),
            sanitize_list(crono.get('conexao_jesus', ''))
        ))

        # 2. Geografia (Limpar anteriores para evitar duplicatas ao reprocessar)
        cursor.execute("DELETE FROM contexto_geografico WHERE liv_id = %s AND capitulo = %s", (liv_id, capitulo))

        locais = dados.get('locais', [])
        if locais:
            sql_geo = """
            INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao)
            VALUES (%s, %s, %s, %s, %s, %s)
            """
            for local in locais:
                cursor.execute(sql_geo, (
                    liv_id,
                    capitulo,
                    local.get('nome', ''),
                    local.get('lat', 0.0),
                    local.get('lng', 0.0),
                    local.get('descricao', '')
                ))

        # 3. Aplicação Prática (Limpar anteriores)
        cursor.execute("DELETE FROM aplicacao_pratica WHERE liv_id = %s AND capitulo = %s", (liv_id, capitulo))

        app = dados.get('aplicacao', {})
        sql_app = """
        INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica)
        VALUES (%s, %s, %s, %s, %s)
        """
        cursor.execute(sql_app, (
            liv_id,
            capitulo,
            sanitize_list(app.get('verdade', '')),
            sanitize_list(app.get('alerta', '')),
            sanitize_list(app.get('acao', ''))
        ))

        conn.commit()
        return True

    except MySQLError as err:
        conn.rollback()
        log(f"Erro ao salvar no banco (Livro {liv_id}, Cap {capitulo}): {err}", "ERRO_DB")
        return False

# ==============================================================================
# LÓGICA DE IA (GEMINI)
# ==============================================================================

def gerar_contexto_ia(livro_nome, capitulo, texto_biblico, tentativas=3):
    """Gera contexto com retries automáticos"""

    prompt = f"""
    Atue como especialista em Teologia, História e Geografia Bíblica.
    Analise: {livro_nome} Capítulo {capitulo}.
    Texto: "{texto_biblico[:30000]}"

    Gere um JSON VÁLIDO com esta estrutura exata:
    {{
      "locais": [
        {{ "nome": "Nome", "lat": 0.0, "lng": 0.0, "descricao": "Texto curto" }}
      ],
      "cronologia": {{
        "ano_estimado": "Ano",
        "periodo": "Período Histórico",
        "personagens": "Personagens principais (separados por vírgula)",
        "eventos_mundiais": "Contexto histórico mundial",
        "conexao_jesus": "Tipologia ou conexão com Cristo"
      }},
      "aplicacao": {{
        "verdade": "Verdade teológica central",
        "alerta": "Alerta espiritual",
        "acao": "Aplicação prática"
      }}
    }}
    Regras:
    1. Se não houver locais, retorne "locais": [].
    2. NUNCA use lat/lng 0.0 e 0.0 (Golfo da Guiné). Se não souber exato, use cidade conhecida mais próxima ou omita.
    """

    for i in range(tentativas):
        try:
            response = model.generate_content(prompt)
            texto_limpo = clean_json_response(response.text)
            return json.loads(texto_limpo)

        except google_exceptions.ResourceExhausted:
            wait_time = (2 ** i) * 5 # Exponential Backoff: 5s, 10s, 20s...
            log(f"Limite de API atingido. Aguardando {wait_time}s...", "WARN")
            time.sleep(wait_time)

        except json.JSONDecodeError as json_err:
            log(f"Erro ao decodificar JSON da IA: {json_err}", "ERRO_JSON")
            time.sleep(2)

        except Exception as e:
            log(f"Erro inesperado na IA: {e}", "ERRO_IA")
            time.sleep(2)

    return None

# ==============================================================================
# LOOP PRINCIPAL
# ==============================================================================

def main():
    log("Iniciando script de geração de contexto...")
    conn = get_db_connection()
    cursor = conn.cursor()

    try:
        livros = get_livros(cursor)
        log(f"Processando {len(livros)} livros.")

        for liv_id, liv_nome in livros:
            total_caps = get_total_capitulos(cursor, liv_id)
            log(f"Livro: {liv_nome} ({total_caps} caps)")

            for cap in range(1, total_caps + 1):
                # Check point de Resumo
                if check_contexto_existe(cursor, liv_id, cap):
                    print(f"   [PULADO] {liv_nome} {cap} já processado.")
                    continue

                print(f"   Processando {liv_nome} {cap}...", end="\r")

                # 1. Obter Texto
                texto = get_texto_capitulo(cursor, liv_id, cap)
                if not texto:
                    log(f"Texto não encontrado: {liv_nome} {cap}", "ERRO_DADOS")
                    continue

                # 2. Gerar com IA
                dados_ia = gerar_contexto_ia(liv_nome, cap, texto)

                if dados_ia:
                    # 3. Salvar
                    if salvar_dados(conn, cursor, liv_id, cap, dados_ia):
                        print(f"   [SUCESSO] {liv_nome} {cap} salvo.      ")
                    else:
                        print(f"   [ERRO DB] {liv_nome} {cap} falhou ao salvar.")
                else:
                    log(f"Falha na geração IA para {liv_nome} {cap}", "FALHA_GERACAO")

                # Pausa gentil para a API
                time.sleep(2)

    except KeyboardInterrupt:
        log("\nOperação interrompida pelo usuário.", "WARN")
    except Exception as e:
        log(f"Erro fatal no loop principal: {e}", "FATAL")
    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()
            log("Conexão fechada.")

if __name__ == "__main__":
    main()
