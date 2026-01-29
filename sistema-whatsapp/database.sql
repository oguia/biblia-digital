SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `instances` (Representa um número de WhatsApp)
--

CREATE TABLE `instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL, -- Ex: "Vendas", "Suporte"
  `session_name` varchar(50) NOT NULL, -- Identificador interno para o arquivo de sessão
  `status` enum('disconnected','connecting','connected') DEFAULT 'disconnected',
  `qrcode` text DEFAULT NULL, -- Armazena o último QR code gerado
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `session_name` (`session_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `instance_id` int(11) DEFAULT NULL, -- NULL = Super Admin (vê tudo). ID = Acesso restrito àquela instância.
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `instance_id` (`instance_id`),
  CONSTRAINT `fk_admin_instance` FOREIGN KEY (`instance_id`) REFERENCES `instances` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `instance_id` int(11) NOT NULL, -- A qual número esse contato pertence
  `phone` varchar(50) NOT NULL, -- ID do contato no WhatsApp (ex: 55119999@s.whatsapp.net)
  `name` varchar(100) DEFAULT NULL,
  `profile_pic_url` text DEFAULT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `unread_count` int(11) DEFAULT 0,
  `last_activity` datetime DEFAULT CURRENT_TIMESTAMP,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `instance_phone` (`instance_id`, `phone`),
  CONSTRAINT `fk_contact_instance` FOREIGN KEY (`instance_id`) REFERENCES `instances` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `remote_jid` varchar(50) NOT NULL, -- O ID do whatsapp (redundancia útil)
  `from_me` tinyint(1) NOT NULL, -- 1 = enviada por mim, 0 = recebida
  `type` varchar(20) DEFAULT 'text',
  `body` text,
  `media_url` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `fk_message_contact` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Default Super Admin
-- User: admin@admin.com / Pass: 123456
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `instance_id`) VALUES
(1, 'Super Admin', 'admin@admin.com', '$2y$10$WTHVajetkixyGhQ0casn/e/yPORm/rB2KIOWqNTqURqLgA7y5A/3C', NULL);

COMMIT;
