-- Seed Data for Enrichment Features (Timeline, Map, Context, Application)
-- Focusing on Genesis 1 to demonstrate the "Bible Happening Now" concept

-- 1. Genesis 1 Context
INSERT INTO historical_context (chapter_id, who_involved, when_text, where_text, historical_events)
SELECT id,
'["Deus (Elohim)", "Espírito Santo", "A Palavra (Jesus)"]',
'No princípio (Eternidade Passada)',
'O Universo; A Terra em formação',
'A Criação do Universo; O início do Tempo; Formação da matéria'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'gn') AND number = 1;

-- 2. Genesis 1 Timeline Events
INSERT INTO timeline_events (chapter_id, year, title, description, era)
SELECT id, -4004, 'A Criação', 'Deus cria os céus e a terra a partir do nada (Creatio Ex Nihilo).', 'Origens'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'gn') AND number = 1;

INSERT INTO timeline_events (chapter_id, year, title, description, era)
SELECT id, -4004, 'Dia 1: Luz', 'Separação entre luz e trevas; criação do tempo.', 'Origens'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'gn') AND number = 1;

-- 3. Genesis 1 Map Locations (Symbolic location for Eden/Creation)
INSERT INTO map_locations (chapter_id, name, latitude, longitude, description, type)
SELECT id, 'Jardim do Éden (Provável)', 31.00000000, 47.00000000, 'Localização tradicional baseada na confluência dos rios Tigre e Eufrates.', 'region'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'gn') AND number = 1;

-- 4. Genesis 1 Spiritual Application (Identity)
INSERT INTO spiritual_applications (chapter_id, truth, alert, action)
SELECT id,
'Deus é o Criador Soberano que traz ordem ao caos e luz às trevas.',
'Sem a presença ativa de Deus, a vida permanece "sem forma e vazia", em trevas espirituais.',
'Identifique uma área "caótica" em sua vida hoje e convide Deus para trazer Sua luz e ordem sobre ela.'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'gn') AND number = 1;

-- 5. Exodus 14 (Crossing the Red Sea) - Additional Demo
-- Ensure Exodus 14 exists first
INSERT INTO historical_context (chapter_id, who_involved, when_text, where_text, historical_events)
SELECT id,
'["Moisés", "Faraó", "Povo de Israel", "Exército Egípcio"]',
'1446 a.C.',
'Pi-Haaarote, entre Migdol e o mar, defronte de Baal-Zefom',
'O Êxodo do Egito; A travessia do Mar Vermelho'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'ex') AND number = 14;

INSERT INTO timeline_events (chapter_id, year, title, description, era)
SELECT id, -1446, 'A Travessia do Mar Vermelho', 'Deus abre o mar para Israel passar e fecha sobre os egípcios.', 'Êxodo'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'ex') AND number = 14;

INSERT INTO map_locations (chapter_id, name, latitude, longitude, description, type)
SELECT id, 'Mar Vermelho (Golfo de Suez)', 29.50000000, 32.50000000, 'Provável local da travessia onde o mar se abriu.', 'region'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'ex') AND number = 14;

INSERT INTO spiritual_applications (chapter_id, truth, alert, action)
SELECT id,
'Deus luta por nós quando estamos encurralados e abre caminhos onde não há saída.',
'O medo nos faz querer voltar para a escravidão do passado (Egito) em vez de confiar no futuro de Deus.',
'Pare de clamar e marche! Dê o próximo passo de fé na direção que Deus ordenou, mesmo que pareça impossível.'
FROM chapters WHERE book_id = (SELECT id FROM books WHERE abbrev = 'ex') AND number = 14;
