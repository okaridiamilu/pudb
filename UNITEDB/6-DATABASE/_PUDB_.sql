-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le : mer. 11 juin 2025 à 12:16
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pudb`
--

-- --------------------------------------------------------

--
-- Structure de la table `damage_types`
--

DROP TABLE IF EXISTS `damage_types`;
CREATE TABLE IF NOT EXISTS `damage_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='table pour user';

--
-- Déchargement des données de la table `damage_types`
--

INSERT INTO `damage_types` (`id`, `label`) VALUES
(1, 'Melee'),
(2, 'Ranged');

-- --------------------------------------------------------

--
-- Structure de la table `pokemon`
--

DROP TABLE IF EXISTS `pokemon`;
CREATE TABLE IF NOT EXISTS `pokemon` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `style` int NOT NULL,
  `dmgType` int NOT NULL,
  `role` int NOT NULL,
  `healAllies` tinyint(1) NOT NULL,
  `croudControle` tinyint(1) NOT NULL,
  `selfShield` tinyint(1) NOT NULL,
  `allieShield` tinyint(1) NOT NULL,
  `buff` tinyint(1) NOT NULL,
  `debuff` tinyint(1) NOT NULL,
  `hpScale` tinyint(1) NOT NULL,
  `execute` tinyint(1) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_style_id` (`style`),
  KEY `fk_dmg_type_id` (`dmgType`),
  KEY `fk_role_id` (`role`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='tableau de pokemons';

--
-- Déchargement des données de la table `pokemon`
--

INSERT INTO `pokemon` (`id`, `name`, `style`, `dmgType`, `role`, `healAllies`, `croudControle`, `selfShield`, `allieShield`, `buff`, `debuff`, `hpScale`, `execute`, `image`) VALUES
(1, 'Absol', 3, 1, 5, 0, 1, 1, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/absol.png'),
(2, 'Aegislash', 3, 1, 4, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/aegislash.png'),
(3, 'Armarouge', 2, 2, 1, 0, 0, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/armarouge.png'),
(4, 'Azumarill', 1, 1, 4, 0, 0, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/azumarill.png'),
(5, 'Blastoise', 1, 2, 2, 0, 1, 1, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/blastoise.png'),
(6, 'Blaziken', 3, 1, 4, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/blaziken.png'),
(7, 'Blissey', 2, 1, 3, 0, 0, 1, 0, 1, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/blissey.png'),
(8, 'Buzzwole', 2, 1, 4, 0, 1, 1, 0, 0, 0, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/buzzwole.png'),
(9, 'Ceruledge', 2, 1, 4, 0, 1, 1, 0, 0, 0, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/ceruledge.png'),
(10, 'Chandelure', 1, 2, 1, 0, 1, 0, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/chandelure.png'),
(11, 'Charizard', 1, 1, 4, 0, 0, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/charizard.png'),
(12, 'Cinderace', 1, 2, 1, 0, 0, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/cinderace.png'),
(13, 'Clefable', 1, 1, 3, 1, 1, 1, 0, 1, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/clefable.png'),
(14, 'Comfey', 1, 2, 3, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/comfey.png'),
(15, 'Cramorant', 3, 2, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/cramorant.png'),
(16, 'Crustle', 2, 1, 2, 0, 1, 1, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/crustle.png'),
(17, 'Darkrai', 3, 1, 5, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/darkrai.png'),
(18, 'Decidueye', 2, 2, 1, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/decidueye.png'),
(19, 'Delphox', 2, 2, 1, 0, 0, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/delphox.png'),
(20, 'Dodrio', 3, 1, 5, 1, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/dodrio.png'),
(21, 'Dragapult', 2, 2, 1, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/dragapult.png'),
(22, 'Dragonite', 2, 2, 4, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/dragonite.png'),
(23, 'Duraludon', 2, 2, 1, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/duraludon.png'),
(24, 'Eldegoss', 1, 2, 3, 1, 0, 1, 0, 1, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/eldegoss.png'),
(25, 'Espeon', 2, 2, 1, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/espeon.png'),
(26, 'Falinks', 3, 1, 4, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/falinks.png'),
(27, 'Garchomp', 2, 1, 4, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/garchomp.png'),
(28, 'Gardevoir', 2, 2, 1, 0, 1, 1, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/gardevoir.png'),
(29, 'Gengar', 3, 1, 5, 1, 0, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/gengar.png'),
(30, 'Glaceon', 2, 2, 1, 1, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/glaceon.png'),
(31, 'Goodra', 2, 1, 2, 1, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/goodra.png'),
(32, 'Greedent', 3, 1, 2, 0, 0, 0, 0, 0, 0, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/greedent.png'),
(33, 'Greninja', 3, 2, 1, 0, 0, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/greninja.png'),
(34, 'Gyarados', 2, 1, 4, 0, 1, 0, 0, 0, 0, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/gyarados.png'),
(35, 'Ho-Oh', 1, 2, 2, 0, 1, 1, 0, 1, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/ho-oh.png'),
(36, 'Hoopa', 3, 2, 3, 1, 0, 1, 0, 1, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/hoopa.png'),
(37, 'Inteleon', 2, 2, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/inteleon.png'),
(38, 'Lapras', 2, 2, 2, 0, 1, 0, 0, 0, 1, 1, 1, 'https://img.pokemondb.net/sprites/home/normal/lapras.png'),
(39, 'Leafeon', 2, 1, 5, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/leafeon.png'),
(40, 'Lucario', 3, 1, 4, 0, 1, 0, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/lucario.png'),
(41, 'Machamp', 2, 1, 4, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/machamp.png'),
(42, 'Mamoswine', 2, 1, 2, 0, 1, 0, 0, 1, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/mamoswine.png'),
(43, 'Mamoswine', 2, 1, 2, 0, 1, 0, 0, 1, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/meowscarada.png'),
(44, 'Metagross', 2, 1, 4, 0, 1, 1, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/metagross.png'),
(45, 'Mew', 3, 2, 1, 1, 1, 0, 0, 1, 1, 1, 1, 'https://img.pokemondb.net/sprites/home/normal/mew.png'),
(46, 'Mewtwo X', 1, 1, 4, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/mewtwo-mega-x.png'),
(47, 'Mewtwo Y', 1, 2, 1, 1, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/mewtwo-mega-y.png'),
(48, 'Mimikyu', 2, 1, 4, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/mimikyu.png'),
(49, 'Miraidon', 1, 2, 1, 1, 1, 0, 0, 0, 0, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/miraidon.png'),
(50, 'Mr.', 2, 1, 3, 1, 1, 0, 0, 1, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/mr-mime.png'),
(51, 'Ninetales', 1, 2, 1, 1, 1, 0, 0, 1, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/ninetales.png'),
(52, 'Pikachu', 1, 2, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/pikachu.png'),
(53, 'Psyduck', 1, 2, 3, 0, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/psyduck.png'),
(54, 'Raichu', 2, 2, 1, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/raichu.png'),
(55, 'Rapidash', 2, 2, 5, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/rapidash.png'),
(56, 'Sableye', 2, 1, 3, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/sableye.png'),
(57, 'Scizor', 2, 1, 4, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/scizor.png'),
(58, 'Scyther', 2, 1, 4, 0, 0, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/scyther.png'),
(59, 'Slowbro', 1, 2, 2, 1, 1, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/slowbro.png'),
(60, 'Snorlax', 1, 1, 2, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/snorlax.png'),
(61, 'Suicune', 2, 2, 4, 0, 1, 0, 0, 0, 1, 1, 1, 'https://img.pokemondb.net/sprites/home/normal/suicune.png'),
(62, 'Sylveon', 1, 2, 1, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/sylveon.png'),
(63, 'Talonflame', 2, 1, 5, 0, 0, 0, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/talonflame.png'),
(64, 'Tinkaton', 1, 1, 4, 0, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/tinkaton.png'),
(65, 'Trevenant', 2, 1, 2, 0, 1, 0, 0, 1, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/trevenant.png'),
(66, 'Tsareena', 3, 1, 4, 0, 1, 1, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/tsareena.png'),
(67, 'Tyranitar', 2, 1, 4, 0, 1, 1, 0, 0, 1, 1, 1, 'https://img.pokemondb.net/sprites/home/normal/tyranitar.png'),
(68, 'Umbreon', 1, 1, 2, 1, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/umbreon.png'),
(69, 'Urshifu', 2, 1, 4, 0, 1, 1, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/urshifu.png'),
(70, 'Venusaur', 1, 2, 1, 0, 1, 0, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/venusaur.png'),
(71, 'Wigglytuff', 2, 1, 3, 1, 1, 0, 0, 0, 1, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/wigglytuff.png'),
(72, 'Zacian', 2, 1, 4, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/zacian.png'),
(73, 'Zeraora', 2, 1, 5, 0, 1, 1, 0, 0, 1, 1, 0, 'https://img.pokemondb.net/sprites/home/normal/zeraora.png'),
(74, 'Zoroark', 3, 1, 5, 1, 1, 0, 0, 0, 0, 0, 0, 'https://img.pokemondb.net/sprites/home/normal/zoroark.png');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='table pour user';

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `label`) VALUES
(1, 'Attacker'),
(2, 'Defender'),
(3, 'Supporter'),
(4, 'All-Rounder'),
(5, 'Speedster');

-- --------------------------------------------------------

--
-- Structure de la table `styles`
--

DROP TABLE IF EXISTS `styles`;
CREATE TABLE IF NOT EXISTS `styles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='table pour user';

