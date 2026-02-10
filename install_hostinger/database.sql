-- Database Schema for Mais Deus - Bible Happening Now

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `abbrev` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `testament` enum('old','new') NOT NULL,
  `group_name` varchar(50) DEFAULT NULL, -- e.g., Pentateuco, Evangelhos
  PRIMARY KEY (`id`),
  UNIQUE KEY `abbrev` (`abbrev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `book_chapter` (`book_id`, `number`),
  CONSTRAINT `fk_chapter_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `verses`
--

CREATE TABLE `verses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `text` text NOT NULL,
  `version` varchar(10) NOT NULL DEFAULT 'nvi', -- nvi, ra, acf, kjv, etc.
  PRIMARY KEY (`id`),
  KEY `idx_version_chapter` (`version`, `chapter_id`),
  CONSTRAINT `fk_verse_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `historical_context` (Where, When, Who)
--

CREATE TABLE `historical_context` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `who_involved` text DEFAULT NULL, -- JSON array or comma-separated list of people
  `when_text` varchar(255) DEFAULT NULL, -- e.g., "Cerca de 1446 a.C."
  `where_text` varchar(255) DEFAULT NULL, -- e.g., "Deserto do Sinai"
  `historical_events` text DEFAULT NULL, -- Parallel events
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapter_id` (`chapter_id`),
  CONSTRAINT `fk_context_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `timeline_events`
--

CREATE TABLE `timeline_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `year` int(11) NOT NULL, -- Negative for BC
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `era` varchar(100) DEFAULT NULL, -- e.g., "Patriarcas", "Reino Unido"
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_timeline_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `map_locations`
--

CREATE TABLE `map_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `latitude` decimal(10, 8) NOT NULL,
  `longitude` decimal(11, 8) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('city', 'route', 'region', 'mountain') DEFAULT 'city',
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_map_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `spiritual_applications` (The "Why")
--

CREATE TABLE `spiritual_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_id` int(11) NOT NULL,
  `truth` text NOT NULL, -- Central Truth
  `alert` text DEFAULT NULL, -- Warning
  `action` text NOT NULL, -- Practical Action
  PRIMARY KEY (`id`),
  UNIQUE KEY `chapter_id` (`chapter_id`),
  CONSTRAINT `fk_app_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `chapter_id` int(11) NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notes` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_chapter` (`user_id`, `chapter_id`),
  CONSTRAINT `fk_progress_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_progress_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
