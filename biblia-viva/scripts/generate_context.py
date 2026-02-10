import mysql.connector
import json
import time

# Configuração da API do Gemini (Exemplo - Instalar: pip install google-generativeai)
# import google.generativeai as genai
# genai.configure(api_key="SUA_API_KEY_AQUI")

# Configuração do Banco de Dados
db_config = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'biblia'
}

def get_texto_capitulo(cursor, liv_id, capitulo):
    """
    Busca o texto completo de um capítulo para enviar ao LLM.
    """
    # Ajuste o nome das colunas conforme seu banco real (vrs_id=5 é NVI, por exemplo)
    query = """
    SELECT ver_texto FROM versiculos
    WHERE ver_liv_id = %s AND ver_capitulo = %s AND ver_vrs_id = 5
    ORDER BY ver_versiculo ASC
    """
    cursor.execute(query, (liv_id, capitulo))
    versiculos = cursor.fetchall()
    texto_completo = " ".join([v[0] for v in versiculos])
    return texto_completo

def gerar_contexto_ia(livro_nome, capitulo, texto_biblico):
    """
    Simula a chamada ao Gemini para gerar o JSON de contexto.
    """
    prompt = f"""
    Você é um teólogo e historiador bíblico especialista.
    Analise o texto de {livro_nome} {capitulo}:
    "{texto_biblico[:500]}..." (texto truncado para exemplo)

    Gere um JSON estrito com os seguintes campos:
    1. "geo": Lista de locais geográficos mencionados (nome, latitude, longitude, descricao_curta).
    2. "crono": Objeto com (ano_estimado, periodo_historico, personagens_chave, eventos_mundiais_paralelos, conexao_com_jesus).
    3. "app": Objeto com (verdade_central, alerta_espiritual, acao_pratica).

    Regras:
    - Latitude/Longitude devem ser reais e precisas.
    - Conexão com Jesus deve mostrar como esse texto aponta para Cristo (tipologia ou profecia).
    - Ação Prática deve ser aplicável hoje.
    """

    # AQUI VOCÊ CHAMARIA O MODELO REAL:
    # model = genai.GenerativeModel('gemini-pro')
    # response = model.generate_content(prompt)
    # return response.text

    print(f"--- Gerando AI para {livro_nome} {capitulo} ---")
    # Retorno Mockado para Teste
    return json.dumps({
        "geo": [{"nome": "Exemplo Local", "lat": 0.0, "lon": 0.0, "desc": "Local gerado via IA"}],
        "crono": {"ano": "2000 AC", "periodo": "Exemplo", "personagens": "Fulano", "eventos": "Nenhum", "jesus": "Tipologia X"},
        "app": {"verdade": "Deus é bom", "alerta": "Não peque", "acao": "Ore mais"}
    })

def salvar_no_banco(cursor, liv_id, capitulo, dados_json):
    """
    Insere os dados gerados nas tabelas de contexto.
    """
    dados = json.loads(dados_json)

    # 1. Inserir Geografia
    for geo in dados['geo']:
        sql_geo = """
        INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao)
        VALUES (%s, %s, %s, %s, %s, %s)
        """
        cursor.execute(sql_geo, (liv_id, capitulo, geo['nome'], geo['lat'], geo['lon'], geo['desc']))

    # 2. Inserir Cronologia
    c = dados['crono']
    sql_crono = """
    INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus)
    VALUES (%s, %s, %s, %s, %s, %s, %s)
    """
    cursor.execute(sql_crono, (liv_id, capitulo, c['ano'], c['periodo'], c['personagens'], c['eventos'], c['jesus']))

    # 3. Inserir Aplicação
    a = dados['app']
    sql_app = """
    INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica)
    VALUES (%s, %s, %s, %s, %s)
    """
    cursor.execute(sql_app, (liv_id, capitulo, a['verdade'], a['alerta'], a['acao']))

    print(f"Dados salvos para {liv_id}:{capitulo}")

def main():
    try:
        conn = mysql.connector.connect(**db_config)
        cursor = conn.cursor()

        # Exemplo: Processar Gênesis (liv_id=1) do cap 1 ao 50
        LIVRO_ID = 1
        LIVRO_NOME = "Gênesis"

        for cap in range(1, 3): # Teste com 2 capítulos
            texto = get_texto_capitulo(cursor, LIVRO_ID, cap)
            if texto:
                json_saida = gerar_contexto_ia(LIVRO_NOME, cap, texto)
                salvar_no_banco(cursor, LIVRO_ID, cap, json_saida)
                conn.commit()
                time.sleep(1) # Respeitar rate limits da API
            else:
                print(f"Texto não encontrado para {LIVRO_NOME} {cap}")

    except Exception as e:
        print(f"Erro: {e}")
    finally:
        if 'conn' in locals() and conn.is_connected():
            cursor.close()
            conn.close()

if __name__ == "__main__":
    main()
