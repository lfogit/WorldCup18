-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 27 mai 2018 à 21:28
-- Version du serveur :  5.7.20-18-log
-- Version de PHP :  7.1.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `PronosCDM`
--

-- --------------------------------------------------------

--
-- Structure de la table `commus`
--

CREATE TABLE `commus` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE `matchs` (
  `id` int(11) NOT NULL,
  `groupe` varchar(2) NOT NULL,
  `team1` int(11) NOT NULL,
  `team2` int(11) NOT NULL,
  `score1` int(11) NOT NULL DEFAULT '0',
  `score2` int(11) NOT NULL DEFAULT '0',
  `winner` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `played` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `matchs`
--

INSERT INTO `matchs` (`id`, `groupe`, `team1`, `team2`, `score1`, `score2`, `winner`, `date`, `played`) VALUES
(1, 'A', 1, 2, 0, 0, 0, '2018-06-14 17:00:00', 0),
(2, 'A', 3, 4, 0, 0, 0, '2018-06-15 14:00:00', 0),
(3, 'B', 7, 8, 0, 0, 0, '2018-06-15 17:00:00', 0),
(4, 'B', 5, 6, 0, 0, 0, '2018-06-15 20:00:00', 0),
(5, 'C', 9, 10, 0, 0, 0, '2018-06-16 12:00:00', 0),
(6, 'D', 13, 14, 0, 0, 0, '2018-06-16 15:00:00', 0),
(7, 'C', 11, 12, 0, 0, 0, '2018-06-16 18:00:00', 0),
(8, 'D', 15, 16, 0, 0, 0, '2018-06-16 21:00:00', 0),
(9, 'E', 19, 20, 0, 0, 0, '2018-06-17 14:00:00', 0),
(10, 'F', 21, 22, 0, 0, 0, '2018-06-17 17:00:00', 0),
(11, 'E', 17, 18, 0, 0, 0, '2018-06-17 20:00:00', 0),
(12, 'F', 23, 24, 0, 0, 0, '2018-06-18 14:00:00', 0),
(13, 'G', 25, 26, 0, 0, 0, '2018-06-18 17:00:00', 0),
(14, 'G', 27, 28, 0, 0, 0, '2018-06-18 20:00:00', 0),
(15, 'H', 30, 32, 0, 0, 0, '2018-06-19 14:00:00', 0),
(16, 'H', 29, 31, 0, 0, 0, '2018-06-19 17:00:00', 0),
(17, 'A', 1, 3, 0, 0, 0, '2018-06-19 20:00:00', 0),
(18, 'B', 5, 7, 0, 0, 0, '2018-06-20 14:00:00', 0),
(19, 'A', 2, 4, 0, 0, 0, '2018-06-20 17:00:00', 0),
(20, 'B', 6, 8, 0, 0, 0, '2018-06-20 20:00:00', 0),
(21, 'C', 10, 12, 0, 0, 0, '2018-06-21 14:00:00', 0),
(22, 'C', 9, 11, 0, 0, 0, '2018-06-21 17:00:00', 0),
(23, 'D', 13, 15, 0, 0, 0, '2018-06-21 20:00:00', 0),
(24, 'E', 17, 19, 0, 0, 0, '2018-06-22 14:00:00', 0),
(25, 'D', 14, 16, 0, 0, 0, '2018-06-22 17:00:00', 0),
(26, 'E', 18, 20, 0, 0, 0, '2018-06-22 20:00:00', 0),
(27, 'G', 25, 27, 0, 0, 0, '2018-06-23 14:00:00', 0),
(28, 'F', 22, 24, 0, 0, 0, '2018-06-23 17:00:00', 0),
(29, 'F', 21, 23, 0, 0, 0, '2018-06-23 20:00:00', 0),
(30, 'G', 26, 28, 0, 0, 0, '2018-06-24 14:00:00', 0),
(31, 'H', 31, 32, 0, 0, 0, '2018-06-24 17:00:00', 0),
(32, 'H', 29, 30, 0, 0, 0, '2018-06-24 20:00:00', 0),
(33, 'A', 1, 4, 0, 0, 0, '2018-06-25 16:00:00', 0),
(34, 'A', 2, 3, 0, 0, 0, '2018-06-25 16:00:00', 0),
(35, 'B', 5, 8, 0, 0, 0, '2018-06-25 20:00:00', 0),
(36, 'B', 6, 7, 0, 0, 0, '2018-06-25 20:00:00', 0),
(37, 'C', 9, 12, 0, 0, 0, '2018-06-26 16:00:00', 0),
(38, 'C', 10, 11, 0, 0, 0, '2018-06-26 16:00:00', 0),
(39, 'D', 13, 16, 0, 0, 0, '2018-06-26 20:00:00', 0),
(40, 'D', 14, 15, 0, 0, 0, '2018-06-26 20:00:00', 0),
(41, 'F', 21, 24, 0, 0, 0, '2018-06-27 16:00:00', 0),
(42, 'F', 22, 23, 0, 0, 0, '2018-06-27 16:00:00', 0),
(43, 'E', 17, 20, 0, 0, 0, '2018-06-27 20:00:00', 0),
(44, 'E', 18, 19, 0, 0, 0, '2018-06-27 20:00:00', 0),
(45, 'H', 29, 32, 0, 0, 0, '2018-06-28 16:00:00', 0),
(46, 'H', 30, 31, 0, 0, 0, '2018-06-28 16:00:00', 0),
(47, 'G', 25, 28, 0, 0, 0, '2018-06-28 20:00:00', 0),
(48, 'G', 26, 27, 0, 0, 0, '2018-06-28 20:00:00', 0),
(49, 'H1', 0, 0, 0, 0, 0, '2018-06-30 20:00:00', 0),
(50, 'H2', 0, 0, 0, 0, 0, '2018-06-30 16:00:00', 0),
(51, 'H3', 0, 0, 0, 0, 0, '2018-07-02 16:00:00', 0),
(52, 'H4', 0, 0, 0, 0, 0, '2018-07-02 20:00:00', 0),
(53, 'H5', 0, 0, 0, 0, 0, '2018-07-01 20:00:00', 0),
(54, 'H6', 0, 0, 0, 0, 0, '2018-07-01 20:00:00', 0),
(55, 'H7', 0, 0, 0, 0, 0, '2018-07-03 16:00:00', 0),
(56, 'H8', 0, 0, 0, 0, 0, '2018-07-03 20:00:00', 0),
(57, 'Q1', 0, 0, 0, 0, 0, '2018-07-06 16:00:00', 0),
(58, 'Q2', 0, 0, 0, 0, 0, '2018-07-06 20:00:00', 0),
(59, 'Q3', 0, 0, 0, 0, 0, '2018-07-07 20:00:00', 0),
(60, 'Q4', 0, 0, 0, 0, 0, '2018-07-07 16:00:00', 0),
(61, 'D1', 0, 0, 0, 0, 0, '2018-07-10 20:00:00', 0),
(62, 'D2', 0, 0, 0, 0, 0, '2018-07-11 20:00:00', 0),
(63, 'F0', 0, 0, 0, 0, 0, '2018-07-15 17:00:00', 0),
(64, 'F1', 0, 0, 0, 0, 0, '2018-07-14 16:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `paris_0`
--

CREATE TABLE `paris_0` (
  `id_pari` int(11) NOT NULL,
  `id_e1` int(11) NOT NULL,
  `id_e2` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `grp` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `paris_divers`
--

CREATE TABLE `paris_divers` (
  `id_pari` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_obj` varchar(1) NOT NULL,
  `val` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `paris_match`
--

CREATE TABLE `paris_match` (
  `id_pari` int(11) NOT NULL,
  `id_match` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `score1` int(11) NOT NULL,
  `score2` int(11) NOT NULL,
  `winner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `teams`
--

CREATE TABLE `teams` (
  `id` int(11) NOT NULL,
  `pays` varchar(255) NOT NULL,
  `groupe` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `teams`
--

INSERT INTO `teams` (`id`, `pays`, `groupe`) VALUES
(1, 'Russie', 'A'),
(2, 'Arabie saoudite', 'A'),
(3, 'Égypte', 'A'),
(4, 'Uruguay', 'A'),
(5, 'Portugal', 'B'),
(6, 'Espagne', 'B'),
(7, 'Maroc', 'B'),
(8, 'Iran', 'B'),
(9, 'France', 'C'),
(10, 'Australie', 'C'),
(11, 'Pérou', 'C'),
(12, 'Danemark', 'C'),
(13, 'Argentine', 'D'),
(14, 'Islande', 'D'),
(15, 'Croatie', 'D'),
(16, 'Nigéria', 'D'),
(17, 'Brésil', 'E'),
(18, 'Suisse', 'E'),
(19, 'Costa Rica', 'E'),
(20, 'Serbie', 'E'),
(21, 'Allemagne', 'F'),
(22, 'Mexique', 'F'),
(23, 'Suède', 'F'),
(24, 'Corée du Sud', 'F'),
(25, 'Belgique', 'G'),
(26, 'Panama', 'G'),
(27, 'Tunisie', 'G'),
(28, 'Angleterre', 'G'),
(29, 'Pologne', 'H'),
(30, 'Colombie', 'H'),
(31, 'Sénégal', 'H'),
(32, 'Japon', 'H');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `points` int(11) NOT NULL DEFAULT '0',
  `id_commu` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `clef` varchar(32) NOT NULL,
  `actif` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commus`
--
ALTER TABLE `commus`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `paris_0`
--
ALTER TABLE `paris_0`
  ADD UNIQUE KEY `id_pari` (`id_pari`);

--
-- Index pour la table `paris_divers`
--
ALTER TABLE `paris_divers`
  ADD PRIMARY KEY (`id_pari`);

--
-- Index pour la table `paris_match`
--
ALTER TABLE `paris_match`
  ADD PRIMARY KEY (`id_pari`);

--
-- Index pour la table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commus`
--
ALTER TABLE `commus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `matchs`
--
ALTER TABLE `matchs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT pour la table `paris_0`
--
ALTER TABLE `paris_0`
  MODIFY `id_pari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `paris_divers`
--
ALTER TABLE `paris_divers`
  MODIFY `id_pari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `paris_match`
--
ALTER TABLE `paris_match`
  MODIFY `id_pari` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
