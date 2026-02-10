import json
import time
import sys
import subprocess

# Tenta importar as bibliotecas e avisa se faltarem
try:
    import mysql.connector
    import google.generativeai as genai
except ImportError as e:
    print("\n[ERRO] Faltam bibliotecas necessárias!")
    print(f"Detalhe: {e}")
    print("\nPARA RESOLVER:")
    print("1. Abra seu terminal/CMD (não o Python).")
    print("2. Digite o seguinte comando e aperte Enter:")
    print("\n   pip install mysql-connector-python google-generativeai\n")
    print("3. Tente rodar este script novamente.\n")
    sys.exit(1)

# ==============================================================================
# CONFIGURAÇÕES (PREENCHA AQUI)
# ==============================================================================

# 1. Sua Chave de API do Google Gemini (Obtenha em https://aistudio.google.com/)
GEMINI_API_KEY = "SUA_CHAVE_API_AQUI"

# 2. Configurações do Banco de Dados (Hostinger)
DB_CONFIG = {
    'host': 'localhost',      # Na Hostinger geralmente é 'localhost' ou o IP
    'user': 'seu_usuario',
    'password': 'sua_senha',
    'database': 'seu_banco',
    'raise_on_warnings': True
}

# 3. ID da Versão da Bíblia para leitura (Ex: 6 = NVI, 5 = Almeida, etc)
VERSAO_ID_LEITURA = 6

# ==============================================================================
# CONFIGURAÇÃO DO MODELO AI
# ==============================================================================
genai.configure(api_key=GEMINI_API_KEY)

# Configuração de segurança e geração para garantir JSON
generation_config = {
  "temperature": 0.2, # Baixa criatividade para ser mais factual
  "top_p": 0.95,
  "top_k": 64,
  "max_output_tokens": 8192,
  "response_mime_type": "application/json",
}

model = genai.GenerativeModel(
  model_name="gemini-1.5-flash", # Modelo rápido e econômico
  generation_config=generation_config,
)

# ==============================================================================
# FUNÇÕES DE BANCO DE DADOS
# ==============================================================================

def get_db_connection():
    return mysql.connector.connect(**DB_CONFIG)

def get_livros(cursor):
    """Retorna lista de livros (id, nome)"""
    cursor.execute("SELECT liv_id, liv_nome FROM livros ORDER BY liv_id ASC")
    return cursor.fetchall()

def get_total_capitulos(cursor, liv_id):
    """Retorna o número total de capítulos de um livro"""
    query = "SELECT MAX(ver_capitulo) FROM versiculos WHERE ver_liv_id = %s"
    cursor.execute(query, (liv_id,))
    result = cursor.fetchone()
    return result[0] if result and result[0] else 0

def get_texto_capitulo(cursor, liv_id, capitulo):
    """Busca o texto completo do capítulo na versão especificada"""
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

    texto_completo = ""
    for row in rows:
        texto_completo += f"{row[0]}. {row[1]} "

    return texto_completo

def check_contexto_existe(cursor, liv_id, capitulo):
    """Verifica se já existe contexto para não duplicar (opcional)"""
    cursor.execute("SELECT id FROM cronologia WHERE liv_id = %s AND capitulo = %s", (liv_id, capitulo))
    return cursor.fetchone() is not None

