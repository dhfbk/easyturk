-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 30, 2020 alle 11:21
-- Versione del server: 5.7.32-0ubuntu0.16.04.1
-- Versione PHP: 7.0.33-0ubuntu0.16.04.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mturk`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  `assignment_id` varchar(200) COLLATE utf8_bin NOT NULL,
  `label` varchar(200) COLLATE utf8_bin NOT NULL,
  `value` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `assignments`
--

CREATE TABLE `assignments` (
  `id` int(11) NOT NULL,
  `assignment_id` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `hit_id` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `worker_id` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `status` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `assignment_info` text COLLATE utf8_bin,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `blocked_workers`
--

CREATE TABLE `blocked_workers` (
  `user_id` int(11) NOT NULL,
  `worker_id` varchar(200) COLLATE utf8_bin NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `clusters`
--

CREATE TABLE `clusters` (
  `id` int(11) NOT NULL,
  `cluster_index` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  `alea` double DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `cluster_to_hit`
--

CREATE TABLE `cluster_to_hit` (
  `id` int(11) NOT NULL,
  `id_cluster` int(11) NOT NULL,
  `id_project` int(11) NOT NULL,
  `id_hit` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `hit_status` enum('Inserted','Assignable','Unassignable','Reviewable','Reviewing','Disposed','Error') COLLATE utf8_bin DEFAULT 'Inserted',
  `hit_info` text COLLATE utf8_bin,
  `max_assignments` int(11) DEFAULT NULL,
  `assignments_pending` int(11) DEFAULT NULL,
  `assignments_available` int(11) DEFAULT NULL,
  `assignments_completed` int(11) DEFAULT NULL,
  `checked_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `file_lines`
--

CREATE TABLE `file_lines` (
  `id` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `line_text` text COLLATE utf8_bin NOT NULL,
  `qualification` varchar(200) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `origin` tinyint(4) NOT NULL DEFAULT '0',
  `action` varchar(200) COLLATE utf8_bin NOT NULL,
  `id_mturk` varchar(200) COLLATE utf8_bin NOT NULL,
  `log_text` mediumtext COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `options`
--

CREATE TABLE `options` (
  `id` varchar(200) COLLATE utf8_bin NOT NULL,
  `value` mediumtext COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `projects`
--

CREATE TABLE `projects` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `title` varchar(255) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `keywords` text COLLATE utf8_bin NOT NULL,
  `reward` float NOT NULL,
  `workers` int(11) NOT NULL,
  `max_time` int(11) NOT NULL,
  `expiry` int(11) NOT NULL,
  `auto_approve` int(11) NOT NULL,
  `layout_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `layout_fields` text COLLATE utf8_bin NOT NULL,
  `params` int(11) NOT NULL,
  `params_fields` varchar(255) COLLATE utf8_bin NOT NULL,
  `qualifications` text COLLATE utf8_bin NOT NULL,
  `hit_details` text COLLATE utf8_bin,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `project_files`
--

CREATE TABLE `project_files` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `filename` varchar(255) COLLATE utf8_bin NOT NULL,
  `is_gold` tinyint(1) NOT NULL,
  `fields` text COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `raw_notifications`
--

CREATE TABLE `raw_notifications` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(200) COLLATE utf8_bin NOT NULL,
  `common_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `aws_access_key_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `aws_secret_access_key` varchar(255) COLLATE utf8_bin NOT NULL,
  `region_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'us-east-1',
  `use_sandbox` tinyint(1) NOT NULL DEFAULT '0',
  `user_info` text COLLATE utf8_bin NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `common_name`, `aws_access_key_id`, `aws_secret_access_key`, `region_name`, `use_sandbox`, `user_info`) VALUES
(1, 'user', '5f4dcc3b5aa765d61d8327deb882cf99', 'John Doe', 'AWS_ACCESS_KEY_ID', 'AWS_SECRET_ACCESS_KEY', 'us-east-1', 1, '');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `line_id_2` (`line_id`,`assignment_id`,`label`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indici per le tabelle `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `assignment_id` (`assignment_id`);

--
-- Indici per le tabelle `blocked_workers`
--
ALTER TABLE `blocked_workers`
  ADD PRIMARY KEY (`user_id`,`worker_id`);

--
-- Indici per le tabelle `clusters`
--
ALTER TABLE `clusters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `line_id` (`line_id`),
  ADD KEY `cluster_index` (`cluster_index`),
  ADD KEY `line_id_cluster_index` (`cluster_index`,`line_id`) USING BTREE,
  ADD KEY `deleted` (`deleted`);

--
-- Indici per le tabelle `cluster_to_hit`
--
ALTER TABLE `cluster_to_hit`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_hit_3` (`id_hit`),
  ADD KEY `id_cluster` (`id_cluster`),
  ADD KEY `id_project` (`id_project`),
  ADD KEY `id_hit` (`id_hit`),
  ADD KEY `deleted` (`deleted`),
  ADD KEY `id_hit_2` (`id_hit`,`deleted`);

--
-- Indici per le tabelle `file_lines`
--
ALTER TABLE `file_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_mturk` (`id_mturk`);

--
-- Indici per le tabelle `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indici per le tabelle `project_files`
--
ALTER TABLE `project_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deleted` (`deleted`);

--
-- Indici per le tabelle `raw_notifications`
--
ALTER TABLE `raw_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `clusters`
--
ALTER TABLE `clusters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `cluster_to_hit`
--
ALTER TABLE `cluster_to_hit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `file_lines`
--
ALTER TABLE `file_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `project_files`
--
ALTER TABLE `project_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `raw_notifications`
--
ALTER TABLE `raw_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
