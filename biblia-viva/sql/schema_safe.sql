-- Tabela contexto_geografico
CREATE TABLE IF NOT EXISTS contexto_geografico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liv_id INT NOT NULL, -- Alterado para INT por segurança, caso a original seja INT
    capitulo INT NOT NULL,
    nome VARCHAR(100) NOT NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    descricao TEXT,
    imagem VARCHAR(255),
    INDEX idx_contexto_liv_cap (liv_id, capitulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela cronologia
CREATE TABLE IF NOT EXISTS cronologia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liv_id INT NOT NULL,
    capitulo INT NOT NULL,
    ano_estimado VARCHAR(50),
    periodo VARCHAR(100),
    personagens TEXT COMMENT 'Lista de personagens contemporâneos',
    eventos_mundiais TEXT COMMENT 'Eventos históricos paralelos',
    INDEX idx_cronologia_liv_cap (liv_id, capitulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela aplicacao_pratica
CREATE TABLE IF NOT EXISTS aplicacao_pratica (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liv_id INT NOT NULL,
    capitulo INT NOT NULL,
    verdade_central VARCHAR(255),
    alerta TEXT,
    acao_pratica TEXT,
    INDEX idx_aplicacao_liv_cap (liv_id, capitulo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
