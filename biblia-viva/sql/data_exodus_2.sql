-- Inserção de dados para Êxodo (liv_id = 2) Capítulo 2

-- Contexto Geográfico: Nascimento e Fuga de Moisés
INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(2, 2, 'Rio Nilo (Mênfis)', 29.8491, 31.2544, 'Local onde Joquebede colocou o cesto com o bebê Moisés entre os juncos.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Nile_River_and_feluccas_in_Aswan.jpg/800px-Nile_River_and_feluccas_in_Aswan.jpg'),
(2, 2, 'Terra de Midiã', 28.3972, 35.0392, 'Região para onde Moisés fugiu após matar um egípcio e onde conheceu Zípora.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3b/Midian_Mountains.jpg/800px-Midian_Mountains.jpg'),
(2, 2, 'Poço de Midiã', 28.4500, 34.9000, 'Local onde Moisés defendeu as filhas de Reuel (Jetro) e deu água ao rebanho.', NULL);

-- Cronologia
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais) VALUES
(2, 2, '1526 a.C.', 'Escravidão no Egito', 'Moisés, Miriã, Filha de Faraó, Reuel (Jetro), Zípora', 'Reinado de Tutmés I (possível Faraó da opressão) ou Amenhotep I; Ascensão do Novo Reino no Egito.');

-- Aplicação Prática
INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(2, 2, 'Deus preserva Seus escolhidos mesmo nas situações mais perigosas, usando até mesmo os inimigos para cumprir Seus propósitos.', 'Não tente fazer justiça com as próprias mãos no tempo errado, como Moisés fez ao matar o egípcio.', 'Confiar na providência divina que cuida dos detalhes da nossa vida, mesmo quando tudo parece perdido.');
