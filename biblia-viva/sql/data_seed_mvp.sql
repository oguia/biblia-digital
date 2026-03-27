-- Seed Data para Biblia Viva: Gênesis, Êxodo, Lucas e Atos

-- ==========================================
-- GÊNESIS (ID: 1)
-- ==========================================

-- Gênesis 1 (Criação)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(1, 1, 'Início dos Tempos', 'Criação', 'Deus, Espírito Santo', 'Inexistentes (Pré-história)', 'Jesus é a Palavra (Verbo) pela qual todas as coisas foram feitas (João 1:1-3).');

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(1, 1, 'Deus é o Criador soberano que traz ordem ao caos e vida ao vazio.', 'Cuidado com a ideia de que somos fruto do acaso; fomos planejados por Deus.', 'Adorar a Deus por Sua majestade revelada na criação e cuidar bem da natureza.');

-- Gênesis 12 (Chamado de Abraão) - Atualização/Refinamento se já existir
-- Nota: Usando ON DUPLICATE KEY UPDATE para garantir consistência
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(1, 12, '2091 a.C.', 'Patriarcas', 'Abrão, Sarai, Ló', '3ª Dinastia de Ur (Mesopotâmia)', 'Através de Abraão, todas as famílias da terra seriam abençoadas, profecia cumprida em Cristo (Gálatas 3:8).')
ON DUPLICATE KEY UPDATE conexao_jesus = VALUES(conexao_jesus);

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(1, 12, 'Ur dos Caldeus', 30.9629, 46.1031, 'Cidade natal de Abraão.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Zigurat_de_Ur.jpg/800px-Zigurat_de_Ur.jpg'),
(1, 12, 'Harã', 36.8622, 39.0272, 'Local de parada da família de Abraão.', NULL);


-- ==========================================
-- ÊXODO (ID: 2)
-- ==========================================

-- Êxodo 14 (Mar Vermelho)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(2, 14, '1446 a.C.', 'Êxodo', 'Moisés, Faraó, Israel', 'Novo Reino Egípcio (Amenhotep II?)', 'A passagem pelo Mar Vermelho simboliza o batismo e a libertação do pecado que temos em Cristo (1 Coríntios 10:1-2).');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(2, 14, 'Pi-Hahiroth (Estimado)', 29.9500, 32.5500, 'Local do acampamento antes da travessia, de frente para Baal-Zefom.', NULL),
(2, 14, 'Mar Vermelho (Golfo de Suez)', 29.5000, 32.5000, 'Local da travessia milagrosa.', NULL);

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(2, 14, 'Quando Deus nos guia para um beco sem saída, Ele está preparando o cenário para um milagre.', 'O medo nos faz querer voltar para a escravidão do passado em vez de confiar no futuro de Deus.', 'Ficar firme e esperar o livramento do Senhor, mesmo quando a situação parece impossível.');

-- Êxodo 20 (Dez Mandamentos)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(2, 20, '1446 a.C.', 'Lei no Sinai', 'Moisés, Arão, Deus', 'Idade do Bronze Recente', 'Jesus não veio abolir a Lei, mas cumpri-la perfeitamente, oferecendo graça onde falhamos (Mateus 5:17).');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(2, 20, 'Monte Sinai (Jebel Musa)', 28.5391, 33.9749, 'Montanha onde Deus entregou as Tábuas da Lei.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Mount_Sinai.jpg/800px-Mount_Sinai.jpg');

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(2, 20, 'Os mandamentos de Deus não são restrições para tirar nossa alegria, mas cercas de proteção para nossa liberdade.', 'Idolatria não é apenas adorar estátuas, mas colocar qualquer coisa (dinheiro, carreira, ego) acima de Deus.', 'Examinar o coração e identificar qual mandamento tenho negligenciado, pedindo graça para obedecer.');


-- ==========================================
-- LUCAS (ID: 42)
-- ==========================================

