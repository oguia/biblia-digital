-- Seed Data for Mais Deus - Bible Happening Now
-- Includes Genesis 1 Real Content + Contextual Data

-- 1. Insert Book: Genesis
INSERT INTO `books` (`id`, `abbrev`, `name`, `author`, `testament`, `group_name`) VALUES
(1, 'gn', 'Gênesis', 'Moisés', 'old', 'Pentateuco');

-- 2. Insert Chapter: Genesis 1
INSERT INTO `chapters` (`id`, `book_id`, `number`) VALUES
(1, 1, 1);

-- 3. Insert Verses (Genesis 1:1-5 NVI)
INSERT INTO `verses` (`chapter_id`, `number`, `text`, `version`) VALUES
(1, 1, 'No princípio Deus criou os céus e a terra.', 'nvi'),
(1, 2, 'Era a terra sem forma e vazia; trevas cobriam a face do abismo, e o Espírito de Deus se movia sobre a face das águas.', 'nvi'),
(1, 3, 'Disse Deus: "Haja luz", e houve luz.', 'nvi'),
(1, 4, 'Deus viu que a luz era boa, e separou a luz das trevas.', 'nvi'),
(1, 5, 'Deus chamou à luz dia, e às trevas chamou noite. Passaram-se a tarde e a manhã; esse foi o primeiro dia.', 'nvi');

-- 4. Insert Historical Context
INSERT INTO `historical_context` (`chapter_id`, `who_involved`, `when_text`, `where_text`, `historical_events`) VALUES
(1, '["Deus (Elohim)", "Espírito Santo", "A Palavra (Jesus)"]', 'No princípio (Eternidade Passada)', 'O Universo; A Terra em formação', 'A Criação do Universo; O início do Tempo; Formação da matéria');

-- 5. Insert Timeline Events
INSERT INTO `timeline_events` (`chapter_id`, `year`, `title`, `description`, `era`) VALUES
(1, -4004, 'A Criação', 'Deus cria os céus e a terra a partir do nada (Creatio Ex Nihilo).', 'Origens'),
(1, -4004, 'Dia 1: Luz', 'Separação entre luz e trevas; criação do tempo.', 'Origens');

-- 6. Insert Map Locations
INSERT INTO `map_locations` (`chapter_id`, `name`, `latitude`, `longitude`, `description`, `type`) VALUES
(1, 'Jardim do Éden (Provável)', 31.00000000, 47.00000000, 'Localização tradicional baseada na confluência dos rios Tigre e Eufrates.', 'region');

-- 7. Insert Spiritual Application
INSERT INTO `spiritual_applications` (`chapter_id`, `truth`, `alert`, `action`) VALUES
(1, 'Deus é o Criador Soberano que traz ordem ao caos e luz às trevas.', 'Sem a presença ativa de Deus, a vida permanece "sem forma e vazia", em trevas espirituais.', 'Identifique uma área "caótica" em sua vida hoje e convide Deus para trazer Sua luz e ordem sobre ela.');

-- 8. Insert a Demo User
INSERT INTO `users` (`id`, `name`, `email`, `password_hash`) VALUES
(1, 'Demo User', 'demo@maisdeus.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password
