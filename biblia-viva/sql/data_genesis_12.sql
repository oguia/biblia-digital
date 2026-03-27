-- Inserção de dados para Gênesis (liv_id = 1) Capítulo 12

-- Contexto Geográfico: A Jornada de Abraão
INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(1, 12, 'Ur dos Caldeus', 30.9629, 46.1031, 'Cidade natal de Abraão, centro da civilização suméria.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Zigurat_de_Ur.jpg/800px-Zigurat_de_Ur.jpg'),
(1, 12, 'Harã', 36.8622, 39.0272, 'Local onde a família de Abraão parou temporariamente após sair de Ur.', NULL),
(1, 12, 'Siquém (Carvalho de Moré)', 32.2167, 35.2667, 'Primeira parada de Abraão em Canaã, onde Deus lhe apareceu.', NULL),
(1, 12, 'Betel', 31.9300, 35.2200, 'Local onde Abraão edificou um altar e invocou o nome do Senhor.', NULL),
(1, 12, 'Egito', 30.0444, 31.2357, 'Refúgio de Abraão durante a fome em Canaã.', NULL);

-- Cronologia
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais) VALUES
(1, 12, '2091 a.C.', 'Patriarcas', 'Terá, Sarai, Ló, Faraó', 'Terceira Dinastia de Ur em declínio na Mesopotâmia; Primeira Período Intermediário no Egito.');

-- Aplicação Prática
INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(1, 12, 'A obediência à chamada de Deus exige fé para deixar o conforto e caminhar rumo ao desconhecido.', 'O medo pode nos levar a mentir e comprometer nosso testemunho, como Abraão fez no Egito.', 'Confiar nas promessas de Deus e obedecer prontamente, mesmo sem saber todos os detalhes do futuro.');