-- Lucas 2 (Nascimento)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(42, 2, '4 a.C.', 'Nascimento de Jesus', 'Jesus, Maria, José, Pastores', 'Reinado de Augusto (Roma) e Herodes (Judeia)', 'O próprio cumprimento da promessa: o Verbo se fez carne.');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(42, 2, 'Belém', 31.7049, 35.2038, 'Cidade de Davi, local do nascimento de Jesus.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Church_of_the_Nativity_2011.jpg/800px-Church_of_the_Nativity_2011.jpg'),
(42, 2, 'Campos dos Pastores', 31.7000, 35.2167, 'Local onde os anjos anunciaram o nascimento.', NULL);

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(42, 2, 'A vinda de Jesus é uma boa nova de grande alegria para TODO o povo, começando pelos humildes.', 'Não deixar que a religiosidade ou a rotina nos roubem o maravilhamento diante da encarnação de Deus.', 'Compartilhar as boas novas de Jesus com alguém esta semana, assim como os pastores fizeram.');

-- Lucas 24 (Ressurreição)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(42, 24, '30/33 d.C.', 'Ressurreição', 'Jesus, Discípulos, Mulheres', 'Tibério César imperador de Roma', 'A vitória definitiva sobre a morte, garantindo nossa própria ressurreição.');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(42, 24, 'Túmulo do Jardim (Jerusalém)', 31.7841, 35.2295, 'Possível local do sepultamento e ressurreição.', NULL),
(42, 24, 'Emaús (Tradicional)', 31.8394, 34.9797, 'Aldeia para onde dois discípulos caminhavam quando encontraram Jesus.', NULL);

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(42, 24, 'A ressurreição de Jesus não é uma lenda, é o fato histórico central que valida nossa fé.', 'A dúvida é natural, mas Jesus pacientemente nos revela Sua presença através das Escrituras.', 'Buscar ver Jesus em todo o Antigo Testamento ("Moisés e os Profetas"), como Ele ensinou no caminho de Emaús.');


-- ==========================================
-- ATOS (ID: 44)
-- ==========================================

-- Atos 2 (Pentecostes)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(44, 2, '30/33 d.C.', 'Início da Igreja', 'Pedro, Apóstolos, Multidão', 'Império Romano em Pax Romana', 'Jesus cumpre a promessa de enviar o Consolador (Espírito Santo) para habitar em nós.');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(44, 2, 'Cenáculo (Monte Sião)', 31.7718, 35.2289, 'Local tradicional da descida do Espírito Santo.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/67/Cenacle_Room.jpg/800px-Cenacle_Room.jpg');

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(44, 2, 'O Espírito Santo capacita a Igreja para testemunhar com ousadia e poder, não por força humana.', 'Não buscar experiências espirituais apenas por emoção, mas para ser testemunha eficaz de Cristo.', 'Orar pedindo plenitude do Espírito Santo para vencer medos e falar de Jesus.');

-- Atos 9 (Conversão de Paulo)
INSERT INTO cronologia (liv_id, capitulo, ano_estimado, periodo, personagens, eventos_mundiais, conexao_jesus) VALUES
(44, 9, '33-36 d.C.', 'Expansão da Igreja', 'Saulo, Ananias, Jesus', 'Calígula imperador (aprox)', 'Jesus se identifica pessoalmente com Sua igreja perseguida: "Por que ME persegues?".');

INSERT INTO contexto_geografico (liv_id, capitulo, nome, latitude, longitude, descricao, imagem) VALUES
(44, 9, 'Estrada para Damasco', 33.5138, 36.2765, 'Local da visão celestial de Saulo.', NULL),
(44, 9, 'Rua Direita (Damasco)', 33.5101, 36.3134, 'Rua onde Saulo ficou hospedado na casa de Judas.', 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0e/Straight_Street%2C_Damascus.jpg/800px-Straight_Street%2C_Damascus.jpg');

INSERT INTO aplicacao_pratica (liv_id, capitulo, verdade_central, alerta, acao_pratica) VALUES
(44, 9, 'Ninguém está longe demais do alcance da graça de Deus; Ele transforma perseguidores em pregadores.', 'Cuidado com o preconceito contra novos convertidos que tinham um passado difícil; Deus pode usá-los poderosamente.', 'Orar pela conversão de alguém que parece ser um "caso perdido" ou inimigo da fé.');
