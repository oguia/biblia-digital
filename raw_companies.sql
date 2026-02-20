
CREATE TABLE empresas (
    id INT PRIMARY KEY,
    nome VARCHAR(255),
    categoria VARCHAR(150),
    rua VARCHAR(255),
    numero VARCHAR(20),
    bairro VARCHAR(150),
    cidade VARCHAR(150),
    estado VARCHAR(2),
    cep VARCHAR(10),
    telefone VARCHAR(20),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    imagem VARCHAR(255)
);


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(1, 'Supermercado Exemplo 1', 'Supermercado', 'Rua Mateus Leme', '3619', 'Santa Felicidade', 'Campo Largo', 'PR', '87177-254', '(41) 95543-8971', -25.47445306, -49.52527745, '/images/supermercado/1.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(2, 'Papelaria Exemplo 2', 'Papelaria', 'Avenida Paraná', '1439', 'Cabral', 'Curitiba', 'PR', '83327-103', '(41) 99585-3015', -25.41978935, -49.27313496, '/images/papelaria/2.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(3, 'Farmácia Exemplo 3', 'Farmácia', 'Rua João Bettega', '1929', 'Rebouças', 'Curitiba', 'PR', '86130-345', '(41) 99342-9490', -25.42737418, -49.27900099, '/images/farmácia/3.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(4, 'Oficina Mecânica Exemplo 4', 'Oficina Mecânica', 'Avenida Sete de Setembro', '4629', 'Batel', 'Almirante Tamandaré', 'PR', '83505-996', '(41) 94857-5769', -25.32757258, -49.3056729, '/images/oficina_mecânica/4.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(5, 'Construtora Exemplo 5', 'Construtora', 'Rua João Bettega', '2183', 'Santa Felicidade', 'Colombo', 'PR', '89977-699', '(41) 94059-6255', -25.28529968, -49.21507573, '/images/construtora/5.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(6, 'Escola Exemplo 6', 'Escola', 'Rua Marechal Deodoro', '367', 'Água Verde', 'Almirante Tamandaré', 'PR', '84106-531', '(41) 97866-7287', -25.32401899, -49.33111217, '/images/escola/6.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(7, 'Papelaria Exemplo 7', 'Papelaria', 'Rua Brigadeiro Franco', '1830', 'Cabral', 'Almirante Tamandaré', 'PR', '86815-600', '(41) 93983-6671', -25.31343775, -49.31521745, '/images/papelaria/7.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(8, 'Farmácia Exemplo 8', 'Farmácia', 'Avenida Sete de Setembro', '1297', 'Boqueirão', 'Pinhais', 'PR', '88186-147', '(41) 96793-3294', -25.45948264, -49.19746896, '/images/farmácia/8.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(9, 'Oficina Mecânica Exemplo 9', 'Oficina Mecânica', 'Avenida República Argentina', '2510', 'Centro', 'Colombo', 'PR', '83638-738', '(41) 99674-8613', -25.27468829, -49.23612221, '/images/oficina_mecânica/9.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(10, 'Papelaria Exemplo 10', 'Papelaria', 'Rua Brigadeiro Franco', '3644', 'Cabral', 'Araucária', 'PR', '81547-999', '(41) 94101-1968', -25.56873191, -49.40924663, '/images/papelaria/10.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(11, 'Empresa de Tecnologia Exemplo 11', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '1907', 'Bigorrilho', 'Pinhais', 'PR', '88862-254', '(41) 97707-6885', -25.45175902, -49.20351499, '/images/empresa_de_tecnologia/11.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(12, 'Empresa de Tecnologia Exemplo 12', 'Empresa de Tecnologia', 'Rua Brigadeiro Franco', '518', 'Rebouças', 'Colombo', 'PR', '88181-903', '(41) 98355-2542', -25.27745869, -49.2268088, '/images/empresa_de_tecnologia/12.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(13, 'Escola Exemplo 13', 'Escola', 'Rua João Bettega', '207', 'Batel', 'Curitiba', 'PR', '89892-894', '(41) 97853-1883', -25.44444244, -49.29319192, '/images/escola/13.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(14, 'Construtora Exemplo 14', 'Construtora', 'Rua Mateus Leme', '1918', 'Portão', 'Araucária', 'PR', '83438-492', '(41) 97459-8101', -25.57519706, -49.39614952, '/images/construtora/14.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(15, 'Oficina Mecânica Exemplo 15', 'Oficina Mecânica', 'Rua Mateus Leme', '4186', 'Bigorrilho', 'Curitiba', 'PR', '87744-793', '(41) 96452-3499', -25.42244327, -49.2594398, '/images/oficina_mecânica/15.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(16, 'Restaurante Exemplo 16', 'Restaurante', 'Avenida das Torres', '3079', 'Portão', 'Pinhais', 'PR', '83062-313', '(41) 99879-3696', -25.43597547, -49.17278539, '/images/restaurante/16.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(17, 'Restaurante Exemplo 17', 'Restaurante', 'Avenida Paraná', '1841', 'Bigorrilho', 'Araucária', 'PR', '86391-492', '(41) 97582-9158', -25.57860416, -49.41955507, '/images/restaurante/17.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(18, 'Oficina Mecânica Exemplo 18', 'Oficina Mecânica', 'Rua Anita Garibaldi', '1325', 'Portão', 'São José dos Pinhais', 'PR', '86629-245', '(41) 91587-5856', -25.52184072, -49.21397889, '/images/oficina_mecânica/18.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(19, 'Escritório de Advocacia Exemplo 19', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '4683', 'Portão', 'Pinhais', 'PR', '86466-336', '(41) 92592-5532', -25.44506138, -49.17600955, '/images/escritório_de_advocacia/19.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(20, 'Escola Exemplo 20', 'Escola', 'Rua XV de Novembro', '4693', 'CIC', 'Colombo', 'PR', '88032-844', '(41) 91263-8685', -25.29126141, -49.22545982, '/images/escola/20.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(21, 'Farmácia Exemplo 21', 'Farmácia', 'Rua Anita Garibaldi', '3150', 'CIC', 'Pinhais', 'PR', '89949-187', '(41) 98353-5217', -25.44878591, -49.17813785, '/images/farmácia/21.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(22, 'Escritório de Advocacia Exemplo 22', 'Escritório de Advocacia', 'Rua João Bettega', '1288', 'Água Verde', 'Curitiba', 'PR', '82696-110', '(41) 97774-3560', -25.43454575, -49.25688117, '/images/escritório_de_advocacia/22.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(23, 'Salão de Beleza Exemplo 23', 'Salão de Beleza', 'Rua João Bettega', '2462', 'Rebouças', 'Almirante Tamandaré', 'PR', '84252-891', '(41) 92133-8513', -25.3148847, -49.31512313, '/images/salão_de_beleza/23.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(24, 'Papelaria Exemplo 24', 'Papelaria', 'Avenida República Argentina', '3174', 'Rebouças', 'Campo Largo', 'PR', '82205-753', '(41) 92359-3939', -25.45247491, -49.53143056, '/images/papelaria/24.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(25, 'Hotel Exemplo 25', 'Hotel', 'Rua João Bettega', '919', 'Cabral', 'São José dos Pinhais', 'PR', '86886-142', '(41) 95522-1759', -25.53482851, -49.19180934, '/images/hotel/25.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(26, 'Oficina Mecânica Exemplo 26', 'Oficina Mecânica', 'Rua XV de Novembro', '4087', 'Boqueirão', 'Curitiba', 'PR', '88235-691', '(41) 95013-2420', -25.4305066, -49.26366633, '/images/oficina_mecânica/26.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(27, 'Escritório de Advocacia Exemplo 27', 'Escritório de Advocacia', 'Rua João Bettega', '2811', 'Santa Felicidade', 'Campo Largo', 'PR', '88536-815', '(41) 92542-1859', -25.45236229, -49.51232007, '/images/escritório_de_advocacia/27.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(28, 'Papelaria Exemplo 28', 'Papelaria', 'Rua XV de Novembro', '3239', 'Cabral', 'Pinhais', 'PR', '84924-575', '(41) 94911-6779', -25.45626915, -49.17940893, '/images/papelaria/28.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(29, 'Empresa de Tecnologia Exemplo 29', 'Empresa de Tecnologia', 'Avenida das Torres', '964', 'Batel', 'Pinhais', 'PR', '83066-824', '(41) 93563-5553', -25.44226726, -49.21184635, '/images/empresa_de_tecnologia/29.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(30, 'Academia Exemplo 30', 'Academia', 'Rua João Bettega', '3786', 'Boqueirão', 'Pinhais', 'PR', '88051-142', '(41) 91641-9053', -25.4614388, -49.17571481, '/images/academia/30.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(31, 'Salão de Beleza Exemplo 31', 'Salão de Beleza', 'Rua XV de Novembro', '936', 'Portão', 'Almirante Tamandaré', 'PR', '84606-880', '(41) 94357-5006', -25.29335031, -49.31603581, '/images/salão_de_beleza/31.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(32, 'Hotel Exemplo 32', 'Hotel', 'Avenida Sete de Setembro', '2812', 'Boqueirão', 'Pinhais', 'PR', '88878-525', '(41) 97614-4503', -25.44033137, -49.19710873, '/images/hotel/32.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(33, 'Oficina Mecânica Exemplo 33', 'Oficina Mecânica', 'Rua Mateus Leme', '378', 'Água Verde', 'Curitiba', 'PR', '84355-841', '(41) 94665-7132', -25.42244604, -49.27654243, '/images/oficina_mecânica/33.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(34, 'Clínica Médica Exemplo 34', 'Clínica Médica', 'Rua João Bettega', '2224', 'CIC', 'São José dos Pinhais', 'PR', '86239-221', '(41) 92621-5389', -25.53941943, -49.19649081, '/images/clínica_médica/34.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(35, 'Padaria Exemplo 35', 'Padaria', 'Rua Marechal Deodoro', '1983', 'Santa Felicidade', 'Colombo', 'PR', '89343-608', '(41) 99948-8903', -25.29116102, -49.21901206, '/images/padaria/35.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(36, 'Construtora Exemplo 36', 'Construtora', 'Avenida das Torres', '3832', 'Cabral', 'Pinhais', 'PR', '84595-844', '(41) 98950-8907', -25.42574552, -49.19839887, '/images/construtora/36.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(37, 'Empresa de Tecnologia Exemplo 37', 'Empresa de Tecnologia', 'Avenida das Torres', '2386', 'Batel', 'Pinhais', 'PR', '86042-484', '(41) 93855-5703', -25.44543828, -49.19403863, '/images/empresa_de_tecnologia/37.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(38, 'Empresa de Tecnologia Exemplo 38', 'Empresa de Tecnologia', 'Avenida Paraná', '1427', 'Batel', 'Campo Largo', 'PR', '85655-972', '(41) 94453-9654', -25.44909113, -49.53669923, '/images/empresa_de_tecnologia/38.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(39, 'Papelaria Exemplo 39', 'Papelaria', 'Rua Brigadeiro Franco', '1260', 'Santa Felicidade', 'Almirante Tamandaré', 'PR', '84770-805', '(41) 91826-3482', -25.3236884, -49.31889478, '/images/papelaria/39.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(40, 'Escola Exemplo 40', 'Escola', 'Rua Mateus Leme', '2247', 'Água Verde', 'Almirante Tamandaré', 'PR', '87005-750', '(41) 99797-3721', -25.30111052, -49.29852839, '/images/escola/40.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(41, 'Hotel Exemplo 41', 'Hotel', 'Rua XV de Novembro', '359', 'Centro', 'São José dos Pinhais', 'PR', '87126-765', '(41) 96381-3446', -25.52918264, -49.21150424, '/images/hotel/41.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(42, 'Construtora Exemplo 42', 'Construtora', 'Avenida República Argentina', '4230', 'CIC', 'São José dos Pinhais', 'PR', '83879-741', '(41) 99074-1005', -25.53495865, -49.21699753, '/images/construtora/42.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(43, 'Clínica Médica Exemplo 43', 'Clínica Médica', 'Avenida Sete de Setembro', '4827', 'CIC', 'Araucária', 'PR', '81556-100', '(41) 98109-4122', -25.56973862, -49.41359667, '/images/clínica_médica/43.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(44, 'Salão de Beleza Exemplo 44', 'Salão de Beleza', 'Rua XV de Novembro', '1478', 'Batel', 'Curitiba', 'PR', '81213-401', '(41) 99599-4047', -25.41549053, -49.28651953, '/images/salão_de_beleza/44.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(45, 'Salão de Beleza Exemplo 45', 'Salão de Beleza', 'Rua João Bettega', '3654', 'Boqueirão', 'Campo Largo', 'PR', '82664-573', '(41) 91315-8888', -25.4743511, -49.54736758, '/images/salão_de_beleza/45.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(46, 'Academia Exemplo 46', 'Academia', 'Avenida Sete de Setembro', '3821', 'Boqueirão', 'Campo Largo', 'PR', '85970-977', '(41) 93450-5715', -25.47100699, -49.53134805, '/images/academia/46.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(47, 'Papelaria Exemplo 47', 'Papelaria', 'Avenida Sete de Setembro', '4691', 'Cabral', 'Campo Largo', 'PR', '89127-243', '(41) 96334-9458', -25.46472135, -49.53828655, '/images/papelaria/47.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(48, 'Escritório de Advocacia Exemplo 48', 'Escritório de Advocacia', 'Rua Mateus Leme', '3848', 'Água Verde', 'Curitiba', 'PR', '87205-287', '(41) 98117-4107', -25.44300532, -49.25902711, '/images/escritório_de_advocacia/48.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(49, 'Padaria Exemplo 49', 'Padaria', 'Rua XV de Novembro', '2806', 'Santa Felicidade', 'Pinhais', 'PR', '88113-761', '(41) 96292-5510', -25.44442323, -49.21094971, '/images/padaria/49.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(50, 'Farmácia Exemplo 50', 'Farmácia', 'Rua Anita Garibaldi', '2513', 'Portão', 'Pinhais', 'PR', '89359-597', '(41) 94996-5333', -25.4378909, -49.18344939, '/images/farmácia/50.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(51, 'Empresa de Tecnologia Exemplo 51', 'Empresa de Tecnologia', 'Avenida Paraná', '3421', 'Boqueirão', 'Campo Largo', 'PR', '87565-949', '(41) 94821-2428', -25.44888797, -49.53340042, '/images/empresa_de_tecnologia/51.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(52, 'Padaria Exemplo 52', 'Padaria', 'Rua Mateus Leme', '3429', 'Portão', 'Araucária', 'PR', '88794-417', '(41) 97771-9220', -25.60154465, -49.41804197, '/images/padaria/52.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(53, 'Escritório de Advocacia Exemplo 53', 'Escritório de Advocacia', 'Rua Anita Garibaldi', '4102', 'Água Verde', 'Almirante Tamandaré', 'PR', '88007-729', '(41) 98560-6464', -25.3147677, -49.31191508, '/images/escritório_de_advocacia/53.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(54, 'Farmácia Exemplo 54', 'Farmácia', 'Avenida Sete de Setembro', '4181', 'Batel', 'Araucária', 'PR', '81555-560', '(41) 98874-1171', -25.59103668, -49.38597179, '/images/farmácia/54.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(55, 'Farmácia Exemplo 55', 'Farmácia', 'Rua Mateus Leme', '408', 'Batel', 'Almirante Tamandaré', 'PR', '88695-657', '(41) 96656-8296', -25.29542987, -49.29508265, '/images/farmácia/55.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(56, 'Salão de Beleza Exemplo 56', 'Salão de Beleza', 'Avenida República Argentina', '4753', 'Bigorrilho', 'São José dos Pinhais', 'PR', '86709-608', '(41) 93933-6861', -25.54120087, -49.19022861, '/images/salão_de_beleza/56.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(57, 'Supermercado Exemplo 57', 'Supermercado', 'Avenida Sete de Setembro', '1999', 'Bigorrilho', 'Pinhais', 'PR', '83268-724', '(41) 93825-5072', -25.44822743, -49.20410864, '/images/supermercado/57.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(58, 'Loja de Roupas Exemplo 58', 'Loja de Roupas', 'Rua Anita Garibaldi', '2075', 'Bigorrilho', 'Araucária', 'PR', '82871-918', '(41) 91007-8816', -25.59485902, -49.39793369, '/images/loja_de_roupas/58.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(59, 'Restaurante Exemplo 59', 'Restaurante', 'Avenida República Argentina', '1243', 'Cabral', 'Colombo', 'PR', '86260-448', '(41) 98320-3257', -25.29797222, -49.22776755, '/images/restaurante/59.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(60, 'Hotel Exemplo 60', 'Hotel', 'Avenida República Argentina', '4233', 'Centro', 'Araucária', 'PR', '85749-678', '(41) 99405-6421', -25.58326645, -49.40312102, '/images/hotel/60.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(61, 'Padaria Exemplo 61', 'Padaria', 'Rua Brigadeiro Franco', '3472', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '86647-393', '(41) 95982-8586', -25.29737436, -49.32963647, '/images/padaria/61.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(62, 'Padaria Exemplo 62', 'Padaria', 'Rua Brigadeiro Franco', '3752', 'Batel', 'Campo Largo', 'PR', '89189-219', '(41) 95106-4878', -25.45758909, -49.54643504, '/images/padaria/62.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(63, 'Clínica Médica Exemplo 63', 'Clínica Médica', 'Rua Anita Garibaldi', '4156', 'Bigorrilho', 'Colombo', 'PR', '89976-285', '(41) 91652-4003', -25.28173271, -49.22018322, '/images/clínica_médica/63.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(64, 'Hotel Exemplo 64', 'Hotel', 'Avenida República Argentina', '711', 'Centro', 'Araucária', 'PR', '85460-113', '(41) 93490-6138', -25.58179369, -49.41536694, '/images/hotel/64.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(65, 'Empresa de Tecnologia Exemplo 65', 'Empresa de Tecnologia', 'Avenida Paraná', '2999', 'Centro', 'Campo Largo', 'PR', '82750-687', '(41) 91667-5935', -25.44266722, -49.52077581, '/images/empresa_de_tecnologia/65.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(66, 'Salão de Beleza Exemplo 66', 'Salão de Beleza', 'Rua Marechal Deodoro', '4744', 'Rebouças', 'Pinhais', 'PR', '83401-718', '(41) 92300-2624', -25.44196941, -49.1841543, '/images/salão_de_beleza/66.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(67, 'Clínica Médica Exemplo 67', 'Clínica Médica', 'Rua Marechal Deodoro', '1229', 'Água Verde', 'Araucária', 'PR', '82674-494', '(41) 99812-9996', -25.58818366, -49.39167842, '/images/clínica_médica/67.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(68, 'Restaurante Exemplo 68', 'Restaurante', 'Avenida Paraná', '1881', 'Boqueirão', 'Curitiba', 'PR', '85054-633', '(41) 95638-2824', -25.41361798, -49.25497264, '/images/restaurante/68.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(69, 'Academia Exemplo 69', 'Academia', 'Avenida República Argentina', '780', 'Batel', 'São José dos Pinhais', 'PR', '85457-769', '(41) 93303-9916', -25.54015903, -49.1908895, '/images/academia/69.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(70, 'Academia Exemplo 70', 'Academia', 'Rua Mateus Leme', '839', 'Boqueirão', 'São José dos Pinhais', 'PR', '85191-659', '(41) 96133-8778', -25.52800565, -49.21616183, '/images/academia/70.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(71, 'Escola Exemplo 71', 'Escola', 'Rua Anita Garibaldi', '915', 'Boqueirão', 'Araucária', 'PR', '88838-936', '(41) 93928-7549', -25.57239673, -49.42108289, '/images/escola/71.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(72, 'Restaurante Exemplo 72', 'Restaurante', 'Avenida Paraná', '1136', 'Bigorrilho', 'Araucária', 'PR', '81516-580', '(41) 91993-1889', -25.59421293, -49.40084896, '/images/restaurante/72.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(73, 'Empresa de Tecnologia Exemplo 73', 'Empresa de Tecnologia', 'Avenida Sete de Setembro', '2787', 'Cabral', 'Araucária', 'PR', '84925-162', '(41) 96185-9976', -25.59847995, -49.40912751, '/images/empresa_de_tecnologia/73.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(74, 'Supermercado Exemplo 74', 'Supermercado', 'Avenida Sete de Setembro', '2170', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '88827-493', '(41) 98181-6657', -25.3280593, -49.32006518, '/images/supermercado/74.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(75, 'Construtora Exemplo 75', 'Construtora', 'Avenida das Torres', '2519', 'Rebouças', 'Araucária', 'PR', '85737-414', '(41) 94930-2837', -25.57581928, -49.39917228, '/images/construtora/75.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(76, 'Escritório de Advocacia Exemplo 76', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '2422', 'Portão', 'São José dos Pinhais', 'PR', '86926-855', '(41) 97874-5854', -25.54876297, -49.21213194, '/images/escritório_de_advocacia/76.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(77, 'Supermercado Exemplo 77', 'Supermercado', 'Rua Mateus Leme', '3817', 'Cabral', 'Pinhais', 'PR', '88926-868', '(41) 99302-9127', -25.4472247, -49.17287489, '/images/supermercado/77.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(78, 'Supermercado Exemplo 78', 'Supermercado', 'Avenida República Argentina', '2723', 'Centro', 'Colombo', 'PR', '84587-155', '(41) 99776-2219', -25.30966521, -49.24086496, '/images/supermercado/78.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(79, 'Empresa de Tecnologia Exemplo 79', 'Empresa de Tecnologia', 'Avenida República Argentina', '768', 'Portão', 'São José dos Pinhais', 'PR', '86074-721', '(41) 94082-2068', -25.54637507, -49.18507253, '/images/empresa_de_tecnologia/79.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(80, 'Clínica Médica Exemplo 80', 'Clínica Médica', 'Avenida Paraná', '3220', 'Portão', 'São José dos Pinhais', 'PR', '87388-128', '(41) 93266-3193', -25.5232248, -49.19417111, '/images/clínica_médica/80.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(81, 'Papelaria Exemplo 81', 'Papelaria', 'Rua Mateus Leme', '1902', 'Água Verde', 'Curitiba', 'PR', '86125-242', '(41) 92152-1968', -25.43813125, -49.25477493, '/images/papelaria/81.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(82, 'Farmácia Exemplo 82', 'Farmácia', 'Rua Marechal Deodoro', '1475', 'Cabral', 'Curitiba', 'PR', '89531-531', '(41) 92565-3336', -25.4152425, -49.26648797, '/images/farmácia/82.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(83, 'Escola Exemplo 83', 'Escola', 'Avenida Sete de Setembro', '4897', 'CIC', 'Pinhais', 'PR', '82426-539', '(41) 94899-9842', -25.43306962, -49.17752443, '/images/escola/83.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(84, 'Construtora Exemplo 84', 'Construtora', 'Avenida das Torres', '3958', 'Batel', 'Colombo', 'PR', '87263-880', '(41) 92223-6147', -25.30452741, -49.20980736, '/images/construtora/84.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(85, 'Supermercado Exemplo 85', 'Supermercado', 'Avenida Sete de Setembro', '3776', 'CIC', 'São José dos Pinhais', 'PR', '84380-172', '(41) 97245-3610', -25.55070434, -49.1836074, '/images/supermercado/85.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(86, 'Construtora Exemplo 86', 'Construtora', 'Avenida das Torres', '2157', 'Rebouças', 'Pinhais', 'PR', '88704-384', '(41) 96052-8022', -25.44846874, -49.20189656, '/images/construtora/86.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(87, 'Construtora Exemplo 87', 'Construtora', 'Avenida Paraná', '3672', 'Rebouças', 'São José dos Pinhais', 'PR', '89022-550', '(41) 98144-6643', -25.54542093, -49.20452161, '/images/construtora/87.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(88, 'Empresa de Tecnologia Exemplo 88', 'Empresa de Tecnologia', 'Rua João Bettega', '2719', 'Centro', 'Campo Largo', 'PR', '81963-238', '(41) 93624-6491', -25.4644777, -49.52250509, '/images/empresa_de_tecnologia/88.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(89, 'Supermercado Exemplo 89', 'Supermercado', 'Rua Anita Garibaldi', '124', 'Santa Felicidade', 'Araucária', 'PR', '85004-984', '(41) 91593-2038', -25.59827678, -49.40024783, '/images/supermercado/89.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(90, 'Hotel Exemplo 90', 'Hotel', 'Rua Anita Garibaldi', '220', 'Água Verde', 'Pinhais', 'PR', '84548-450', '(41) 97278-3499', -25.44664762, -49.18152872, '/images/hotel/90.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(91, 'Escola Exemplo 91', 'Escola', 'Rua Brigadeiro Franco', '1756', 'Bigorrilho', 'São José dos Pinhais', 'PR', '82251-497', '(41) 97193-2725', -25.51179475, -49.20027613, '/images/escola/91.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(92, 'Oficina Mecânica Exemplo 92', 'Oficina Mecânica', 'Rua Mateus Leme', '3744', 'Batel', 'São José dos Pinhais', 'PR', '88544-362', '(41) 94926-5227', -25.51794456, -49.20166606, '/images/oficina_mecânica/92.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(93, 'Oficina Mecânica Exemplo 93', 'Oficina Mecânica', 'Rua Brigadeiro Franco', '3121', 'Cabral', 'Araucária', 'PR', '82439-399', '(41) 94308-7568', -25.57518305, -49.38687475, '/images/oficina_mecânica/93.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(94, 'Padaria Exemplo 94', 'Padaria', 'Avenida Paraná', '306', 'Água Verde', 'Colombo', 'PR', '83732-441', '(41) 99166-3588', -25.28112734, -49.22084582, '/images/padaria/94.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(95, 'Construtora Exemplo 95', 'Construtora', 'Rua João Bettega', '354', 'Cabral', 'Pinhais', 'PR', '81926-521', '(41) 94315-1839', -25.42498156, -49.20396638, '/images/construtora/95.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(96, 'Supermercado Exemplo 96', 'Supermercado', 'Rua XV de Novembro', '4776', 'Cabral', 'Araucária', 'PR', '87237-842', '(41) 94075-2928', -25.59465063, -49.40374666, '/images/supermercado/96.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(97, 'Construtora Exemplo 97', 'Construtora', 'Rua João Bettega', '4241', 'Santa Felicidade', 'Curitiba', 'PR', '87129-477', '(41) 96413-3592', -25.43221712, -49.29039584, '/images/construtora/97.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(98, 'Supermercado Exemplo 98', 'Supermercado', 'Rua João Bettega', '1761', 'Bigorrilho', 'Colombo', 'PR', '89419-384', '(41) 91146-3110', -25.30223755, -49.20845489, '/images/supermercado/98.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(99, 'Loja de Roupas Exemplo 99', 'Loja de Roupas', 'Avenida República Argentina', '714', 'Santa Felicidade', 'Pinhais', 'PR', '81428-156', '(41) 97244-5418', -25.43327409, -49.18528651, '/images/loja_de_roupas/99.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(100, 'Escritório de Advocacia Exemplo 100', 'Escritório de Advocacia', 'Rua João Bettega', '1225', 'Cabral', 'Curitiba', 'PR', '85186-400', '(41) 98707-3069', -25.41376983, -49.28076216, '/images/escritório_de_advocacia/100.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(101, 'Salão de Beleza Exemplo 101', 'Salão de Beleza', 'Rua Anita Garibaldi', '1313', 'Cabral', 'Araucária', 'PR', '88171-405', '(41) 97089-5610', -25.5695819, -49.40627264, '/images/salão_de_beleza/101.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(102, 'Farmácia Exemplo 102', 'Farmácia', 'Rua Mateus Leme', '2679', 'Rebouças', 'Pinhais', 'PR', '88995-110', '(41) 96324-2680', -25.4482969, -49.17827014, '/images/farmácia/102.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(103, 'Supermercado Exemplo 103', 'Supermercado', 'Rua XV de Novembro', '3369', 'Boqueirão', 'São José dos Pinhais', 'PR', '85601-410', '(41) 93825-1398', -25.53251375, -49.21746915, '/images/supermercado/103.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(104, 'Escola Exemplo 104', 'Escola', 'Rua Mateus Leme', '530', 'Água Verde', 'Pinhais', 'PR', '86348-120', '(41) 96260-3094', -25.45571761, -49.21225313, '/images/escola/104.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(105, 'Empresa de Tecnologia Exemplo 105', 'Empresa de Tecnologia', 'Rua João Bettega', '2297', 'Centro', 'Campo Largo', 'PR', '89674-527', '(41) 99037-5763', -25.47598426, -49.53628774, '/images/empresa_de_tecnologia/105.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(106, 'Clínica Médica Exemplo 106', 'Clínica Médica', 'Avenida Paraná', '4301', 'Rebouças', 'São José dos Pinhais', 'PR', '88962-978', '(41) 99236-1297', -25.51476629, -49.18684663, '/images/clínica_médica/106.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(107, 'Clínica Médica Exemplo 107', 'Clínica Médica', 'Rua Mateus Leme', '349', 'Bigorrilho', 'São José dos Pinhais', 'PR', '82372-571', '(41) 95649-6592', -25.54637447, -49.20738145, '/images/clínica_médica/107.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(108, 'Salão de Beleza Exemplo 108', 'Salão de Beleza', 'Rua Anita Garibaldi', '287', 'Cabral', 'Araucária', 'PR', '87205-275', '(41) 97272-5414', -25.58097948, -49.39494378, '/images/salão_de_beleza/108.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(109, 'Padaria Exemplo 109', 'Padaria', 'Rua Anita Garibaldi', '4940', 'Bigorrilho', 'São José dos Pinhais', 'PR', '83076-872', '(41) 94689-1217', -25.53524967, -49.20057561, '/images/padaria/109.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(110, 'Escritório de Advocacia Exemplo 110', 'Escritório de Advocacia', 'Avenida Sete de Setembro', '657', 'Rebouças', 'Colombo', 'PR', '83506-227', '(41) 99305-5650', -25.29811316, -49.24118623, '/images/escritório_de_advocacia/110.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(111, 'Padaria Exemplo 111', 'Padaria', 'Rua Brigadeiro Franco', '646', 'Batel', 'Colombo', 'PR', '89396-525', '(41) 97969-4929', -25.28669715, -49.23478585, '/images/padaria/111.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(112, 'Farmácia Exemplo 112', 'Farmácia', 'Avenida Paraná', '4164', 'Cabral', 'Araucária', 'PR', '84302-963', '(41) 95670-9195', -25.5889075, -49.39365092, '/images/farmácia/112.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(113, 'Escritório de Advocacia Exemplo 113', 'Escritório de Advocacia', 'Rua Mateus Leme', '2917', 'Cabral', 'São José dos Pinhais', 'PR', '85110-812', '(41) 97038-4375', -25.52303618, -49.21710592, '/images/escritório_de_advocacia/113.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(114, 'Supermercado Exemplo 114', 'Supermercado', 'Rua XV de Novembro', '3755', 'Boqueirão', 'Colombo', 'PR', '86072-453', '(41) 94728-4511', -25.29893619, -49.23987767, '/images/supermercado/114.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(115, 'Escritório de Advocacia Exemplo 115', 'Escritório de Advocacia', 'Avenida República Argentina', '4289', 'Bigorrilho', 'Colombo', 'PR', '83565-454', '(41) 97067-9848', -25.27489043, -49.22696085, '/images/escritório_de_advocacia/115.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(116, 'Papelaria Exemplo 116', 'Papelaria', 'Avenida das Torres', '2014', 'CIC', 'São José dos Pinhais', 'PR', '83827-432', '(41) 95234-9671', -25.52967208, -49.20988498, '/images/papelaria/116.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(117, 'Padaria Exemplo 117', 'Padaria', 'Rua Brigadeiro Franco', '1011', 'Rebouças', 'São José dos Pinhais', 'PR', '83437-270', '(41) 98095-4324', -25.53141083, -49.20029335, '/images/padaria/117.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(118, 'Restaurante Exemplo 118', 'Restaurante', 'Rua Mateus Leme', '4429', 'Boqueirão', 'São José dos Pinhais', 'PR', '85700-773', '(41) 97252-9938', -25.54516998, -49.18465668, '/images/restaurante/118.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(119, 'Farmácia Exemplo 119', 'Farmácia', 'Avenida Sete de Setembro', '2328', 'Água Verde', 'Pinhais', 'PR', '83459-228', '(41) 98590-4724', -25.44848126, -49.206071, '/images/farmácia/119.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(120, 'Salão de Beleza Exemplo 120', 'Salão de Beleza', 'Rua Mateus Leme', '325', 'CIC', 'Almirante Tamandaré', 'PR', '89793-180', '(41) 96882-1507', -25.32572573, -49.29696026, '/images/salão_de_beleza/120.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(121, 'Empresa de Tecnologia Exemplo 121', 'Empresa de Tecnologia', 'Avenida República Argentina', '2249', 'Rebouças', 'Almirante Tamandaré', 'PR', '81267-610', '(41) 97286-1270', -25.30231635, -49.30328381, '/images/empresa_de_tecnologia/121.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(122, 'Restaurante Exemplo 122', 'Restaurante', 'Rua Anita Garibaldi', '3635', 'Cabral', 'Pinhais', 'PR', '86687-421', '(41) 94684-4921', -25.45895135, -49.20492599, '/images/restaurante/122.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(123, 'Oficina Mecânica Exemplo 123', 'Oficina Mecânica', 'Rua Brigadeiro Franco', '463', 'Centro', 'São José dos Pinhais', 'PR', '83616-114', '(41) 91650-1595', -25.51684474, -49.19129634, '/images/oficina_mecânica/123.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(124, 'Farmácia Exemplo 124', 'Farmácia', 'Rua Mateus Leme', '2391', 'Santa Felicidade', 'Pinhais', 'PR', '82409-978', '(41) 92731-5138', -25.45767707, -49.2014999, '/images/farmácia/124.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(125, 'Construtora Exemplo 125', 'Construtora', 'Avenida das Torres', '981', 'Rebouças', 'São José dos Pinhais', 'PR', '87268-312', '(41) 98264-3797', -25.52158452, -49.20620584, '/images/construtora/125.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(126, 'Papelaria Exemplo 126', 'Papelaria', 'Avenida Paraná', '3732', 'Boqueirão', 'Campo Largo', 'PR', '85687-581', '(41) 93694-5690', -25.45882372, -49.52015543, '/images/papelaria/126.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(127, 'Clínica Médica Exemplo 127', 'Clínica Médica', 'Rua João Bettega', '4282', 'Água Verde', 'Campo Largo', 'PR', '89529-317', '(41) 99019-6690', -25.45448438, -49.51221215, '/images/clínica_médica/127.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(128, 'Escritório de Advocacia Exemplo 128', 'Escritório de Advocacia', 'Rua Marechal Deodoro', '2786', 'Portão', 'São José dos Pinhais', 'PR', '82428-468', '(41) 94384-4649', -25.52308376, -49.19628884, '/images/escritório_de_advocacia/128.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(129, 'Restaurante Exemplo 129', 'Restaurante', 'Avenida Sete de Setembro', '1600', 'Batel', 'Curitiba', 'PR', '88473-273', '(41) 96402-1206', -25.4407671, -49.28339544, '/images/restaurante/129.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(130, 'Escola Exemplo 130', 'Escola', 'Rua João Bettega', '2124', 'Portão', 'Campo Largo', 'PR', '89325-598', '(41) 93036-2195', -25.45445803, -49.519167, '/images/escola/130.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(131, 'Loja de Roupas Exemplo 131', 'Loja de Roupas', 'Rua Brigadeiro Franco', '1517', 'Santa Felicidade', 'Curitiba', 'PR', '83954-389', '(41) 98955-1792', -25.43830879, -49.26090031, '/images/loja_de_roupas/131.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(132, 'Farmácia Exemplo 132', 'Farmácia', 'Rua João Bettega', '2077', 'Santa Felicidade', 'Curitiba', 'PR', '82539-755', '(41) 99044-5042', -25.41270761, -49.28798275, '/images/farmácia/132.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(133, 'Loja de Roupas Exemplo 133', 'Loja de Roupas', 'Rua Marechal Deodoro', '4816', 'Bigorrilho', 'Pinhais', 'PR', '84998-791', '(41) 99304-9031', -25.42480718, -49.19744885, '/images/loja_de_roupas/133.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(134, 'Restaurante Exemplo 134', 'Restaurante', 'Avenida República Argentina', '3658', 'Batel', 'Curitiba', 'PR', '83163-284', '(41) 92526-5419', -25.43794693, -49.27195092, '/images/restaurante/134.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(135, 'Escola Exemplo 135', 'Escola', 'Avenida das Torres', '4967', 'CIC', 'Araucária', 'PR', '82396-862', '(41) 95226-5124', -25.59390317, -49.40681742, '/images/escola/135.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(136, 'Padaria Exemplo 136', 'Padaria', 'Rua Marechal Deodoro', '675', 'Boqueirão', 'Colombo', 'PR', '83179-619', '(41) 93022-2969', -25.27434452, -49.20814398, '/images/padaria/136.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(137, 'Farmácia Exemplo 137', 'Farmácia', 'Rua Brigadeiro Franco', '514', 'Rebouças', 'Campo Largo', 'PR', '82065-154', '(41) 97195-6873', -25.45194207, -49.53625434, '/images/farmácia/137.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(138, 'Clínica Médica Exemplo 138', 'Clínica Médica', 'Rua Anita Garibaldi', '930', 'Centro', 'Curitiba', 'PR', '89736-862', '(41) 96955-8186', -25.41844001, -49.27103991, '/images/clínica_médica/138.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(139, 'Hotel Exemplo 139', 'Hotel', 'Rua Marechal Deodoro', '1148', 'CIC', 'Campo Largo', 'PR', '85578-404', '(41) 96739-2981', -25.45913442, -49.53948442, '/images/hotel/139.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(140, 'Empresa de Tecnologia Exemplo 140', 'Empresa de Tecnologia', 'Avenida das Torres', '2536', 'Boqueirão', 'Campo Largo', 'PR', '87023-902', '(41) 93896-1721', -25.47480447, -49.53497155, '/images/empresa_de_tecnologia/140.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(141, 'Hotel Exemplo 141', 'Hotel', 'Rua João Bettega', '2147', 'Rebouças', 'Araucária', 'PR', '82048-906', '(41) 99995-6087', -25.60180378, -49.41904624, '/images/hotel/141.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(142, 'Clínica Médica Exemplo 142', 'Clínica Médica', 'Rua Anita Garibaldi', '2539', 'Boqueirão', 'Pinhais', 'PR', '86777-550', '(41) 97463-8213', -25.45445241, -49.18999947, '/images/clínica_médica/142.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(143, 'Hotel Exemplo 143', 'Hotel', 'Avenida das Torres', '2606', 'Bigorrilho', 'Campo Largo', 'PR', '88007-490', '(41) 97254-8744', -25.44956037, -49.54445949, '/images/hotel/143.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(144, 'Farmácia Exemplo 144', 'Farmácia', 'Avenida Sete de Setembro', '1256', 'Boqueirão', 'Campo Largo', 'PR', '82154-247', '(41) 95256-1851', -25.47502357, -49.52467435, '/images/farmácia/144.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(145, 'Construtora Exemplo 145', 'Construtora', 'Rua João Bettega', '2442', 'Batel', 'Pinhais', 'PR', '83047-845', '(41) 97239-9289', -25.43550222, -49.17871312, '/images/construtora/145.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(146, 'Restaurante Exemplo 146', 'Restaurante', 'Rua Marechal Deodoro', '128', 'Bigorrilho', 'Curitiba', 'PR', '84911-281', '(41) 98899-8175', -25.43884393, -49.25470247, '/images/restaurante/146.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(147, 'Escola Exemplo 147', 'Escola', 'Avenida Sete de Setembro', '3805', 'Rebouças', 'Campo Largo', 'PR', '81385-926', '(41) 94969-4231', -25.46039548, -49.53899889, '/images/escola/147.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(148, 'Oficina Mecânica Exemplo 148', 'Oficina Mecânica', 'Rua Anita Garibaldi', '1791', 'Centro', 'Curitiba', 'PR', '88790-168', '(41) 96839-7797', -25.41996254, -49.26221965, '/images/oficina_mecânica/148.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(149, 'Clínica Médica Exemplo 149', 'Clínica Médica', 'Avenida República Argentina', '299', 'Bigorrilho', 'Campo Largo', 'PR', '85327-721', '(41) 94707-5810', -25.46375444, -49.51457246, '/images/clínica_médica/149.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(150, 'Oficina Mecânica Exemplo 150', 'Oficina Mecânica', 'Avenida das Torres', '2447', 'Centro', 'Campo Largo', 'PR', '86221-594', '(41) 91054-4608', -25.45545417, -49.50908666, '/images/oficina_mecânica/150.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(151, 'Oficina Mecânica Exemplo 151', 'Oficina Mecânica', 'Rua XV de Novembro', '587', 'Batel', 'Curitiba', 'PR', '87378-412', '(41) 93405-6800', -25.43577094, -49.26970714, '/images/oficina_mecânica/151.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(152, 'Supermercado Exemplo 152', 'Supermercado', 'Rua João Bettega', '3360', 'Cabral', 'Araucária', 'PR', '85055-950', '(41) 95005-6032', -25.58282333, -49.42337897, '/images/supermercado/152.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(153, 'Supermercado Exemplo 153', 'Supermercado', 'Rua Anita Garibaldi', '947', 'Bigorrilho', 'Campo Largo', 'PR', '82940-465', '(41) 93757-5347', -25.45102222, -49.51802935, '/images/supermercado/153.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(154, 'Restaurante Exemplo 154', 'Restaurante', 'Rua Marechal Deodoro', '4667', 'Batel', 'Araucária', 'PR', '81231-651', '(41) 94988-1040', -25.59538023, -49.4210887, '/images/restaurante/154.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(155, 'Supermercado Exemplo 155', 'Supermercado', 'Rua Brigadeiro Franco', '4025', 'CIC', 'Almirante Tamandaré', 'PR', '84172-317', '(41) 99760-9249', -25.30218494, -49.30640816, '/images/supermercado/155.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(156, 'Supermercado Exemplo 156', 'Supermercado', 'Rua Marechal Deodoro', '2313', 'Centro', 'Curitiba', 'PR', '82206-763', '(41) 95428-9379', -25.4338241, -49.27110028, '/images/supermercado/156.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(157, 'Hotel Exemplo 157', 'Hotel', 'Rua XV de Novembro', '3683', 'Portão', 'Campo Largo', 'PR', '87413-532', '(41) 93029-4309', -25.4708388, -49.52597807, '/images/hotel/157.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(158, 'Oficina Mecânica Exemplo 158', 'Oficina Mecânica', 'Rua Brigadeiro Franco', '3600', 'Batel', 'Araucária', 'PR', '82825-415', '(41) 99709-7601', -25.57961345, -49.4185311, '/images/oficina_mecânica/158.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(159, 'Clínica Médica Exemplo 159', 'Clínica Médica', 'Rua Anita Garibaldi', '1913', 'Água Verde', 'Almirante Tamandaré', 'PR', '85946-902', '(41) 92196-5729', -25.29932169, -49.30833805, '/images/clínica_médica/159.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(160, 'Escritório de Advocacia Exemplo 160', 'Escritório de Advocacia', 'Avenida das Torres', '4122', 'Água Verde', 'Pinhais', 'PR', '82210-368', '(41) 92337-7244', -25.45132341, -49.18600061, '/images/escritório_de_advocacia/160.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(161, 'Construtora Exemplo 161', 'Construtora', 'Rua João Bettega', '4351', 'Centro', 'Araucária', 'PR', '88593-485', '(41) 96658-8768', -25.6044477, -49.38773218, '/images/construtora/161.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(162, 'Escola Exemplo 162', 'Escola', 'Avenida República Argentina', '843', 'Rebouças', 'Curitiba', 'PR', '86454-459', '(41) 93959-4400', -25.42883952, -49.29196364, '/images/escola/162.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(163, 'Escola Exemplo 163', 'Escola', 'Avenida Sete de Setembro', '4628', 'Batel', 'Campo Largo', 'PR', '85792-148', '(41) 99555-1372', -25.44352199, -49.53306839, '/images/escola/163.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(164, 'Clínica Médica Exemplo 164', 'Clínica Médica', 'Avenida República Argentina', '2552', 'Bigorrilho', 'Araucária', 'PR', '88793-971', '(41) 93654-7318', -25.5708541, -49.39144818, '/images/clínica_médica/164.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(165, 'Empresa de Tecnologia Exemplo 165', 'Empresa de Tecnologia', 'Rua Mateus Leme', '1603', 'Boqueirão', 'Araucária', 'PR', '88567-917', '(41) 95280-7218', -25.59242823, -49.41543389, '/images/empresa_de_tecnologia/165.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(166, 'Clínica Médica Exemplo 166', 'Clínica Médica', 'Rua Marechal Deodoro', '3451', 'Bigorrilho', 'Colombo', 'PR', '81522-583', '(41) 98859-9979', -25.27405447, -49.22024342, '/images/clínica_médica/166.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(167, 'Papelaria Exemplo 167', 'Papelaria', 'Avenida Sete de Setembro', '128', 'Rebouças', 'Pinhais', 'PR', '83384-414', '(41) 97302-8650', -25.4291274, -49.20395582, '/images/papelaria/167.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(168, 'Salão de Beleza Exemplo 168', 'Salão de Beleza', 'Avenida Sete de Setembro', '4527', 'Centro', 'Almirante Tamandaré', 'PR', '89083-282', '(41) 99815-2330', -25.32248721, -49.32448083, '/images/salão_de_beleza/168.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(169, 'Farmácia Exemplo 169', 'Farmácia', 'Rua Anita Garibaldi', '4379', 'Batel', 'Colombo', 'PR', '81612-252', '(41) 99139-3590', -25.28939221, -49.2331052, '/images/farmácia/169.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(170, 'Oficina Mecânica Exemplo 170', 'Oficina Mecânica', 'Rua Anita Garibaldi', '4768', 'Boqueirão', 'Campo Largo', 'PR', '88096-503', '(41) 99747-4813', -25.47736662, -49.53341993, '/images/oficina_mecânica/170.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(171, 'Padaria Exemplo 171', 'Padaria', 'Avenida Sete de Setembro', '3455', 'Centro', 'Pinhais', 'PR', '85165-268', '(41) 94746-5481', -25.45380672, -49.17879923, '/images/padaria/171.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(172, 'Construtora Exemplo 172', 'Construtora', 'Avenida República Argentina', '1578', 'Bigorrilho', 'Colombo', 'PR', '85309-977', '(41) 96538-3885', -25.29722357, -49.21842074, '/images/construtora/172.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(173, 'Restaurante Exemplo 173', 'Restaurante', 'Avenida Paraná', '4325', 'Cabral', 'São José dos Pinhais', 'PR', '87916-904', '(41) 96540-8973', -25.52666814, -49.18953328, '/images/restaurante/173.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(174, 'Farmácia Exemplo 174', 'Farmácia', 'Rua XV de Novembro', '604', 'Batel', 'Pinhais', 'PR', '87204-207', '(41) 98871-9434', -25.42533551, -49.19986307, '/images/farmácia/174.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(175, 'Loja de Roupas Exemplo 175', 'Loja de Roupas', 'Rua XV de Novembro', '4797', 'Cabral', 'Colombo', 'PR', '88006-243', '(41) 97253-5577', -25.27276296, -49.2219624, '/images/loja_de_roupas/175.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(176, 'Empresa de Tecnologia Exemplo 176', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '1814', 'Rebouças', 'Araucária', 'PR', '82809-875', '(41) 98534-6129', -25.58183724, -49.42098423, '/images/empresa_de_tecnologia/176.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(177, 'Papelaria Exemplo 177', 'Papelaria', 'Rua Mateus Leme', '1544', 'Santa Felicidade', 'Campo Largo', 'PR', '85819-762', '(41) 91400-6660', -25.46990141, -49.51498314, '/images/papelaria/177.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(178, 'Escola Exemplo 178', 'Escola', 'Rua Mateus Leme', '2568', 'Cabral', 'Pinhais', 'PR', '86749-727', '(41) 98112-4958', -25.44659567, -49.19071502, '/images/escola/178.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(179, 'Empresa de Tecnologia Exemplo 179', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '2996', 'Centro', 'Almirante Tamandaré', 'PR', '89951-548', '(41) 93635-5260', -25.32917776, -49.29527132, '/images/empresa_de_tecnologia/179.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(180, 'Clínica Médica Exemplo 180', 'Clínica Médica', 'Rua Marechal Deodoro', '4402', 'Cabral', 'Almirante Tamandaré', 'PR', '85532-854', '(41) 93325-2195', -25.31118019, -49.32215736, '/images/clínica_médica/180.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(181, 'Oficina Mecânica Exemplo 181', 'Oficina Mecânica', 'Rua Marechal Deodoro', '1223', 'Bigorrilho', 'Colombo', 'PR', '86049-759', '(41) 91601-2280', -25.27676217, -49.22942624, '/images/oficina_mecânica/181.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(182, 'Salão de Beleza Exemplo 182', 'Salão de Beleza', 'Rua João Bettega', '4890', 'Rebouças', 'Araucária', 'PR', '88117-762', '(41) 95947-3767', -25.59372109, -49.42330656, '/images/salão_de_beleza/182.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(183, 'Hotel Exemplo 183', 'Hotel', 'Rua XV de Novembro', '2556', 'Cabral', 'Almirante Tamandaré', 'PR', '88524-793', '(41) 99124-6201', -25.3299471, -49.30957046, '/images/hotel/183.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(184, 'Academia Exemplo 184', 'Academia', 'Rua Brigadeiro Franco', '1402', 'Portão', 'Almirante Tamandaré', 'PR', '84833-148', '(41) 93405-5123', -25.30469797, -49.29227286, '/images/academia/184.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(185, 'Oficina Mecânica Exemplo 185', 'Oficina Mecânica', 'Rua XV de Novembro', '1612', 'Centro', 'Campo Largo', 'PR', '82379-915', '(41) 94054-8768', -25.46926017, -49.51243161, '/images/oficina_mecânica/185.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(186, 'Oficina Mecânica Exemplo 186', 'Oficina Mecânica', 'Rua Mateus Leme', '2325', 'Água Verde', 'Campo Largo', 'PR', '81288-293', '(41) 95105-7956', -25.4420334, -49.53481303, '/images/oficina_mecânica/186.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(187, 'Oficina Mecânica Exemplo 187', 'Oficina Mecânica', 'Rua Anita Garibaldi', '605', 'Batel', 'Curitiba', 'PR', '84405-223', '(41) 97110-8464', -25.4339015, -49.27986393, '/images/oficina_mecânica/187.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(188, 'Escritório de Advocacia Exemplo 188', 'Escritório de Advocacia', 'Rua João Bettega', '2181', 'CIC', 'Almirante Tamandaré', 'PR', '81398-529', '(41) 94123-4565', -25.32279712, -49.33060299, '/images/escritório_de_advocacia/188.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(189, 'Academia Exemplo 189', 'Academia', 'Rua Mateus Leme', '3987', 'Rebouças', 'Curitiba', 'PR', '88760-284', '(41) 99022-9950', -25.42142404, -49.2684227, '/images/academia/189.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(190, 'Empresa de Tecnologia Exemplo 190', 'Empresa de Tecnologia', 'Avenida das Torres', '1001', 'Cabral', 'Pinhais', 'PR', '82059-358', '(41) 98424-5345', -25.42592651, -49.17705165, '/images/empresa_de_tecnologia/190.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(191, 'Papelaria Exemplo 191', 'Papelaria', 'Avenida Paraná', '681', 'Batel', 'Araucária', 'PR', '89253-656', '(41) 96997-4965', -25.57183613, -49.38875194, '/images/papelaria/191.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(192, 'Academia Exemplo 192', 'Academia', 'Rua XV de Novembro', '4189', 'Água Verde', 'Campo Largo', 'PR', '88720-194', '(41) 94793-6251', -25.44048803, -49.51131693, '/images/academia/192.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(193, 'Hotel Exemplo 193', 'Hotel', 'Avenida das Torres', '4399', 'Água Verde', 'Colombo', 'PR', '87531-934', '(41) 91392-9118', -25.29234504, -49.22080781, '/images/hotel/193.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(194, 'Clínica Médica Exemplo 194', 'Clínica Médica', 'Avenida Sete de Setembro', '3383', 'Bigorrilho', 'Campo Largo', 'PR', '84962-987', '(41) 93791-6783', -25.47634647, -49.54277472, '/images/clínica_médica/194.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(195, 'Papelaria Exemplo 195', 'Papelaria', 'Rua Brigadeiro Franco', '1598', 'Água Verde', 'Araucária', 'PR', '88007-340', '(41) 91706-7181', -25.58993259, -49.41379958, '/images/papelaria/195.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(196, 'Papelaria Exemplo 196', 'Papelaria', 'Avenida Paraná', '3641', 'Cabral', 'Almirante Tamandaré', 'PR', '81612-888', '(41) 96038-7968', -25.30802658, -49.30938064, '/images/papelaria/196.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(197, 'Academia Exemplo 197', 'Academia', 'Rua Anita Garibaldi', '3250', 'Boqueirão', 'Curitiba', 'PR', '81113-555', '(41) 94358-2939', -25.43683521, -49.2819415, '/images/academia/197.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(198, 'Salão de Beleza Exemplo 198', 'Salão de Beleza', 'Rua Marechal Deodoro', '1524', 'Santa Felicidade', 'Campo Largo', 'PR', '85404-576', '(41) 93930-3362', -25.45696001, -49.5267994, '/images/salão_de_beleza/198.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(199, 'Clínica Médica Exemplo 199', 'Clínica Médica', 'Rua João Bettega', '528', 'Portão', 'Almirante Tamandaré', 'PR', '86871-512', '(41) 99844-2549', -25.31936735, -49.31846432, '/images/clínica_médica/199.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(200, 'Academia Exemplo 200', 'Academia', 'Avenida República Argentina', '4105', 'Rebouças', 'São José dos Pinhais', 'PR', '83730-860', '(41) 93197-1360', -25.55056009, -49.18623515, '/images/academia/200.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(201, 'Salão de Beleza Exemplo 201', 'Salão de Beleza', 'Rua Mateus Leme', '4419', 'Santa Felicidade', 'São José dos Pinhais', 'PR', '85942-775', '(41) 91684-7046', -25.51429781, -49.19897598, '/images/salão_de_beleza/201.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(202, 'Construtora Exemplo 202', 'Construtora', 'Rua Marechal Deodoro', '3323', 'Santa Felicidade', 'Colombo', 'PR', '87230-976', '(41) 98024-7835', -25.27313507, -49.24165905, '/images/construtora/202.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(203, 'Clínica Médica Exemplo 203', 'Clínica Médica', 'Rua Anita Garibaldi', '3487', 'Batel', 'Curitiba', 'PR', '87123-777', '(41) 95734-5995', -25.42440799, -49.28812776, '/images/clínica_médica/203.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(204, 'Restaurante Exemplo 204', 'Restaurante', 'Avenida República Argentina', '3561', 'Rebouças', 'Almirante Tamandaré', 'PR', '84818-924', '(41) 93651-3262', -25.31064108, -49.29840237, '/images/restaurante/204.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(205, 'Padaria Exemplo 205', 'Padaria', 'Rua XV de Novembro', '4937', 'Cabral', 'Almirante Tamandaré', 'PR', '85223-647', '(41) 96405-8814', -25.32134791, -49.30913664, '/images/padaria/205.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(206, 'Escola Exemplo 206', 'Escola', 'Avenida Sete de Setembro', '2588', 'Portão', 'Pinhais', 'PR', '86653-412', '(41) 94852-1193', -25.45416518, -49.17836689, '/images/escola/206.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(207, 'Empresa de Tecnologia Exemplo 207', 'Empresa de Tecnologia', 'Avenida Paraná', '4844', 'Santa Felicidade', 'Curitiba', 'PR', '89275-413', '(41) 97028-7007', -25.43595607, -49.27253647, '/images/empresa_de_tecnologia/207.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(208, 'Restaurante Exemplo 208', 'Restaurante', 'Rua João Bettega', '141', 'CIC', 'Campo Largo', 'PR', '84112-781', '(41) 93453-3273', -25.47449561, -49.52342945, '/images/restaurante/208.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(209, 'Clínica Médica Exemplo 209', 'Clínica Médica', 'Rua João Bettega', '4584', 'Portão', 'Almirante Tamandaré', 'PR', '83286-783', '(41) 95124-2565', -25.32319881, -49.30461299, '/images/clínica_médica/209.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(210, 'Supermercado Exemplo 210', 'Supermercado', 'Rua Anita Garibaldi', '1807', 'CIC', 'Pinhais', 'PR', '87763-250', '(41) 95517-4022', -25.43385741, -49.1963658, '/images/supermercado/210.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(211, 'Loja de Roupas Exemplo 211', 'Loja de Roupas', 'Rua Mateus Leme', '3956', 'Bigorrilho', 'Curitiba', 'PR', '83575-576', '(41) 99596-3975', -25.43001004, -49.25497383, '/images/loja_de_roupas/211.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(212, 'Padaria Exemplo 212', 'Padaria', 'Rua Mateus Leme', '2283', 'Batel', 'Campo Largo', 'PR', '88792-990', '(41) 91421-1636', -25.47287617, -49.51453766, '/images/padaria/212.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(213, 'Clínica Médica Exemplo 213', 'Clínica Médica', 'Rua Mateus Leme', '4516', 'Santa Felicidade', 'Pinhais', 'PR', '86228-760', '(41) 99766-9415', -25.45379544, -49.20323767, '/images/clínica_médica/213.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(214, 'Papelaria Exemplo 214', 'Papelaria', 'Avenida República Argentina', '1855', 'Portão', 'Curitiba', 'PR', '81896-402', '(41) 91204-1868', -25.40857534, -49.27665201, '/images/papelaria/214.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(215, 'Salão de Beleza Exemplo 215', 'Salão de Beleza', 'Rua Anita Garibaldi', '922', 'Rebouças', 'São José dos Pinhais', 'PR', '86951-815', '(41) 95771-9288', -25.52374191, -49.19881926, '/images/salão_de_beleza/215.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(216, 'Restaurante Exemplo 216', 'Restaurante', 'Avenida das Torres', '1935', 'Portão', 'Curitiba', 'PR', '86131-808', '(41) 92511-1168', -25.4241803, -49.26133625, '/images/restaurante/216.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(217, 'Clínica Médica Exemplo 217', 'Clínica Médica', 'Rua Marechal Deodoro', '949', 'Rebouças', 'Pinhais', 'PR', '81755-209', '(41) 92489-8095', -25.45058822, -49.17477403, '/images/clínica_médica/217.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(218, 'Farmácia Exemplo 218', 'Farmácia', 'Rua XV de Novembro', '3810', 'Batel', 'Curitiba', 'PR', '84972-180', '(41) 94439-7059', -25.43303444, -49.26930646, '/images/farmácia/218.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(219, 'Supermercado Exemplo 219', 'Supermercado', 'Avenida Paraná', '4727', 'Boqueirão', 'Campo Largo', 'PR', '89220-709', '(41) 91962-4009', -25.46549775, -49.53395672, '/images/supermercado/219.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(220, 'Farmácia Exemplo 220', 'Farmácia', 'Rua XV de Novembro', '3226', 'Rebouças', 'Almirante Tamandaré', 'PR', '83071-306', '(41) 95898-1149', -25.32371537, -49.32275318, '/images/farmácia/220.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(221, 'Farmácia Exemplo 221', 'Farmácia', 'Rua Marechal Deodoro', '2428', 'Rebouças', 'Pinhais', 'PR', '81536-841', '(41) 97533-7711', -25.44834503, -49.17313268, '/images/farmácia/221.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(222, 'Escola Exemplo 222', 'Escola', 'Avenida Paraná', '3126', 'CIC', 'Colombo', 'PR', '81121-300', '(41) 96554-7569', -25.2973545, -49.2095718, '/images/escola/222.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(223, 'Supermercado Exemplo 223', 'Supermercado', 'Rua XV de Novembro', '987', 'Santa Felicidade', 'Araucária', 'PR', '82791-585', '(41) 97993-7026', -25.56725263, -49.40999299, '/images/supermercado/223.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(224, 'Escritório de Advocacia Exemplo 224', 'Escritório de Advocacia', 'Avenida Paraná', '4835', 'Batel', 'Almirante Tamandaré', 'PR', '87704-431', '(41) 95080-2853', -25.32659669, -49.30252084, '/images/escritório_de_advocacia/224.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(225, 'Papelaria Exemplo 225', 'Papelaria', 'Avenida Sete de Setembro', '1764', 'Batel', 'Araucária', 'PR', '87375-989', '(41) 92141-8883', -25.57229386, -49.40175032, '/images/papelaria/225.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(226, 'Papelaria Exemplo 226', 'Papelaria', 'Rua Marechal Deodoro', '4585', 'Água Verde', 'Almirante Tamandaré', 'PR', '86566-930', '(41) 96888-3872', -25.30954405, -49.29815877, '/images/papelaria/226.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(227, 'Supermercado Exemplo 227', 'Supermercado', 'Rua XV de Novembro', '4567', 'Água Verde', 'Almirante Tamandaré', 'PR', '84367-685', '(41) 99977-6313', -25.3132259, -49.3319886, '/images/supermercado/227.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(228, 'Papelaria Exemplo 228', 'Papelaria', 'Avenida República Argentina', '4498', 'Cabral', 'Campo Largo', 'PR', '85245-739', '(41) 96702-5819', -25.45701142, -49.54887266, '/images/papelaria/228.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(229, 'Hotel Exemplo 229', 'Hotel', 'Rua Brigadeiro Franco', '3409', 'Santa Felicidade', 'Campo Largo', 'PR', '84076-199', '(41) 91169-2334', -25.46755594, -49.53235483, '/images/hotel/229.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(230, 'Farmácia Exemplo 230', 'Farmácia', 'Rua Mateus Leme', '601', 'Centro', 'Curitiba', 'PR', '88794-119', '(41) 92292-5338', -25.41941739, -49.26095357, '/images/farmácia/230.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(231, 'Construtora Exemplo 231', 'Construtora', 'Rua João Bettega', '1656', 'Batel', 'Curitiba', 'PR', '82653-399', '(41) 93930-8371', -25.44009487, -49.27829655, '/images/construtora/231.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(232, 'Escola Exemplo 232', 'Escola', 'Avenida Paraná', '1293', 'Centro', 'Almirante Tamandaré', 'PR', '88306-369', '(41) 99987-2835', -25.31894029, -49.31512291, '/images/escola/232.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(233, 'Clínica Médica Exemplo 233', 'Clínica Médica', 'Avenida Sete de Setembro', '2998', 'Cabral', 'Colombo', 'PR', '81128-137', '(41) 98773-3497', -25.30909871, -49.23952753, '/images/clínica_médica/233.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(234, 'Empresa de Tecnologia Exemplo 234', 'Empresa de Tecnologia', 'Rua XV de Novembro', '1821', 'Centro', 'São José dos Pinhais', 'PR', '82315-632', '(41) 99729-3894', -25.54133069, -49.21631887, '/images/empresa_de_tecnologia/234.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(235, 'Clínica Médica Exemplo 235', 'Clínica Médica', 'Avenida Sete de Setembro', '949', 'Cabral', 'Araucária', 'PR', '84639-231', '(41) 95532-2354', -25.5897488, -49.38854214, '/images/clínica_médica/235.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(236, 'Escola Exemplo 236', 'Escola', 'Avenida Sete de Setembro', '2214', 'Centro', 'Curitiba', 'PR', '81226-531', '(41) 94644-6904', -25.445046, -49.26536283, '/images/escola/236.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(237, 'Escritório de Advocacia Exemplo 237', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '3048', 'CIC', 'São José dos Pinhais', 'PR', '82615-807', '(41) 97369-2496', -25.54599612, -49.22213894, '/images/escritório_de_advocacia/237.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(238, 'Supermercado Exemplo 238', 'Supermercado', 'Rua Marechal Deodoro', '2465', 'Rebouças', 'Almirante Tamandaré', 'PR', '83507-352', '(41) 95910-4986', -25.31683241, -49.30161885, '/images/supermercado/238.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(239, 'Loja de Roupas Exemplo 239', 'Loja de Roupas', 'Rua Mateus Leme', '3492', 'Bigorrilho', 'Curitiba', 'PR', '87373-846', '(41) 91918-3033', -25.41670023, -49.28252229, '/images/loja_de_roupas/239.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(240, 'Papelaria Exemplo 240', 'Papelaria', 'Rua João Bettega', '4088', 'Água Verde', 'Curitiba', 'PR', '86306-224', '(41) 95611-6325', -25.40948021, -49.28961435, '/images/papelaria/240.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(241, 'Clínica Médica Exemplo 241', 'Clínica Médica', 'Rua João Bettega', '3226', 'Batel', 'Pinhais', 'PR', '83477-254', '(41) 92575-7483', -25.4533108, -49.18227392, '/images/clínica_médica/241.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(242, 'Empresa de Tecnologia Exemplo 242', 'Empresa de Tecnologia', 'Avenida Sete de Setembro', '2132', 'Bigorrilho', 'Pinhais', 'PR', '82512-553', '(41) 92634-8769', -25.43685576, -49.17675404, '/images/empresa_de_tecnologia/242.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(243, 'Escola Exemplo 243', 'Escola', 'Rua Brigadeiro Franco', '3223', 'Boqueirão', 'Curitiba', 'PR', '88645-861', '(41) 98459-3779', -25.43327285, -49.27987158, '/images/escola/243.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(244, 'Loja de Roupas Exemplo 244', 'Loja de Roupas', 'Avenida Sete de Setembro', '2252', 'CIC', 'Colombo', 'PR', '89479-619', '(41) 98434-2892', -25.3102721, -49.22212225, '/images/loja_de_roupas/244.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(245, 'Hotel Exemplo 245', 'Hotel', 'Avenida República Argentina', '1460', 'Boqueirão', 'Curitiba', 'PR', '87778-725', '(41) 97213-1305', -25.4438782, -49.28120193, '/images/hotel/245.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(246, 'Empresa de Tecnologia Exemplo 246', 'Empresa de Tecnologia', 'Rua João Bettega', '2969', 'Cabral', 'Colombo', 'PR', '85167-366', '(41) 95852-9271', -25.30322178, -49.24122855, '/images/empresa_de_tecnologia/246.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(247, 'Restaurante Exemplo 247', 'Restaurante', 'Avenida das Torres', '3644', 'Batel', 'Almirante Tamandaré', 'PR', '86486-994', '(41) 92646-2074', -25.32469042, -49.29562936, '/images/restaurante/247.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(248, 'Oficina Mecânica Exemplo 248', 'Oficina Mecânica', 'Rua XV de Novembro', '2881', 'Santa Felicidade', 'Curitiba', 'PR', '86574-715', '(41) 92772-4784', -25.42857898, -49.2578801, '/images/oficina_mecânica/248.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(249, 'Papelaria Exemplo 249', 'Papelaria', 'Rua Marechal Deodoro', '4827', 'Rebouças', 'Curitiba', 'PR', '87014-984', '(41) 99994-8149', -25.4237076, -49.2549547, '/images/papelaria/249.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(250, 'Escritório de Advocacia Exemplo 250', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '2676', 'Rebouças', 'Campo Largo', 'PR', '87154-634', '(41) 94849-5894', -25.45731744, -49.52012786, '/images/escritório_de_advocacia/250.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(251, 'Clínica Médica Exemplo 251', 'Clínica Médica', 'Rua Mateus Leme', '2410', 'CIC', 'Curitiba', 'PR', '82894-133', '(41) 95330-4189', -25.41346182, -49.29196081, '/images/clínica_médica/251.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(252, 'Empresa de Tecnologia Exemplo 252', 'Empresa de Tecnologia', 'Rua Marechal Deodoro', '2322', 'CIC', 'Colombo', 'PR', '82119-432', '(41) 95332-5203', -25.2891711, -49.23385614, '/images/empresa_de_tecnologia/252.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(253, 'Loja de Roupas Exemplo 253', 'Loja de Roupas', 'Avenida Paraná', '3131', 'Centro', 'São José dos Pinhais', 'PR', '87830-525', '(41) 99475-7616', -25.52190652, -49.19553823, '/images/loja_de_roupas/253.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(254, 'Construtora Exemplo 254', 'Construtora', 'Avenida das Torres', '4817', 'Bigorrilho', 'Araucária', 'PR', '84933-517', '(41) 91613-6516', -25.59620608, -49.38642037, '/images/construtora/254.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(255, 'Oficina Mecânica Exemplo 255', 'Oficina Mecânica', 'Avenida das Torres', '1235', 'Cabral', 'Campo Largo', 'PR', '89064-525', '(41) 96622-3020', -25.46646508, -49.51483272, '/images/oficina_mecânica/255.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(256, 'Escritório de Advocacia Exemplo 256', 'Escritório de Advocacia', 'Avenida Sete de Setembro', '1645', 'Água Verde', 'Campo Largo', 'PR', '84292-489', '(41) 97320-6638', -25.45970031, -49.51056049, '/images/escritório_de_advocacia/256.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(257, 'Salão de Beleza Exemplo 257', 'Salão de Beleza', 'Avenida Paraná', '419', 'Santa Felicidade', 'Pinhais', 'PR', '84273-511', '(41) 98414-5183', -25.46050362, -49.17439464, '/images/salão_de_beleza/257.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(258, 'Restaurante Exemplo 258', 'Restaurante', 'Rua João Bettega', '1705', 'CIC', 'Curitiba', 'PR', '89641-243', '(41) 98623-6029', -25.41605255, -49.26398869, '/images/restaurante/258.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(259, 'Padaria Exemplo 259', 'Padaria', 'Avenida República Argentina', '2044', 'Rebouças', 'Campo Largo', 'PR', '89321-138', '(41) 95392-6691', -25.46634489, -49.50987617, '/images/padaria/259.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(260, 'Supermercado Exemplo 260', 'Supermercado', 'Rua Mateus Leme', '1287', 'Rebouças', 'Colombo', 'PR', '87175-480', '(41) 92552-2838', -25.30212379, -49.23654014, '/images/supermercado/260.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(261, 'Clínica Médica Exemplo 261', 'Clínica Médica', 'Rua Brigadeiro Franco', '4262', 'Batel', 'Curitiba', 'PR', '84829-906', '(41) 99873-1180', -25.42887829, -49.25711876, '/images/clínica_médica/261.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(262, 'Academia Exemplo 262', 'Academia', 'Rua João Bettega', '3876', 'Cabral', 'Almirante Tamandaré', 'PR', '87602-235', '(41) 91174-6375', -25.32593212, -49.3178716, '/images/academia/262.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(263, 'Loja de Roupas Exemplo 263', 'Loja de Roupas', 'Avenida Sete de Setembro', '1345', 'Rebouças', 'Pinhais', 'PR', '89366-857', '(41) 94023-8166', -25.43273077, -49.19276083, '/images/loja_de_roupas/263.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(264, 'Clínica Médica Exemplo 264', 'Clínica Médica', 'Rua Brigadeiro Franco', '3688', 'Batel', 'Curitiba', 'PR', '81351-710', '(41) 94606-9217', -25.42165521, -49.28610748, '/images/clínica_médica/264.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(265, 'Padaria Exemplo 265', 'Padaria', 'Avenida das Torres', '1249', 'CIC', 'Almirante Tamandaré', 'PR', '85046-857', '(41) 96993-6160', -25.30435645, -49.33194829, '/images/padaria/265.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(266, 'Papelaria Exemplo 266', 'Papelaria', 'Rua Mateus Leme', '650', 'Água Verde', 'Campo Largo', 'PR', '82581-990', '(41) 98759-6776', -25.46965254, -49.5200379, '/images/papelaria/266.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(267, 'Salão de Beleza Exemplo 267', 'Salão de Beleza', 'Rua João Bettega', '2694', 'Santa Felicidade', 'Pinhais', 'PR', '82073-796', '(41) 93249-5534', -25.44228598, -49.19762232, '/images/salão_de_beleza/267.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(268, 'Construtora Exemplo 268', 'Construtora', 'Rua XV de Novembro', '4949', 'Rebouças', 'Campo Largo', 'PR', '87639-395', '(41) 95007-7349', -25.45189328, -49.5357643, '/images/construtora/268.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(269, 'Padaria Exemplo 269', 'Padaria', 'Avenida Sete de Setembro', '1486', 'Portão', 'Curitiba', 'PR', '83036-306', '(41) 96258-9440', -25.40935821, -49.26297345, '/images/padaria/269.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(270, 'Loja de Roupas Exemplo 270', 'Loja de Roupas', 'Rua Brigadeiro Franco', '2104', 'Bigorrilho', 'Curitiba', 'PR', '88030-251', '(41) 91998-6392', -25.43588392, -49.27724855, '/images/loja_de_roupas/270.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(271, 'Escola Exemplo 271', 'Escola', 'Rua Anita Garibaldi', '3875', 'Bigorrilho', 'Pinhais', 'PR', '87035-619', '(41) 96832-8502', -25.43294856, -49.17468702, '/images/escola/271.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(272, 'Academia Exemplo 272', 'Academia', 'Rua XV de Novembro', '3678', 'Água Verde', 'São José dos Pinhais', 'PR', '83032-569', '(41) 98006-1451', -25.51623291, -49.18803036, '/images/academia/272.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(273, 'Papelaria Exemplo 273', 'Papelaria', 'Avenida das Torres', '2193', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '81448-289', '(41) 94271-5618', -25.32636682, -49.33185766, '/images/papelaria/273.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(274, 'Empresa de Tecnologia Exemplo 274', 'Empresa de Tecnologia', 'Avenida Sete de Setembro', '4291', 'Água Verde', 'Curitiba', 'PR', '83150-717', '(41) 98615-1647', -25.43065068, -49.28942971, '/images/empresa_de_tecnologia/274.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(275, 'Academia Exemplo 275', 'Academia', 'Rua João Bettega', '487', 'Cabral', 'Araucária', 'PR', '83901-111', '(41) 93119-1338', -25.59320286, -49.38694556, '/images/academia/275.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(276, 'Loja de Roupas Exemplo 276', 'Loja de Roupas', 'Avenida das Torres', '4429', 'Rebouças', 'São José dos Pinhais', 'PR', '87480-751', '(41) 93296-5887', -25.53552899, -49.19239968, '/images/loja_de_roupas/276.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(277, 'Padaria Exemplo 277', 'Padaria', 'Rua Brigadeiro Franco', '3751', 'Água Verde', 'Campo Largo', 'PR', '87141-598', '(41) 92343-9552', -25.4760241, -49.52175425, '/images/padaria/277.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(278, 'Hotel Exemplo 278', 'Hotel', 'Rua Marechal Deodoro', '724', 'Boqueirão', 'São José dos Pinhais', 'PR', '81007-278', '(41) 96856-4950', -25.52661107, -49.22126071, '/images/hotel/278.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(279, 'Padaria Exemplo 279', 'Padaria', 'Rua Marechal Deodoro', '3968', 'Portão', 'São José dos Pinhais', 'PR', '83858-379', '(41) 94882-5038', -25.52401835, -49.20974913, '/images/padaria/279.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(280, 'Escritório de Advocacia Exemplo 280', 'Escritório de Advocacia', 'Avenida República Argentina', '892', 'Portão', 'São José dos Pinhais', 'PR', '89780-560', '(41) 97561-2858', -25.5399694, -49.20677777, '/images/escritório_de_advocacia/280.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(281, 'Loja de Roupas Exemplo 281', 'Loja de Roupas', 'Avenida das Torres', '2435', 'Batel', 'Curitiba', 'PR', '88125-370', '(41) 97463-9154', -25.42688487, -49.28986271, '/images/loja_de_roupas/281.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(282, 'Escola Exemplo 282', 'Escola', 'Avenida Sete de Setembro', '362', 'Água Verde', 'Araucária', 'PR', '87087-545', '(41) 92967-3786', -25.56758319, -49.40226983, '/images/escola/282.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(283, 'Papelaria Exemplo 283', 'Papelaria', 'Rua Brigadeiro Franco', '545', 'Água Verde', 'Campo Largo', 'PR', '84212-224', '(41) 95870-9615', -25.45357089, -49.54203152, '/images/papelaria/283.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(284, 'Supermercado Exemplo 284', 'Supermercado', 'Rua Anita Garibaldi', '982', 'Portão', 'Pinhais', 'PR', '84987-635', '(41) 99981-2100', -25.45856449, -49.20669865, '/images/supermercado/284.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(285, 'Supermercado Exemplo 285', 'Supermercado', 'Rua Anita Garibaldi', '2618', 'Santa Felicidade', 'Colombo', 'PR', '87322-306', '(41) 94288-6155', -25.28496699, -49.22318357, '/images/supermercado/285.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(286, 'Padaria Exemplo 286', 'Padaria', 'Avenida Sete de Setembro', '4188', 'Água Verde', 'Pinhais', 'PR', '85453-426', '(41) 91380-4792', -25.44668672, -49.21111599, '/images/padaria/286.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(287, 'Clínica Médica Exemplo 287', 'Clínica Médica', 'Rua Marechal Deodoro', '626', 'Rebouças', 'Almirante Tamandaré', 'PR', '88912-175', '(41) 99652-6330', -25.31299526, -49.29774164, '/images/clínica_médica/287.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(288, 'Padaria Exemplo 288', 'Padaria', 'Avenida Sete de Setembro', '1663', 'CIC', 'Colombo', 'PR', '85193-416', '(41) 93492-2377', -25.3065957, -49.22282071, '/images/padaria/288.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(289, 'Padaria Exemplo 289', 'Padaria', 'Rua Mateus Leme', '4647', 'Bigorrilho', 'Pinhais', 'PR', '84541-101', '(41) 91446-9423', -25.46350443, -49.1913244, '/images/padaria/289.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(290, 'Farmácia Exemplo 290', 'Farmácia', 'Avenida Sete de Setembro', '3053', 'Centro', 'Araucária', 'PR', '82317-300', '(41) 97721-6642', -25.59167977, -49.39068943, '/images/farmácia/290.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(291, 'Salão de Beleza Exemplo 291', 'Salão de Beleza', 'Rua Brigadeiro Franco', '3042', 'Portão', 'Almirante Tamandaré', 'PR', '83488-577', '(41) 93918-5665', -25.30175895, -49.30627623, '/images/salão_de_beleza/291.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(292, 'Clínica Médica Exemplo 292', 'Clínica Médica', 'Rua Anita Garibaldi', '3170', 'Rebouças', 'Araucária', 'PR', '81408-244', '(41) 99296-3885', -25.57598996, -49.39137836, '/images/clínica_médica/292.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(293, 'Salão de Beleza Exemplo 293', 'Salão de Beleza', 'Avenida Paraná', '4544', 'Santa Felicidade', 'Campo Largo', 'PR', '89876-961', '(41) 92694-1568', -25.4479348, -49.51907823, '/images/salão_de_beleza/293.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(294, 'Escola Exemplo 294', 'Escola', 'Avenida Sete de Setembro', '2639', 'Boqueirão', 'Araucária', 'PR', '87727-923', '(41) 91824-7038', -25.60236133, -49.4127086, '/images/escola/294.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(295, 'Hotel Exemplo 295', 'Hotel', 'Avenida das Torres', '3065', 'CIC', 'Curitiba', 'PR', '82252-412', '(41) 97607-3201', -25.41724344, -49.25688016, '/images/hotel/295.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(296, 'Escritório de Advocacia Exemplo 296', 'Escritório de Advocacia', 'Rua Marechal Deodoro', '120', 'Cabral', 'São José dos Pinhais', 'PR', '87961-318', '(41) 98560-8821', -25.52268069, -49.19863576, '/images/escritório_de_advocacia/296.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(297, 'Academia Exemplo 297', 'Academia', 'Avenida Paraná', '2876', 'Cabral', 'Almirante Tamandaré', 'PR', '87218-635', '(41) 98171-3965', -25.32348255, -49.32762098, '/images/academia/297.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(298, 'Papelaria Exemplo 298', 'Papelaria', 'Avenida República Argentina', '3825', 'Boqueirão', 'São José dos Pinhais', 'PR', '81634-674', '(41) 98176-8451', -25.51942389, -49.19769511, '/images/papelaria/298.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(299, 'Construtora Exemplo 299', 'Construtora', 'Rua Brigadeiro Franco', '3176', 'Portão', 'Campo Largo', 'PR', '81405-929', '(41) 98622-2050', -25.46938549, -49.5271216, '/images/construtora/299.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(300, 'Oficina Mecânica Exemplo 300', 'Oficina Mecânica', 'Avenida Paraná', '2568', 'Batel', 'Araucária', 'PR', '84770-977', '(41) 97815-5825', -25.58536658, -49.42049103, '/images/oficina_mecânica/300.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(301, 'Loja de Roupas Exemplo 301', 'Loja de Roupas', 'Rua Mateus Leme', '1331', 'Centro', 'Colombo', 'PR', '88979-209', '(41) 96832-4251', -25.3100033, -49.22062552, '/images/loja_de_roupas/301.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(302, 'Escola Exemplo 302', 'Escola', 'Rua Mateus Leme', '1494', 'Santa Felicidade', 'Curitiba', 'PR', '84925-830', '(41) 93010-7406', -25.43583896, -49.26731075, '/images/escola/302.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(303, 'Clínica Médica Exemplo 303', 'Clínica Médica', 'Rua XV de Novembro', '772', 'Água Verde', 'Araucária', 'PR', '81114-958', '(41) 94613-7288', -25.57407669, -49.4201742, '/images/clínica_médica/303.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(304, 'Empresa de Tecnologia Exemplo 304', 'Empresa de Tecnologia', 'Rua Mateus Leme', '1665', 'Água Verde', 'Curitiba', 'PR', '84570-171', '(41) 95400-3558', -25.44050631, -49.26536966, '/images/empresa_de_tecnologia/304.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(305, 'Empresa de Tecnologia Exemplo 305', 'Empresa de Tecnologia', 'Rua Mateus Leme', '3292', 'Batel', 'Campo Largo', 'PR', '88115-796', '(41) 93155-4578', -25.46026207, -49.52394456, '/images/empresa_de_tecnologia/305.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(306, 'Restaurante Exemplo 306', 'Restaurante', 'Rua Mateus Leme', '1220', 'Boqueirão', 'Curitiba', 'PR', '88183-824', '(41) 91001-2381', -25.43116984, -49.28986731, '/images/restaurante/306.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(307, 'Farmácia Exemplo 307', 'Farmácia', 'Rua João Bettega', '4339', 'Cabral', 'Curitiba', 'PR', '81466-172', '(41) 93398-9642', -25.44194207, -49.27395841, '/images/farmácia/307.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(308, 'Hotel Exemplo 308', 'Hotel', 'Rua João Bettega', '1514', 'Cabral', 'Almirante Tamandaré', 'PR', '83643-800', '(41) 99676-1675', -25.29758826, -49.30260441, '/images/hotel/308.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(309, 'Escritório de Advocacia Exemplo 309', 'Escritório de Advocacia', 'Avenida Sete de Setembro', '3345', 'Boqueirão', 'Pinhais', 'PR', '85934-446', '(41) 96952-5882', -25.45916683, -49.1815628, '/images/escritório_de_advocacia/309.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(310, 'Papelaria Exemplo 310', 'Papelaria', 'Rua Anita Garibaldi', '3141', 'Centro', 'São José dos Pinhais', 'PR', '86179-557', '(41) 91169-2569', -25.5360412, -49.21109513, '/images/papelaria/310.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(311, 'Padaria Exemplo 311', 'Padaria', 'Rua Marechal Deodoro', '4150', 'Batel', 'Campo Largo', 'PR', '81885-795', '(41) 97173-6660', -25.46997477, -49.52920063, '/images/padaria/311.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(312, 'Escola Exemplo 312', 'Escola', 'Avenida República Argentina', '3453', 'CIC', 'São José dos Pinhais', 'PR', '89251-476', '(41) 98447-2222', -25.53284884, -49.20571544, '/images/escola/312.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(313, 'Padaria Exemplo 313', 'Padaria', 'Rua Brigadeiro Franco', '350', 'Bigorrilho', 'Campo Largo', 'PR', '81031-195', '(41) 99351-4884', -25.46992679, -49.53753782, '/images/padaria/313.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(314, 'Construtora Exemplo 314', 'Construtora', 'Rua Marechal Deodoro', '3017', 'Centro', 'São José dos Pinhais', 'PR', '85645-693', '(41) 97661-7776', -25.52382233, -49.18712504, '/images/construtora/314.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(315, 'Empresa de Tecnologia Exemplo 315', 'Empresa de Tecnologia', 'Rua XV de Novembro', '3296', 'Centro', 'Campo Largo', 'PR', '82111-775', '(41) 97186-4038', -25.45075793, -49.54234989, '/images/empresa_de_tecnologia/315.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(316, 'Hotel Exemplo 316', 'Hotel', 'Rua João Bettega', '2006', 'Centro', 'Curitiba', 'PR', '84214-570', '(41) 94219-9947', -25.41630218, -49.25746778, '/images/hotel/316.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(317, 'Oficina Mecânica Exemplo 317', 'Oficina Mecânica', 'Rua Anita Garibaldi', '3799', 'Água Verde', 'Araucária', 'PR', '84392-318', '(41) 97447-8419', -25.60168453, -49.39807772, '/images/oficina_mecânica/317.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(318, 'Loja de Roupas Exemplo 318', 'Loja de Roupas', 'Avenida Paraná', '826', 'Água Verde', 'Campo Largo', 'PR', '89186-413', '(41) 96037-1600', -25.44583032, -49.54538362, '/images/loja_de_roupas/318.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(319, 'Construtora Exemplo 319', 'Construtora', 'Avenida Sete de Setembro', '1687', 'Portão', 'Almirante Tamandaré', 'PR', '84361-945', '(41) 95752-9472', -25.32300383, -49.30753601, '/images/construtora/319.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(320, 'Escritório de Advocacia Exemplo 320', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '108', 'Cabral', 'Araucária', 'PR', '87198-289', '(41) 97143-4396', -25.56879836, -49.41225011, '/images/escritório_de_advocacia/320.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(321, 'Oficina Mecânica Exemplo 321', 'Oficina Mecânica', 'Avenida República Argentina', '4754', 'Rebouças', 'Pinhais', 'PR', '88994-615', '(41) 97128-5141', -25.42701625, -49.20637011, '/images/oficina_mecânica/321.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(322, 'Loja de Roupas Exemplo 322', 'Loja de Roupas', 'Rua Marechal Deodoro', '1924', 'Centro', 'Colombo', 'PR', '89254-547', '(41) 99553-1448', -25.27385471, -49.2404093, '/images/loja_de_roupas/322.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(323, 'Salão de Beleza Exemplo 323', 'Salão de Beleza', 'Rua Marechal Deodoro', '290', 'Boqueirão', 'Pinhais', 'PR', '86092-739', '(41) 95129-1425', -25.45587361, -49.19414328, '/images/salão_de_beleza/323.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(324, 'Papelaria Exemplo 324', 'Papelaria', 'Rua Anita Garibaldi', '4605', 'Centro', 'Almirante Tamandaré', 'PR', '84361-210', '(41) 99220-8939', -25.30897935, -49.30133217, '/images/papelaria/324.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(325, 'Padaria Exemplo 325', 'Padaria', 'Rua Mateus Leme', '2067', 'Centro', 'Almirante Tamandaré', 'PR', '87676-682', '(41) 99649-3544', -25.31339407, -49.31226523, '/images/padaria/325.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(326, 'Restaurante Exemplo 326', 'Restaurante', 'Rua João Bettega', '3096', 'Rebouças', 'Curitiba', 'PR', '82238-190', '(41) 99536-3251', -25.43467715, -49.28837562, '/images/restaurante/326.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(327, 'Clínica Médica Exemplo 327', 'Clínica Médica', 'Avenida Paraná', '4070', 'Rebouças', 'Pinhais', 'PR', '83446-281', '(41) 91502-8088', -25.43479795, -49.18213372, '/images/clínica_médica/327.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(328, 'Oficina Mecânica Exemplo 328', 'Oficina Mecânica', 'Rua Mateus Leme', '2404', 'Santa Felicidade', 'Almirante Tamandaré', 'PR', '89182-567', '(41) 95397-6049', -25.32733145, -49.30653788, '/images/oficina_mecânica/328.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(329, 'Escritório de Advocacia Exemplo 329', 'Escritório de Advocacia', 'Avenida das Torres', '3675', 'Batel', 'Campo Largo', 'PR', '82922-732', '(41) 94966-2738', -25.46874527, -49.53247827, '/images/escritório_de_advocacia/329.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(330, 'Papelaria Exemplo 330', 'Papelaria', 'Rua XV de Novembro', '3633', 'CIC', 'Colombo', 'PR', '86939-287', '(41) 91779-4374', -25.28995014, -49.2420629, '/images/papelaria/330.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(331, 'Clínica Médica Exemplo 331', 'Clínica Médica', 'Avenida República Argentina', '3599', 'Batel', 'Almirante Tamandaré', 'PR', '88913-138', '(41) 95555-8509', -25.32966586, -49.31488184, '/images/clínica_médica/331.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(332, 'Academia Exemplo 332', 'Academia', 'Avenida Sete de Setembro', '744', 'Portão', 'São José dos Pinhais', 'PR', '89290-915', '(41) 98778-3094', -25.52631289, -49.20515963, '/images/academia/332.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(333, 'Empresa de Tecnologia Exemplo 333', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '1354', 'Santa Felicidade', 'Curitiba', 'PR', '85220-341', '(41) 94069-7453', -25.42887961, -49.27868883, '/images/empresa_de_tecnologia/333.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(334, 'Papelaria Exemplo 334', 'Papelaria', 'Avenida das Torres', '4538', 'Água Verde', 'Pinhais', 'PR', '83253-495', '(41) 93820-4872', -25.43766475, -49.19156633, '/images/papelaria/334.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(335, 'Clínica Médica Exemplo 335', 'Clínica Médica', 'Rua Brigadeiro Franco', '3023', 'Rebouças', 'Araucária', 'PR', '84744-842', '(41) 97594-5699', -25.58416994, -49.41912734, '/images/clínica_médica/335.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(336, 'Salão de Beleza Exemplo 336', 'Salão de Beleza', 'Rua João Bettega', '119', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '82620-713', '(41) 95299-2807', -25.31052254, -49.31229721, '/images/salão_de_beleza/336.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(337, 'Salão de Beleza Exemplo 337', 'Salão de Beleza', 'Rua João Bettega', '1907', 'Santa Felicidade', 'Araucária', 'PR', '83349-546', '(41) 92703-5306', -25.59027296, -49.40907898, '/images/salão_de_beleza/337.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(338, 'Farmácia Exemplo 338', 'Farmácia', 'Rua Marechal Deodoro', '2641', 'Centro', 'Almirante Tamandaré', 'PR', '83906-445', '(41) 91908-8401', -25.32587981, -49.29874224, '/images/farmácia/338.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(339, 'Supermercado Exemplo 339', 'Supermercado', 'Rua Mateus Leme', '992', 'Portão', 'Campo Largo', 'PR', '81852-999', '(41) 98872-6360', -25.46199479, -49.51386563, '/images/supermercado/339.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(340, 'Escritório de Advocacia Exemplo 340', 'Escritório de Advocacia', 'Rua Anita Garibaldi', '3789', 'Batel', 'Colombo', 'PR', '81570-739', '(41) 96503-7777', -25.31140497, -49.24177274, '/images/escritório_de_advocacia/340.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(341, 'Empresa de Tecnologia Exemplo 341', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '1590', 'Batel', 'São José dos Pinhais', 'PR', '84545-309', '(41) 96976-9487', -25.53278235, -49.21087257, '/images/empresa_de_tecnologia/341.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(342, 'Construtora Exemplo 342', 'Construtora', 'Rua Marechal Deodoro', '716', 'Rebouças', 'Pinhais', 'PR', '89628-137', '(41) 97515-7818', -25.45983974, -49.1945315, '/images/construtora/342.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(343, 'Escritório de Advocacia Exemplo 343', 'Escritório de Advocacia', 'Rua Anita Garibaldi', '911', 'Centro', 'Araucária', 'PR', '87495-250', '(41) 99615-5710', -25.56684842, -49.40930659, '/images/escritório_de_advocacia/343.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(344, 'Escola Exemplo 344', 'Escola', 'Rua Mateus Leme', '2602', 'Batel', 'Campo Largo', 'PR', '86842-290', '(41) 93950-2374', -25.46177512, -49.54069255, '/images/escola/344.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(345, 'Escritório de Advocacia Exemplo 345', 'Escritório de Advocacia', 'Rua Brigadeiro Franco', '1366', 'Boqueirão', 'Almirante Tamandaré', 'PR', '86571-161', '(41) 97394-7299', -25.30985012, -49.30473266, '/images/escritório_de_advocacia/345.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(346, 'Salão de Beleza Exemplo 346', 'Salão de Beleza', 'Rua João Bettega', '3006', 'Portão', 'São José dos Pinhais', 'PR', '87687-297', '(41) 94923-5123', -25.51512207, -49.21076632, '/images/salão_de_beleza/346.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(347, 'Padaria Exemplo 347', 'Padaria', 'Rua Anita Garibaldi', '3899', 'Batel', 'São José dos Pinhais', 'PR', '87303-388', '(41) 99901-9776', -25.54964201, -49.22065676, '/images/padaria/347.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(348, 'Restaurante Exemplo 348', 'Restaurante', 'Avenida Paraná', '2986', 'Bigorrilho', 'Campo Largo', 'PR', '81642-401', '(41) 91008-7144', -25.47264263, -49.52060039, '/images/restaurante/348.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(349, 'Papelaria Exemplo 349', 'Papelaria', 'Rua XV de Novembro', '2218', 'CIC', 'Almirante Tamandaré', 'PR', '84092-809', '(41) 96831-1333', -25.30369657, -49.29280028, '/images/papelaria/349.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(350, 'Papelaria Exemplo 350', 'Papelaria', 'Avenida Paraná', '4870', 'Bigorrilho', 'Campo Largo', 'PR', '86836-885', '(41) 92249-8240', -25.46985295, -49.52771519, '/images/papelaria/350.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(351, 'Loja de Roupas Exemplo 351', 'Loja de Roupas', 'Avenida das Torres', '4531', 'Batel', 'Curitiba', 'PR', '87725-261', '(41) 92251-1057', -25.4301776, -49.25424368, '/images/loja_de_roupas/351.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(352, 'Supermercado Exemplo 352', 'Supermercado', 'Rua Marechal Deodoro', '3026', 'Água Verde', 'Colombo', 'PR', '83965-619', '(41) 94968-1872', -25.27444406, -49.20998966, '/images/supermercado/352.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(353, 'Restaurante Exemplo 353', 'Restaurante', 'Avenida Paraná', '2173', 'Cabral', 'Pinhais', 'PR', '89463-991', '(41) 98301-7628', -25.42960091, -49.17384344, '/images/restaurante/353.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(354, 'Hotel Exemplo 354', 'Hotel', 'Rua João Bettega', '3539', 'Água Verde', 'Almirante Tamandaré', 'PR', '83005-802', '(41) 91965-9713', -25.32300041, -49.32387862, '/images/hotel/354.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(355, 'Hotel Exemplo 355', 'Hotel', 'Rua XV de Novembro', '2103', 'Batel', 'Almirante Tamandaré', 'PR', '88747-533', '(41) 93352-4538', -25.29133132, -49.32738485, '/images/hotel/355.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(356, 'Empresa de Tecnologia Exemplo 356', 'Empresa de Tecnologia', 'Rua Brigadeiro Franco', '1266', 'Batel', 'Almirante Tamandaré', 'PR', '88766-457', '(41) 94370-4317', -25.32415282, -49.29825932, '/images/empresa_de_tecnologia/356.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(357, 'Salão de Beleza Exemplo 357', 'Salão de Beleza', 'Rua Marechal Deodoro', '598', 'Água Verde', 'Colombo', 'PR', '87382-186', '(41) 93206-1154', -25.3090379, -49.23875247, '/images/salão_de_beleza/357.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(358, 'Supermercado Exemplo 358', 'Supermercado', 'Rua Anita Garibaldi', '1782', 'Centro', 'Araucária', 'PR', '86655-575', '(41) 91450-9384', -25.5877128, -49.4130005, '/images/supermercado/358.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(359, 'Oficina Mecânica Exemplo 359', 'Oficina Mecânica', 'Rua Mateus Leme', '4768', 'Água Verde', 'Pinhais', 'PR', '85717-768', '(41) 91616-5094', -25.43702497, -49.18612782, '/images/oficina_mecânica/359.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(360, 'Clínica Médica Exemplo 360', 'Clínica Médica', 'Avenida Paraná', '1831', 'Cabral', 'Almirante Tamandaré', 'PR', '83546-547', '(41) 94923-3328', -25.31538973, -49.32679566, '/images/clínica_médica/360.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(361, 'Escola Exemplo 361', 'Escola', 'Rua XV de Novembro', '656', 'Santa Felicidade', 'Almirante Tamandaré', 'PR', '85974-348', '(41) 97707-9357', -25.29528658, -49.30480147, '/images/escola/361.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(362, 'Loja de Roupas Exemplo 362', 'Loja de Roupas', 'Avenida Sete de Setembro', '4632', 'Santa Felicidade', 'Almirante Tamandaré', 'PR', '88035-221', '(41) 95546-4284', -25.31027455, -49.32667699, '/images/loja_de_roupas/362.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(363, 'Construtora Exemplo 363', 'Construtora', 'Rua XV de Novembro', '2251', 'Rebouças', 'Curitiba', 'PR', '82314-930', '(41) 95891-2898', -25.43764447, -49.27377706, '/images/construtora/363.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(364, 'Academia Exemplo 364', 'Academia', 'Rua Mateus Leme', '2210', 'Centro', 'Pinhais', 'PR', '88950-156', '(41) 99335-2984', -25.46255976, -49.191041, '/images/academia/364.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(365, 'Salão de Beleza Exemplo 365', 'Salão de Beleza', 'Rua Anita Garibaldi', '501', 'Cabral', 'Pinhais', 'PR', '86738-393', '(41) 92518-9774', -25.45410553, -49.19663022, '/images/salão_de_beleza/365.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(366, 'Hotel Exemplo 366', 'Hotel', 'Avenida das Torres', '3151', 'Água Verde', 'Colombo', 'PR', '82929-392', '(41) 95669-7271', -25.28331398, -49.22405893, '/images/hotel/366.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(367, 'Restaurante Exemplo 367', 'Restaurante', 'Rua Anita Garibaldi', '4651', 'Boqueirão', 'Pinhais', 'PR', '82105-207', '(41) 91270-4243', -25.42859407, -49.19839694, '/images/restaurante/367.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(368, 'Academia Exemplo 368', 'Academia', 'Avenida República Argentina', '3547', 'Rebouças', 'Curitiba', 'PR', '81762-744', '(41) 98899-5166', -25.44241527, -49.25498159, '/images/academia/368.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(369, 'Hotel Exemplo 369', 'Hotel', 'Rua Marechal Deodoro', '824', 'Boqueirão', 'Araucária', 'PR', '82228-191', '(41) 99431-6837', -25.59316288, -49.39812268, '/images/hotel/369.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(370, 'Papelaria Exemplo 370', 'Papelaria', 'Rua Mateus Leme', '1982', 'Água Verde', 'Curitiba', 'PR', '88567-670', '(41) 91200-3277', -25.41957451, -49.27599234, '/images/papelaria/370.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(371, 'Supermercado Exemplo 371', 'Supermercado', 'Rua João Bettega', '1562', 'Água Verde', 'Pinhais', 'PR', '85774-773', '(41) 97696-3533', -25.43363223, -49.18128347, '/images/supermercado/371.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(372, 'Loja de Roupas Exemplo 372', 'Loja de Roupas', 'Rua João Bettega', '3288', 'Batel', 'Campo Largo', 'PR', '89502-756', '(41) 98843-3426', -25.46539613, -49.52432784, '/images/loja_de_roupas/372.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(373, 'Farmácia Exemplo 373', 'Farmácia', 'Rua XV de Novembro', '505', 'CIC', 'São José dos Pinhais', 'PR', '81258-595', '(41) 92543-8035', -25.53348662, -49.18499, '/images/farmácia/373.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(374, 'Empresa de Tecnologia Exemplo 374', 'Empresa de Tecnologia', 'Rua João Bettega', '4119', 'Portão', 'Curitiba', 'PR', '86014-459', '(41) 97785-2337', -25.41361964, -49.29315629, '/images/empresa_de_tecnologia/374.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(375, 'Papelaria Exemplo 375', 'Papelaria', 'Avenida das Torres', '1408', 'Boqueirão', 'São José dos Pinhais', 'PR', '87876-799', '(41) 91175-6094', -25.53934088, -49.19416296, '/images/papelaria/375.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(376, 'Papelaria Exemplo 376', 'Papelaria', 'Rua Mateus Leme', '655', 'Bigorrilho', 'Campo Largo', 'PR', '87749-602', '(41) 93071-2766', -25.4702623, -49.53070572, '/images/papelaria/376.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(377, 'Academia Exemplo 377', 'Academia', 'Rua João Bettega', '435', 'Água Verde', 'Pinhais', 'PR', '84300-479', '(41) 99124-3486', -25.45646524, -49.19336303, '/images/academia/377.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(378, 'Construtora Exemplo 378', 'Construtora', 'Rua XV de Novembro', '599', 'Centro', 'Pinhais', 'PR', '86142-753', '(41) 99410-2248', -25.43315647, -49.20245407, '/images/construtora/378.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(379, 'Hotel Exemplo 379', 'Hotel', 'Rua XV de Novembro', '132', 'Portão', 'Almirante Tamandaré', 'PR', '87052-710', '(41) 91480-5965', -25.3266808, -49.29431933, '/images/hotel/379.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(380, 'Escola Exemplo 380', 'Escola', 'Avenida Sete de Setembro', '4817', 'Água Verde', 'Pinhais', 'PR', '84496-929', '(41) 92208-5481', -25.43458732, -49.19406049, '/images/escola/380.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(381, 'Escritório de Advocacia Exemplo 381', 'Escritório de Advocacia', 'Avenida Paraná', '3201', 'Água Verde', 'Almirante Tamandaré', 'PR', '81896-411', '(41) 96539-7067', -25.31354323, -49.32033209, '/images/escritório_de_advocacia/381.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(382, 'Academia Exemplo 382', 'Academia', 'Rua Marechal Deodoro', '2340', 'Bigorrilho', 'São José dos Pinhais', 'PR', '86150-285', '(41) 94215-9221', -25.51325521, -49.20481927, '/images/academia/382.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(383, 'Salão de Beleza Exemplo 383', 'Salão de Beleza', 'Rua Brigadeiro Franco', '3464', 'Santa Felicidade', 'São José dos Pinhais', 'PR', '86507-349', '(41) 98461-3955', -25.54883375, -49.18352314, '/images/salão_de_beleza/383.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(384, 'Papelaria Exemplo 384', 'Papelaria', 'Rua XV de Novembro', '3529', 'Centro', 'Campo Largo', 'PR', '86980-793', '(41) 98000-4757', -25.44579661, -49.51733574, '/images/papelaria/384.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(385, 'Clínica Médica Exemplo 385', 'Clínica Médica', 'Avenida República Argentina', '2192', 'Cabral', 'São José dos Pinhais', 'PR', '82324-669', '(41) 91430-5085', -25.52804658, -49.19943916, '/images/clínica_médica/385.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(386, 'Supermercado Exemplo 386', 'Supermercado', 'Rua João Bettega', '725', 'Rebouças', 'Pinhais', 'PR', '87865-664', '(41) 98088-8495', -25.43071546, -49.18807427, '/images/supermercado/386.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(387, 'Construtora Exemplo 387', 'Construtora', 'Avenida Sete de Setembro', '1211', 'Santa Felicidade', 'Curitiba', 'PR', '83049-635', '(41) 92047-4833', -25.41679857, -49.27864323, '/images/construtora/387.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(388, 'Salão de Beleza Exemplo 388', 'Salão de Beleza', 'Rua Marechal Deodoro', '284', 'Portão', 'Almirante Tamandaré', 'PR', '88267-118', '(41) 99027-6529', -25.29755645, -49.32498873, '/images/salão_de_beleza/388.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(389, 'Farmácia Exemplo 389', 'Farmácia', 'Avenida Sete de Setembro', '852', 'Água Verde', 'Almirante Tamandaré', 'PR', '84393-885', '(41) 91924-7013', -25.31654475, -49.30748668, '/images/farmácia/389.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(390, 'Clínica Médica Exemplo 390', 'Clínica Médica', 'Avenida Paraná', '2198', 'Cabral', 'Almirante Tamandaré', 'PR', '88379-774', '(41) 98912-8182', -25.30356119, -49.29695493, '/images/clínica_médica/390.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(391, 'Construtora Exemplo 391', 'Construtora', 'Avenida República Argentina', '3911', 'Boqueirão', 'Colombo', 'PR', '81810-785', '(41) 99893-6174', -25.30083351, -49.24339744, '/images/construtora/391.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(392, 'Construtora Exemplo 392', 'Construtora', 'Rua Anita Garibaldi', '2044', 'Centro', 'Almirante Tamandaré', 'PR', '82478-467', '(41) 98489-7408', -25.30986287, -49.30878386, '/images/construtora/392.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(393, 'Padaria Exemplo 393', 'Padaria', 'Rua XV de Novembro', '3343', 'Água Verde', 'Colombo', 'PR', '86008-172', '(41) 96150-3797', -25.28239224, -49.23468371, '/images/padaria/393.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(394, 'Padaria Exemplo 394', 'Padaria', 'Rua João Bettega', '1445', 'Água Verde', 'Pinhais', 'PR', '85968-884', '(41) 94978-4987', -25.42641414, -49.18739215, '/images/padaria/394.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(395, 'Construtora Exemplo 395', 'Construtora', 'Avenida República Argentina', '4175', 'Água Verde', 'São José dos Pinhais', 'PR', '87798-686', '(41) 94325-5886', -25.52987856, -49.21589101, '/images/construtora/395.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(396, 'Supermercado Exemplo 396', 'Supermercado', 'Rua Mateus Leme', '199', 'Bigorrilho', 'Araucária', 'PR', '85665-131', '(41) 98416-4152', -25.58846644, -49.39553679, '/images/supermercado/396.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(397, 'Escola Exemplo 397', 'Escola', 'Rua Marechal Deodoro', '4715', 'Batel', 'Araucária', 'PR', '88239-884', '(41) 93687-6834', -25.58517864, -49.3875149, '/images/escola/397.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(398, 'Supermercado Exemplo 398', 'Supermercado', 'Avenida das Torres', '4513', 'Portão', 'Pinhais', 'PR', '85628-869', '(41) 98688-6804', -25.46421794, -49.1949043, '/images/supermercado/398.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(399, 'Salão de Beleza Exemplo 399', 'Salão de Beleza', 'Rua Mateus Leme', '1178', 'Rebouças', 'Almirante Tamandaré', 'PR', '87947-168', '(41) 94070-1197', -25.31694893, -49.31546281, '/images/salão_de_beleza/399.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(400, 'Farmácia Exemplo 400', 'Farmácia', 'Rua Mateus Leme', '4550', 'Água Verde', 'Araucária', 'PR', '82311-335', '(41) 96482-6498', -25.57220718, -49.40418487, '/images/farmácia/400.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(401, 'Oficina Mecânica Exemplo 401', 'Oficina Mecânica', 'Avenida República Argentina', '2697', 'Rebouças', 'Almirante Tamandaré', 'PR', '83203-541', '(41) 92898-2036', -25.29560359, -49.30328174, '/images/oficina_mecânica/401.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(402, 'Empresa de Tecnologia Exemplo 402', 'Empresa de Tecnologia', 'Avenida Sete de Setembro', '3642', 'Rebouças', 'Curitiba', 'PR', '88779-611', '(41) 91692-4331', -25.43848431, -49.29315689, '/images/empresa_de_tecnologia/402.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(403, 'Supermercado Exemplo 403', 'Supermercado', 'Avenida República Argentina', '1212', 'Portão', 'Campo Largo', 'PR', '88927-858', '(41) 95822-9892', -25.44944018, -49.53719126, '/images/supermercado/403.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(404, 'Construtora Exemplo 404', 'Construtora', 'Avenida Paraná', '3632', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '87591-387', '(41) 93485-6408', -25.29811638, -49.30718979, '/images/construtora/404.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(405, 'Papelaria Exemplo 405', 'Papelaria', 'Rua Mateus Leme', '3714', 'Água Verde', 'Colombo', 'PR', '85778-347', '(41) 97787-7292', -25.29860029, -49.2252704, '/images/papelaria/405.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(406, 'Restaurante Exemplo 406', 'Restaurante', 'Avenida das Torres', '3632', 'CIC', 'Campo Largo', 'PR', '89678-826', '(41) 97398-1331', -25.4526108, -49.54118989, '/images/restaurante/406.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(407, 'Supermercado Exemplo 407', 'Supermercado', 'Rua XV de Novembro', '2267', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '88945-673', '(41) 98207-3619', -25.29450878, -49.31193738, '/images/supermercado/407.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(408, 'Empresa de Tecnologia Exemplo 408', 'Empresa de Tecnologia', 'Rua Mateus Leme', '290', 'Portão', 'São José dos Pinhais', 'PR', '86843-979', '(41) 94161-4321', -25.52316522, -49.20489904, '/images/empresa_de_tecnologia/408.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(409, 'Construtora Exemplo 409', 'Construtora', 'Rua Marechal Deodoro', '229', 'Bigorrilho', 'Curitiba', 'PR', '88678-651', '(41) 99169-4809', -25.41998928, -49.26082074, '/images/construtora/409.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(410, 'Papelaria Exemplo 410', 'Papelaria', 'Rua Marechal Deodoro', '294', 'Batel', 'Araucária', 'PR', '87747-689', '(41) 95168-4357', -25.57864494, -49.40665103, '/images/papelaria/410.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(411, 'Escola Exemplo 411', 'Escola', 'Rua Brigadeiro Franco', '4468', 'CIC', 'Curitiba', 'PR', '87483-836', '(41) 99042-9172', -25.43837509, -49.28366466, '/images/escola/411.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(412, 'Padaria Exemplo 412', 'Padaria', 'Rua Marechal Deodoro', '403', 'CIC', 'Colombo', 'PR', '85386-718', '(41) 99506-4534', -25.29722157, -49.20868318, '/images/padaria/412.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(413, 'Farmácia Exemplo 413', 'Farmácia', 'Rua Marechal Deodoro', '4209', 'CIC', 'São José dos Pinhais', 'PR', '89867-918', '(41) 92627-7828', -25.53151386, -49.20079994, '/images/farmácia/413.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(414, 'Construtora Exemplo 414', 'Construtora', 'Avenida Paraná', '2207', 'Batel', 'Almirante Tamandaré', 'PR', '82090-569', '(41) 99169-9987', -25.30545102, -49.30954025, '/images/construtora/414.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(415, 'Construtora Exemplo 415', 'Construtora', 'Rua Marechal Deodoro', '3875', 'Bigorrilho', 'Almirante Tamandaré', 'PR', '87608-304', '(41) 97765-5355', -25.32565483, -49.29673061, '/images/construtora/415.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(416, 'Salão de Beleza Exemplo 416', 'Salão de Beleza', 'Rua Mateus Leme', '1117', 'Centro', 'Campo Largo', 'PR', '87662-612', '(41) 92002-1500', -25.46691076, -49.51879789, '/images/salão_de_beleza/416.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(417, 'Restaurante Exemplo 417', 'Restaurante', 'Avenida Sete de Setembro', '3149', 'Centro', 'São José dos Pinhais', 'PR', '87020-349', '(41) 98884-9294', -25.54000333, -49.20020191, '/images/restaurante/417.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(418, 'Empresa de Tecnologia Exemplo 418', 'Empresa de Tecnologia', 'Rua Brigadeiro Franco', '463', 'Batel', 'Curitiba', 'PR', '86083-426', '(41) 98987-4256', -25.43244115, -49.27201425, '/images/empresa_de_tecnologia/418.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(419, 'Loja de Roupas Exemplo 419', 'Loja de Roupas', 'Rua Marechal Deodoro', '1375', 'Santa Felicidade', 'São José dos Pinhais', 'PR', '88524-630', '(41) 99144-2735', -25.54072855, -49.21309992, '/images/loja_de_roupas/419.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(420, 'Empresa de Tecnologia Exemplo 420', 'Empresa de Tecnologia', 'Avenida Paraná', '3982', 'Bigorrilho', 'Araucária', 'PR', '86315-359', '(41) 97708-9453', -25.58677671, -49.40713651, '/images/empresa_de_tecnologia/420.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(421, 'Academia Exemplo 421', 'Academia', 'Avenida Paraná', '176', 'Boqueirão', 'Curitiba', 'PR', '83153-562', '(41) 98308-6098', -25.4446815, -49.27297961, '/images/academia/421.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(422, 'Padaria Exemplo 422', 'Padaria', 'Avenida Sete de Setembro', '2592', 'Centro', 'Colombo', 'PR', '87415-264', '(41) 99632-6027', -25.28330934, -49.21744583, '/images/padaria/422.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(423, 'Oficina Mecânica Exemplo 423', 'Oficina Mecânica', 'Avenida das Torres', '3756', 'Portão', 'São José dos Pinhais', 'PR', '81280-252', '(41) 91635-2118', -25.51987662, -49.20355196, '/images/oficina_mecânica/423.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(424, 'Hotel Exemplo 424', 'Hotel', 'Rua XV de Novembro', '914', 'Água Verde', 'Campo Largo', 'PR', '88717-317', '(41) 96875-3367', -25.45232808, -49.51593067, '/images/hotel/424.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(425, 'Loja de Roupas Exemplo 425', 'Loja de Roupas', 'Rua João Bettega', '1163', 'Centro', 'Campo Largo', 'PR', '84989-301', '(41) 95785-3116', -25.47800161, -49.52137759, '/images/loja_de_roupas/425.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(426, 'Escritório de Advocacia Exemplo 426', 'Escritório de Advocacia', 'Rua João Bettega', '1502', 'Portão', 'Pinhais', 'PR', '84369-338', '(41) 98103-7682', -25.45123354, -49.19757915, '/images/escritório_de_advocacia/426.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(427, 'Papelaria Exemplo 427', 'Papelaria', 'Rua XV de Novembro', '4921', 'Centro', 'São José dos Pinhais', 'PR', '86208-196', '(41) 95088-2904', -25.54338811, -49.18320803, '/images/papelaria/427.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(428, 'Supermercado Exemplo 428', 'Supermercado', 'Rua Mateus Leme', '3039', 'Santa Felicidade', 'São José dos Pinhais', 'PR', '85904-253', '(41) 99411-5314', -25.54541974, -49.2127815, '/images/supermercado/428.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(429, 'Loja de Roupas Exemplo 429', 'Loja de Roupas', 'Rua João Bettega', '329', 'Portão', 'Curitiba', 'PR', '85447-607', '(41) 99868-3800', -25.42315565, -49.2808507, '/images/loja_de_roupas/429.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(430, 'Construtora Exemplo 430', 'Construtora', 'Rua XV de Novembro', '3305', 'Rebouças', 'Campo Largo', 'PR', '88297-349', '(41) 96177-1147', -25.46580855, -49.54311066, '/images/construtora/430.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(431, 'Escola Exemplo 431', 'Escola', 'Avenida das Torres', '842', 'Centro', 'Colombo', 'PR', '88447-607', '(41) 92449-1947', -25.30298584, -49.22594338, '/images/escola/431.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(432, 'Empresa de Tecnologia Exemplo 432', 'Empresa de Tecnologia', 'Avenida Paraná', '2169', 'Santa Felicidade', 'Colombo', 'PR', '89494-232', '(41) 91985-3327', -25.3090501, -49.23250767, '/images/empresa_de_tecnologia/432.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(433, 'Salão de Beleza Exemplo 433', 'Salão de Beleza', 'Rua Marechal Deodoro', '1922', 'Cabral', 'Colombo', 'PR', '89275-738', '(41) 91305-3825', -25.29150234, -49.23370762, '/images/salão_de_beleza/433.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(434, 'Oficina Mecânica Exemplo 434', 'Oficina Mecânica', 'Rua Marechal Deodoro', '2130', 'Boqueirão', 'Curitiba', 'PR', '82745-330', '(41) 92432-3154', -25.41365711, -49.28554055, '/images/oficina_mecânica/434.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(435, 'Padaria Exemplo 435', 'Padaria', 'Avenida Paraná', '1532', 'Bigorrilho', 'Campo Largo', 'PR', '84660-677', '(41) 94808-1305', -25.47755686, -49.51955802, '/images/padaria/435.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(436, 'Farmácia Exemplo 436', 'Farmácia', 'Avenida República Argentina', '998', 'Portão', 'Campo Largo', 'PR', '86538-394', '(41) 99054-4003', -25.45978996, -49.54001734, '/images/farmácia/436.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(437, 'Academia Exemplo 437', 'Academia', 'Rua Marechal Deodoro', '2745', 'CIC', 'Colombo', 'PR', '81839-264', '(41) 94375-5795', -25.28242246, -49.23011313, '/images/academia/437.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(438, 'Loja de Roupas Exemplo 438', 'Loja de Roupas', 'Rua Anita Garibaldi', '1559', 'Batel', 'Campo Largo', 'PR', '88504-121', '(41) 97321-9247', -25.47070211, -49.54369333, '/images/loja_de_roupas/438.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(439, 'Restaurante Exemplo 439', 'Restaurante', 'Avenida Paraná', '929', 'Rebouças', 'Colombo', 'PR', '89344-672', '(41) 96538-2887', -25.30294326, -49.23060939, '/images/restaurante/439.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(440, 'Restaurante Exemplo 440', 'Restaurante', 'Avenida República Argentina', '849', 'Santa Felicidade', 'Araucária', 'PR', '86013-316', '(41) 93337-3191', -25.57005282, -49.42172045, '/images/restaurante/440.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(441, 'Academia Exemplo 441', 'Academia', 'Rua Marechal Deodoro', '3387', 'Rebouças', 'Araucária', 'PR', '81592-335', '(41) 95248-8465', -25.56535471, -49.39146615, '/images/academia/441.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(442, 'Salão de Beleza Exemplo 442', 'Salão de Beleza', 'Rua João Bettega', '1941', 'CIC', 'Colombo', 'PR', '86202-791', '(41) 93864-4273', -25.28134323, -49.22137269, '/images/salão_de_beleza/442.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(443, 'Loja de Roupas Exemplo 443', 'Loja de Roupas', 'Avenida República Argentina', '3184', 'Água Verde', 'Curitiba', 'PR', '85832-604', '(41) 92322-7532', -25.44147861, -49.2639194, '/images/loja_de_roupas/443.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(444, 'Academia Exemplo 444', 'Academia', 'Avenida Paraná', '4577', 'Cabral', 'Araucária', 'PR', '88544-195', '(41) 92846-7278', -25.56646652, -49.39612592, '/images/academia/444.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(445, 'Empresa de Tecnologia Exemplo 445', 'Empresa de Tecnologia', 'Avenida Paraná', '1904', 'Água Verde', 'Araucária', 'PR', '85856-372', '(41) 93890-3704', -25.58367011, -49.41761031, '/images/empresa_de_tecnologia/445.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(446, 'Padaria Exemplo 446', 'Padaria', 'Avenida República Argentina', '1133', 'Água Verde', 'Curitiba', 'PR', '81158-386', '(41) 94275-2797', -25.44718798, -49.29321747, '/images/padaria/446.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(447, 'Oficina Mecânica Exemplo 447', 'Oficina Mecânica', 'Rua XV de Novembro', '3925', 'Água Verde', 'Campo Largo', 'PR', '87346-998', '(41) 92809-1768', -25.45527804, -49.53463486, '/images/oficina_mecânica/447.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(448, 'Empresa de Tecnologia Exemplo 448', 'Empresa de Tecnologia', 'Rua Marechal Deodoro', '2813', 'Bigorrilho', 'Pinhais', 'PR', '84843-881', '(41) 93410-6079', -25.42505484, -49.19989794, '/images/empresa_de_tecnologia/448.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(449, 'Farmácia Exemplo 449', 'Farmácia', 'Rua Mateus Leme', '2475', 'CIC', 'São José dos Pinhais', 'PR', '88579-959', '(41) 98212-6963', -25.54764713, -49.19060879, '/images/farmácia/449.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(450, 'Escola Exemplo 450', 'Escola', 'Rua Marechal Deodoro', '3454', 'Bigorrilho', 'São José dos Pinhais', 'PR', '83069-300', '(41) 97047-3260', -25.54785807, -49.21873686, '/images/escola/450.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(451, 'Papelaria Exemplo 451', 'Papelaria', 'Rua Brigadeiro Franco', '3608', 'Centro', 'Pinhais', 'PR', '81636-383', '(41) 91985-2272', -25.42956363, -49.173675, '/images/papelaria/451.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(452, 'Loja de Roupas Exemplo 452', 'Loja de Roupas', 'Rua Brigadeiro Franco', '1078', 'Portão', 'Pinhais', 'PR', '81524-204', '(41) 98191-5264', -25.43176499, -49.20658174, '/images/loja_de_roupas/452.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(453, 'Construtora Exemplo 453', 'Construtora', 'Avenida Paraná', '3836', 'Rebouças', 'Campo Largo', 'PR', '83952-515', '(41) 99960-6904', -25.47484017, -49.52887159, '/images/construtora/453.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(454, 'Restaurante Exemplo 454', 'Restaurante', 'Rua Anita Garibaldi', '4387', 'Bigorrilho', 'São José dos Pinhais', 'PR', '87219-126', '(41) 97307-3554', -25.51362127, -49.20029889, '/images/restaurante/454.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(455, 'Empresa de Tecnologia Exemplo 455', 'Empresa de Tecnologia', 'Rua João Bettega', '3064', 'Centro', 'Araucária', 'PR', '82535-880', '(41) 96775-8001', -25.56709345, -49.39811111, '/images/empresa_de_tecnologia/455.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(456, 'Empresa de Tecnologia Exemplo 456', 'Empresa de Tecnologia', 'Rua João Bettega', '584', 'Portão', 'Curitiba', 'PR', '85680-591', '(41) 94716-6500', -25.4168121, -49.2691099, '/images/empresa_de_tecnologia/456.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(457, 'Escritório de Advocacia Exemplo 457', 'Escritório de Advocacia', 'Avenida República Argentina', '4131', 'Portão', 'Campo Largo', 'PR', '81697-638', '(41) 96072-3174', -25.46153378, -49.51923108, '/images/escritório_de_advocacia/457.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(458, 'Academia Exemplo 458', 'Academia', 'Rua XV de Novembro', '1871', 'CIC', 'Araucária', 'PR', '83218-534', '(41) 99439-4237', -25.56908628, -49.41033407, '/images/academia/458.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(459, 'Salão de Beleza Exemplo 459', 'Salão de Beleza', 'Rua Anita Garibaldi', '3315', 'Batel', 'Almirante Tamandaré', 'PR', '89937-612', '(41) 96265-3193', -25.30550057, -49.31576331, '/images/salão_de_beleza/459.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(460, 'Academia Exemplo 460', 'Academia', 'Rua Brigadeiro Franco', '137', 'CIC', 'Araucária', 'PR', '85590-323', '(41) 95100-5593', -25.60506769, -49.38910593, '/images/academia/460.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(461, 'Escola Exemplo 461', 'Escola', 'Avenida Sete de Setembro', '2717', 'CIC', 'Colombo', 'PR', '89481-311', '(41) 95350-3504', -25.28570392, -49.23607167, '/images/escola/461.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(462, 'Restaurante Exemplo 462', 'Restaurante', 'Avenida das Torres', '1528', 'Água Verde', 'Colombo', 'PR', '81493-624', '(41) 93510-4830', -25.27490382, -49.21354244, '/images/restaurante/462.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(463, 'Empresa de Tecnologia Exemplo 463', 'Empresa de Tecnologia', 'Rua Anita Garibaldi', '3465', 'Portão', 'São José dos Pinhais', 'PR', '84536-979', '(41) 98144-5067', -25.52535488, -49.20816027, '/images/empresa_de_tecnologia/463.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(464, 'Supermercado Exemplo 464', 'Supermercado', 'Rua Anita Garibaldi', '704', 'Santa Felicidade', 'Pinhais', 'PR', '82707-619', '(41) 98392-5975', -25.46259016, -49.19851457, '/images/supermercado/464.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(465, 'Padaria Exemplo 465', 'Padaria', 'Rua XV de Novembro', '758', 'Batel', 'Pinhais', 'PR', '82641-280', '(41) 95415-4125', -25.46086256, -49.20741225, '/images/padaria/465.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(466, 'Construtora Exemplo 466', 'Construtora', 'Avenida Paraná', '175', 'Santa Felicidade', 'Pinhais', 'PR', '87085-158', '(41) 94062-1237', -25.42558157, -49.18674141, '/images/construtora/466.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(467, 'Construtora Exemplo 467', 'Construtora', 'Rua Marechal Deodoro', '3843', 'Centro', 'Curitiba', 'PR', '86421-314', '(41) 99533-7214', -25.42518731, -49.27067547, '/images/construtora/467.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(468, 'Salão de Beleza Exemplo 468', 'Salão de Beleza', 'Rua Anita Garibaldi', '1944', 'Portão', 'Campo Largo', 'PR', '82695-897', '(41) 97965-6396', -25.44341092, -49.53923139, '/images/salão_de_beleza/468.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(469, 'Construtora Exemplo 469', 'Construtora', 'Avenida das Torres', '305', 'CIC', 'Araucária', 'PR', '89227-410', '(41) 99188-1930', -25.59501586, -49.41096796, '/images/construtora/469.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(470, 'Escritório de Advocacia Exemplo 470', 'Escritório de Advocacia', 'Avenida República Argentina', '1098', 'Batel', 'Campo Largo', 'PR', '88757-838', '(41) 94662-5293', -25.45324291, -49.5464344, '/images/escritório_de_advocacia/470.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(471, 'Escritório de Advocacia Exemplo 471', 'Escritório de Advocacia', 'Rua João Bettega', '1512', 'Rebouças', 'Curitiba', 'PR', '83758-181', '(41) 97612-5163', -25.42263902, -49.28044555, '/images/escritório_de_advocacia/471.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(472, 'Academia Exemplo 472', 'Academia', 'Rua Mateus Leme', '3650', 'CIC', 'Colombo', 'PR', '81001-231', '(41) 91804-9531', -25.28822856, -49.23046669, '/images/academia/472.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(473, 'Oficina Mecânica Exemplo 473', 'Oficina Mecânica', 'Rua Mateus Leme', '4546', 'Água Verde', 'Pinhais', 'PR', '86795-369', '(41) 93551-7614', -25.45272075, -49.20182111, '/images/oficina_mecânica/473.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(474, 'Supermercado Exemplo 474', 'Supermercado', 'Rua Anita Garibaldi', '1377', 'Rebouças', 'Almirante Tamandaré', 'PR', '87010-639', '(41) 93734-2765', -25.30641636, -49.3318433, '/images/supermercado/474.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(475, 'Loja de Roupas Exemplo 475', 'Loja de Roupas', 'Avenida Paraná', '3708', 'Bigorrilho', 'Pinhais', 'PR', '84392-851', '(41) 93718-5723', -25.45196136, -49.18211628, '/images/loja_de_roupas/475.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(476, 'Loja de Roupas Exemplo 476', 'Loja de Roupas', 'Avenida Sete de Setembro', '2874', 'Portão', 'Almirante Tamandaré', 'PR', '88783-693', '(41) 96053-9571', -25.29037059, -49.31638744, '/images/loja_de_roupas/476.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(477, 'Loja de Roupas Exemplo 477', 'Loja de Roupas', 'Avenida Sete de Setembro', '2410', 'Santa Felicidade', 'Araucária', 'PR', '89431-580', '(41) 99309-8980', -25.59797017, -49.38887284, '/images/loja_de_roupas/477.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(478, 'Farmácia Exemplo 478', 'Farmácia', 'Rua João Bettega', '4588', 'Batel', 'Curitiba', 'PR', '84372-558', '(41) 94517-1813', -25.42865155, -49.27137442, '/images/farmácia/478.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(479, 'Farmácia Exemplo 479', 'Farmácia', 'Rua Marechal Deodoro', '2922', 'Batel', 'Araucária', 'PR', '82789-953', '(41) 99783-9000', -25.60013256, -49.42062191, '/images/farmácia/479.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(480, 'Oficina Mecânica Exemplo 480', 'Oficina Mecânica', 'Rua João Bettega', '628', 'Boqueirão', 'Colombo', 'PR', '84346-220', '(41) 96259-4947', -25.28397636, -49.24116189, '/images/oficina_mecânica/480.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(481, 'Papelaria Exemplo 481', 'Papelaria', 'Avenida Paraná', '2263', 'Rebouças', 'Curitiba', 'PR', '83574-447', '(41) 93159-8492', -25.42230428, -49.27239108, '/images/papelaria/481.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(482, 'Hotel Exemplo 482', 'Hotel', 'Avenida República Argentina', '4303', 'Batel', 'Curitiba', 'PR', '87978-773', '(41) 98120-3919', -25.43868757, -49.27396628, '/images/hotel/482.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(483, 'Restaurante Exemplo 483', 'Restaurante', 'Rua Brigadeiro Franco', '936', 'Centro', 'Colombo', 'PR', '81271-449', '(41) 96315-6919', -25.29247163, -49.21428613, '/images/restaurante/483.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(484, 'Escritório de Advocacia Exemplo 484', 'Escritório de Advocacia', 'Rua João Bettega', '2766', 'Água Verde', 'Campo Largo', 'PR', '86094-208', '(41) 95448-4194', -25.44433597, -49.51888214, '/images/escritório_de_advocacia/484.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(485, 'Padaria Exemplo 485', 'Padaria', 'Rua Brigadeiro Franco', '1940', 'CIC', 'Campo Largo', 'PR', '81036-775', '(41) 96612-8296', -25.4560042, -49.53015776, '/images/padaria/485.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(486, 'Escola Exemplo 486', 'Escola', 'Rua Anita Garibaldi', '4936', 'Santa Felicidade', 'Colombo', 'PR', '87434-209', '(41) 94408-4120', -25.30140997, -49.23646253, '/images/escola/486.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(487, 'Escritório de Advocacia Exemplo 487', 'Escritório de Advocacia', 'Rua Mateus Leme', '2237', 'Boqueirão', 'Curitiba', 'PR', '83587-418', '(41) 91660-4848', -25.42974256, -49.27239669, '/images/escritório_de_advocacia/487.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(488, 'Padaria Exemplo 488', 'Padaria', 'Rua João Bettega', '3360', 'Boqueirão', 'Colombo', 'PR', '89244-518', '(41) 93966-2468', -25.27964276, -49.23055666, '/images/padaria/488.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(489, 'Academia Exemplo 489', 'Academia', 'Rua Mateus Leme', '4723', 'Santa Felicidade', 'Curitiba', 'PR', '85128-930', '(41) 99697-9383', -25.42616771, -49.28775428, '/images/academia/489.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(490, 'Padaria Exemplo 490', 'Padaria', 'Avenida Paraná', '2909', 'Água Verde', 'Araucária', 'PR', '83698-188', '(41) 91190-2696', -25.59822215, -49.40163362, '/images/padaria/490.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(491, 'Supermercado Exemplo 491', 'Supermercado', 'Avenida Paraná', '4538', 'Água Verde', 'Araucária', 'PR', '87140-217', '(41) 95957-5125', -25.57488476, -49.42078097, '/images/supermercado/491.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(492, 'Padaria Exemplo 492', 'Padaria', 'Rua XV de Novembro', '985', 'Água Verde', 'Araucária', 'PR', '83440-718', '(41) 93713-9792', -25.58489672, -49.39433903, '/images/padaria/492.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(493, 'Empresa de Tecnologia Exemplo 493', 'Empresa de Tecnologia', 'Avenida Sete de Setembro', '4481', 'Batel', 'Campo Largo', 'PR', '85923-362', '(41) 97999-9045', -25.46683479, -49.54302193, '/images/empresa_de_tecnologia/493.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(494, 'Escola Exemplo 494', 'Escola', 'Rua Anita Garibaldi', '2518', 'Santa Felicidade', 'Pinhais', 'PR', '88935-253', '(41) 97770-9504', -25.45716802, -49.19189641, '/images/escola/494.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(495, 'Salão de Beleza Exemplo 495', 'Salão de Beleza', 'Rua XV de Novembro', '4139', 'Batel', 'Pinhais', 'PR', '84161-107', '(41) 96468-9867', -25.46437802, -49.19540746, '/images/salão_de_beleza/495.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(496, 'Padaria Exemplo 496', 'Padaria', 'Rua XV de Novembro', '4213', 'Bigorrilho', 'São José dos Pinhais', 'PR', '84366-404', '(41) 98428-5965', -25.51572462, -49.19830587, '/images/padaria/496.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(497, 'Salão de Beleza Exemplo 497', 'Salão de Beleza', 'Rua XV de Novembro', '3517', 'Água Verde', 'Campo Largo', 'PR', '85598-230', '(41) 91561-2254', -25.44330202, -49.54510875, '/images/salão_de_beleza/497.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(498, 'Escritório de Advocacia Exemplo 498', 'Escritório de Advocacia', 'Rua XV de Novembro', '180', 'Cabral', 'Pinhais', 'PR', '87753-738', '(41) 95960-5175', -25.43715477, -49.18263922, '/images/escritório_de_advocacia/498.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(499, 'Construtora Exemplo 499', 'Construtora', 'Rua Mateus Leme', '1512', 'Bigorrilho', 'Campo Largo', 'PR', '89361-688', '(41) 93262-5758', -25.47660955, -49.53113686, '/images/construtora/499.jpg');


INSERT INTO empresas
(id, nome, categoria, rua, numero, bairro, cidade, estado, cep, telefone, latitude, longitude, imagem)
VALUES
(500, 'Papelaria Exemplo 500', 'Papelaria', 'Rua Brigadeiro Franco', '984', 'Cabral', 'Colombo', 'PR', '85478-937', '(41) 91358-4153', -25.27968288, -49.21661976, '/images/papelaria/500.jpg');