--
-- Déchargement des données de la table `styles`
--

INSERT INTO `styles` (`id`, `label`) VALUES
(1, 'Novice'),
(2, 'Intermediate'),
(3, 'Expert');

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `pokemon1_id` int NOT NULL,
  `pokemon2_id` int NOT NULL,
  `pokemon3_id` int NOT NULL,
  `pokemon4_id` int NOT NULL,
  `pokemon5_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `poke1_id` (`pokemon1_id`),
  KEY `poke2_id` (`pokemon2_id`),
  KEY `poke3_id` (`pokemon3_id`),
  KEY `poke4_id` (`pokemon4_id`),
  KEY `poke5_id` (`pokemon5_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'id client',
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='table pour user';

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'lumina', '$2y$10$uM28viTxRE75OF/nMmkLS.aO252j1811YYvYjpi.y/cdyN/GH3VoS', '2025-06-02 13:03:25'),
(3, 'Schlawgadawg', '$2y$10$HNpyfMjKl8JuvNSEbtOFdOVgZG9I0JjQCoucfyM8nZzXwXHVi/da.', '2025-06-10 15:29:18'),
(4, 'chrichri', '$2y$10$BukCOKxrW/H257vk/5MPdOt5FAvv4m/i5ldG6CezTHU/EapR/0HKm', '2025-06-10 23:54:10');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
