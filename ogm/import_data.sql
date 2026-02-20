SET FOREIGN_KEY_CHECKS=0;
TRUNCATE TABLE companies;
TRUNCATE TABLE categories;
TRUNCATE TABLE neighborhoods;
SET FOREIGN_KEY_CHECKS=1;

-- Categories
INSERT INTO categories (id, name, slug) VALUES (1, 'Restaurante', 'restaurante');
INSERT INTO categories (id, name, slug) VALUES (2, 'Pizzaria', 'pizzaria');
INSERT INTO categories (id, name, slug) VALUES (3, 'Farmácia', 'farmácia');
INSERT INTO categories (id, name, slug) VALUES (4, 'Supermercado', 'supermercado');
INSERT INTO categories (id, name, slug) VALUES (5, 'Academia', 'academia');
INSERT INTO categories (id, name, slug) VALUES (6, 'Oficina Mecânica', 'oficina-mecânica');
INSERT INTO categories (id, name, slug) VALUES (7, 'Escola', 'escola');
INSERT INTO categories (id, name, slug) VALUES (8, 'Hotel', 'hotel');
INSERT INTO categories (id, name, slug) VALUES (9, 'Clínica Médica', 'clínica-médica');
INSERT INTO categories (id, name, slug) VALUES (10, 'Salão de Beleza', 'salão-de-beleza');
INSERT INTO categories (id, name, slug) VALUES (11, 'Loja de Roupas', 'loja-de-roupas');
INSERT INTO categories (id, name, slug) VALUES (12, 'Construtora', 'construtora');
INSERT INTO categories (id, name, slug) VALUES (13, 'Advogado', 'advogado');
INSERT INTO categories (id, name, slug) VALUES (14, 'Padaria', 'padaria');

-- Companies & Neighborhoods