def salvar_dados(cursor, liv_id, capitulo, dados):
    """Salva os dados gerados nas 3 tabelas"""

    # 1. Cronologia
    crono = dados.get('cronologia', {})
    sql_crono = """
    INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus)
    VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    # Garantir que todos os campos existam no JSON, senão usa string vazia
    cursor.execute(sql_crono, (
        liv_id,
        capitulo,
        crono.get('ano_estimado', ''),
        crono.get('periodo', ''),
        crono.get('personagens', ''), # Personagens podem vir como lista ou string, ideal tratar
        crono.get('eventos_mundiais', ''),
        crono.get('conexao_jesus', '')
    ))

    # 2. Geografia
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

    # 3. Aplicação Prática
    app = dados.get('aplicacao', {})
    sql_app = """
    INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica)
    VALUES (%s, %s, %s, %s, %s)
    """
    cursor.execute(sql_app, (
        liv_id,
        capitulo,
        app.get('verdade', ''),
        app.get('alerta', ''),
        app.get('acao', '')
    ))

# ==============================================================================
# LÓGICA DE IA (GEMINI)
# ==============================================================================

def gerar_contexto_ia(livro_nome, capitulo, texto_biblico):
    """Envia o texto para o Gemini e recebe o JSON estruturado"""

    prompt = f"""
    Atue como um especialista em Teologia Bíblica, Arqueologia e Geografia Histórica.
    Analise o texto bíblico abaixo ({livro_nome} Capítulo {capitulo}) e extraia as informações de contexto.

    TEXTO BÍBLICO:
    "{texto_biblico[:30000]}" (limitado para segurança)

    INSTRUÇÕES DE SAÍDA (JSON ESTRITO):
    Retorne APENAS um objeto JSON com a seguinte estrutura exata:

    {{
      "locais": [
        {{
          "nome": "Nome do Local (Ex: Jericó)",
          "lat": -0.0000,
          "lng": 0.0000,
          "descricao": "Descrição curta da relevância neste capítulo."
        }}
      ],
      "cronologia": {{
        "ano_estimado": "Ex: 1406 a.C.",
        "periodo": "Ex: Conquista de Canaã",
        "personagens": "Ex: Josué, Raabe, Espias",
        "eventos_mundiais": "Ex: Novo Reino no Egito",
        "conexao_jesus": "Explique brevemente como este capítulo aponta para Cristo (tipologia, profecia ou tema redentor)."
      }},
      "aplicacao": {{
        "verdade": "Uma frase resumindo a verdade teológica central.",
        "alerta": "Um alerta espiritual baseado no erro de algum personagem ou mandamento.",
        "acao": "Uma ação prática para o cristão moderno."
      }}
    }}

    Regras:
    1. Se não houver locais geográficos claros, retorne "locais": [].
    2. Coordenadas (lat/lng) devem ser precisas (formato decimal).
    3. Seja conservador e teologicamente ortodoxo.
    """

    try:
        response = model.generate_content(prompt)
        # O Gemini configurado com response_mime_type="application/json" já deve retornar JSON puro
        return json.loads(response.text)
    except Exception as e:
        print(f"Erro na IA: {e}")
        return None

# ==============================================================================
# LOOP PRINCIPAL
# ==============================================================================

def main():
    conn = None
    try:
        conn = get_db_connection()
        cursor = conn.cursor()

        livros = get_livros(cursor)
        print(f"Conectado! Encontrados {len(livros)} livros para processar.")

        for liv_id, liv_nome in livros:
            total_caps = get_total_capitulos(cursor, liv_id)
            print(f"\n>>> Processando Livro: {liv_nome} ({total_caps} capítulos)")

            for cap in range(1, total_caps + 1):
                # Verificar se já processamos (para poder parar e continuar depois)
                if check_contexto_existe(cursor, liv_id, cap):
                    print(f"   [PULADO] Cap {cap} já existe.")
                    continue

                print(f"   [...] Lendo Cap {cap}...", end="\r")
                texto = get_texto_capitulo(cursor, liv_id, cap)

                if not texto:
                    print(f"   [ERRO] Texto não encontrado para {liv_nome} {cap} (Versão ID {VERSAO_ID_LEITURA})")
                    continue

                # Chamada IA
                print(f"   [IA] Gerando contexto para {liv_nome} {cap}...", end="\r")
                dados_ia = gerar_contexto_ia(liv_nome, cap, texto)

                if dados_ia:
                    salvar_dados(cursor, liv_id, cap, dados_ia)
                    conn.commit()
                    print(f"   [OK] Cap {cap} processado e salvo!     ")
                else:
                    print(f"   [FALHA] Cap {cap} - Erro na geração da IA.")

                # Pausa para não estourar limite da API (Rate Limit)
                time.sleep(2)

    except mysql.connector.Error as err:
        print(f"Erro de Banco de Dados: {err}")
    except Exception as e:
        print(f"Erro Geral: {e}")
    finally:
        if conn and conn.is_connected():
            cursor.close()
            conn.close()
            print("\nConexão fechada. Processo finalizado.")

if __name__ == "__main__":
    main()
