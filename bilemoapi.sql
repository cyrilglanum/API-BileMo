-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 16 juin 2022 à 13:23
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bilemoapi`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text,
  `slug` varchar(255) DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `capacity` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `name`, `slug`, `brand`, `description`, `color`, `capacity`, `created_at`, `updated_at`) VALUES
(1, 'Samsung A40', 'A40', 'Samsung', 'try de phone', 'grey', '128', NULL, NULL),
(2, 'A 80 Samsung blue', 'A 80', 'Samsung', 'Portable de qualité supérieure', 'blue', '512', NULL, NULL),
(3, 'A60 Samsung Red', 'A60', 'Samsung', 'le portable de qualité à prix raisonnable.', 'red', '256', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `raisonsociale` varchar(255) NOT NULL,
  `siret` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id`, `username`, `raisonsociale`, `siret`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'SFR', 'SFR', '123 568 941 00056', 'SFR@sfr.com', 'sfr', '2022-03-31 12:29:09', '2022-03-31 12:29:09'),
(2, 'Orange', 'Orange', '123 568 941 00057', 'orange@orange.com', 'orange', '2022-03-31 12:29:59', '2022-03-31 12:29:59');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `roles` json NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `postalcode` int(11) DEFAULT NULL,
  `ville` varchar(255) DEFAULT NULL,
  `actif` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `roles`, `lastname`, `firstname`, `email`, `password`, `postalcode`, `ville`, `actif`, `client_id`, `created_at`, `updated_at`) VALUES
(1, '[\"ROLE_USER\"]', 'Guit', 'Cyr', 'cyri@gmail.com', '$2y$10$4EejtV.9.yxWr/nBrYktsun3z.11ST3w1K2GkPIoFTrfcYM0E4oBS', 84000, 'Avignon', 1, 1, '2022-03-31 12:33:59', '2022-03-31 12:33:59'),
(2, '[\"ROLE_USER\"]', 'Vin', 'Léoo', 'leo@gmail.com', '$2y$10$4EejtV.9.yxWr/nBrYktsun3z.11ST3w1K2GkPIoFTrfcYM0E4oBS', 31000, 'Rochefort', 1, 1, '2022-03-31 12:34:51', '2022-03-31 12:34:51'),
(3, '[\"ROLE_USER\"]', 'Langlan', 'gérard', 'gerard@gmail.com', '$2y$10$4EejtV.9.yxWr/nBrYktsun3z.11ST3w1K2GkPIoFTrfcYM0E4oBS', 31000, 'Rochefort', 1, 2, '2022-03-31 12:34:51', '2022-03-31 12:34:51'),
(4, '[\"ROLE_USER\"]', 'Dubois', 'Cédric', 'cdric@gmail.com', '$2y$10$4EejtV.9.yxWr/nBrYktsun3z.11ST3w1K2GkPIoFTrfcYM0E4oBS', 31000, 'Rochefort', 1, 2, '2022-03-31 12:34:51', '2022-03-31 12:34:51'),
(5, '[\"ROLE_USER\"]', 'dufour', 'louis', 'louois@gmail.com', '$2y$10$4EejtV.9.yxWr/nBrYktsun3z.11ST3w1K2GkPIoFTrfcYM0E4oBS', 31000, 'Rochefort', 1, 1, '2022-03-31 12:34:51', '2022-03-31 12:34:51'),
(42, '[\"ROLE_USER\"]', 'Guillaume', 'Jean', 'email@email1e.com', '$2y$13$ik7OFKQ4F2lv9OBeMt.Dtuup/vubS2BsoIWL22ZbDhwgsI9MhANVu', 84000, 'Avignon', 1, 1, '2022-06-16 10:17:19', '2022-06-16 10:17:19'),
(41, '[\"ROLE_ADMIN\"]', 'Cyril', 'admin', 'adminapi@gmail.com', '$2y$13$m73CrpiSPp1yPzjESqV7ZucoxJlOjTP6Wdt70Q0ajUQX5TTuQqIUy', 84000, 'Avignon', 1, 1, '2022-06-09 08:20:51', '2022-06-09 08:20:51'),
(37, '[\"ROLE_CLIENT\"]', 'Caroline', 'Dupont', 'teste@mzail.com', '$2y$13$m73CrpiSPp1yPzjESqV7ZucoxJlOjTP6Wdt70Q0ajUQX5TTuQqIUy', 84000, 'Avignon', 1, 1, '2022-06-01 14:58:01', '2022-06-01 14:58:01');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
