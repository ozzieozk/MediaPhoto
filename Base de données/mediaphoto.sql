-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 10 nov. 2020 à 20:05
-- Version du serveur :  10.4.14-MariaDB
-- Version de PHP : 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mediaphoto`
--

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

CREATE TABLE `galerie` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `type` int(11) NOT NULL,
  `auteur` int(11) NOT NULL,
  `taille` float NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `galerie`
--

INSERT INTO `galerie` (`id`, `titre`, `description`, `date`, `type`, `auteur`, `taille`) VALUES
(1, 'Vacances', 'Mes superbes vacances à la mer', '2020-11-02 23:00:00', 1, 1, 2472),
(2, 'Montagne', 'Mes superbes vacances à la montagne', '2020-11-02 23:00:00', 1, 1, 0),
(52, 'Papillons', 'Papillons galerie', '2020-11-09 23:00:00', 3, 1, 0),
(53, 'test', 'test', '2020-11-09 23:00:00', 3, 1, 0),
(54, 'Voyage', 'Les photos prises au bon moment avec mon Nicon 3400.', '2020-11-10 17:57:49', 1, 11, 0);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_partage`
--

CREATE TABLE `galerie_partage` (
  `id` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `galerie_partage`
--

INSERT INTO `galerie_partage` (`id`, `id_utilisateur`, `id_galerie`) VALUES
(13, 2, 52),
(14, 11, 52),
(15, 2, 53),
(16, 11, 53),
(17, 1, 53);

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `chemin` varchar(255) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL,
  `qualite` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `taille` float NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id`, `titre`, `date`, `chemin`, `id_utilisateur`, `id_galerie`, `qualite`, `type`, `taille`) VALUES
(1, 'Plage magnifique', '2020-11-02 23:00:00', '/html/images/1.jpg', 1, 1, 'HD', 'jpg', 10),
(2, 'Plage moche', '2020-11-02 23:00:00', '/html/images/4.jpg', 1, 1, 'FHD', 'jpg', 57),
(3, 'Montagne anonyme', '2020-11-04 23:00:00', '/html/images/3.jpg', 2, 2, 'Ultra HD', 'jpg', 47),
(4, 'Mont Fuji', '2020-11-01 23:00:00', '/html/images/4.jpg', 2, 2, 'HD', 'jpg', 17),
(5, 'Voici ma 5ème photo !', '2020-11-10 18:03:18', '/html/images/5.jpg', 11, 54, 'FULL HD', 'jpg', 36.5),
(6, 'Voici ma 6ème photo !', '2020-11-10 18:03:18', '/html/images/6.jpg', 11, 54, 'HD', 'jpg', 43.8),
(7, 'Voici ma 7ème photo !', '2020-11-10 18:03:18', '/html/images/7.jpeg', 11, 54, 'FULL HD', 'jpg', 51.1),
(8, 'Voici ma 8ème photo !', '2020-11-10 18:03:18', '/html/images/8.jpeg', 11, 54, 'HD', 'jpg', 58.4),
(9, 'Voici ma 9ème photo !', '2020-11-10 18:03:18', '/html/images/9.jpg', 11, 54, 'FULL HD', 'jpg', 65.7),
(10, 'Voici ma 10ème photo !', '2020-11-10 18:03:18', '/html/images/10.jpg', 11, 54, 'HD', 'jpg', 73),
(11, 'Voici ma 11ème photo !', '2020-11-10 18:03:18', '/html/images/11.jpg', 11, 54, 'FULL HD', 'jpg', 80.3),
(12, 'Voici ma 12ème photo !', '2020-11-10 18:03:18', '/html/images/12.jpg', 11, 54, 'HD', 'jpg', 87.6),
(13, 'Voici ma 13ème photo !', '2020-11-10 18:03:18', '/html/images/13.jpeg', 11, 54, 'FULL HD', 'jpg', 94.9),
(14, 'Voici ma 14ème photo !', '2020-11-10 18:03:18', '/html/images/14.jpeg', 11, 54, 'HD', 'jpg', 102.2),
(15, 'Voici ma 15ème photo !', '2020-11-10 18:03:18', '/html/images/15.jpeg', 11, 54, 'FULL HD', 'jpg', 109.5),
(16, 'Voici ma 16ème photo !', '2020-11-10 18:03:18', '/html/images/16.jpeg', 11, 54, 'HD', 'jpg', 116.8),
(17, 'Voici ma 17ème photo !', '2020-11-10 18:03:18', '/html/images/17.jpg', 11, 54, 'FULL HD', 'jpg', 124.1),
(18, 'Voici ma 18ème photo !', '2020-11-10 18:03:18', '/html/images/18.jpg', 11, 54, 'HD', 'jpg', 131.4),
(19, 'Voici ma 19ème photo !', '2020-11-10 18:03:18', '/html/images/19.jpg', 11, 54, 'FULL HD', 'jpg', 138.7),
(20, 'Voici ma 20ème photo !', '2020-11-10 18:03:18', '/html/images/20.jpg', 11, 54, 'HD', 'jpg', 146),
(21, 'Voici ma 21ème photo !', '2020-11-10 18:03:18', '/html/images/21.jpeg', 11, 54, 'FULL HD', 'jpg', 153.3),
(22, 'Voici ma 22ème photo !', '2020-11-10 18:03:18', '/html/images/22.jpg', 11, 54, 'HD', 'jpg', 160.6),
(23, 'Voici ma 23ème photo !', '2020-11-10 18:03:18', '/html/images/23.jpg', 11, 54, 'FULL HD', 'jpg', 167.9),
(24, 'Voici ma 24ème photo !', '2020-11-10 18:03:18', '/html/images/24.jpeg', 11, 54, 'HD', 'jpg', 175.2),
(25, 'Voici ma 25ème photo !', '2020-11-10 18:03:18', '/html/images/25.jpg', 11, 54, 'FULL HD', 'jpg', 182.5),
(26, 'Voici ma 26ème photo !', '2020-11-10 18:03:18', '/html/images/26.jpg', 11, 54, 'HD', 'jpg', 189.8),
(27, 'Voici ma 27ème photo !', '2020-11-10 18:03:18', '/html/images/27.jpg', 11, 54, 'FULL HD', 'jpg', 197.1),
(28, 'Voici ma 28ème photo !', '2020-11-10 18:03:18', '/html/images/28.jpg', 11, 54, 'HD', 'jpg', 204.4),
(29, 'Voici ma 29ème photo !', '2020-11-10 18:03:18', '/html/images/29.jpg', 11, 54, 'FULL HD', 'jpg', 211.7),
(30, 'Voici ma 30ème photo !', '2020-11-10 18:03:18', '/html/images/30.jpeg', 11, 54, 'HD', 'jpg', 219),
(31, 'Voici ma 31ème photo !', '2020-11-10 18:03:18', '/html/images/31.jpeg', 11, 54, 'FULL HD', 'jpg', 226.3),
(32, 'Voici ma 32ème photo !', '2020-11-10 18:03:18', '/html/images/32.jpg', 11, 54, 'HD', 'jpg', 233.6),
(33, 'Voici ma 33ème photo !', '2020-11-10 18:03:18', '/html/images/33.jpg', 11, 54, 'FULL HD', 'jpg', 240.9),
(34, 'Voici ma 34ème photo !', '2020-11-10 18:03:18', '/html/images/34.jpg', 11, 54, 'HD', 'jpg', 248.2),
(35, 'Voici ma 35ème photo !', '2020-11-10 18:03:18', '/html/images/35.jpg', 11, 54, 'FULL HD', 'jpg', 255.5),
(36, 'Voici ma 36ème photo !', '2020-11-10 18:03:18', '/html/images/36.jpg', 11, 54, 'HD', 'jpg', 262.8),
(37, 'Voici ma 37ème photo !', '2020-11-10 18:03:18', '/html/images/37.jpeg', 11, 54, 'FULL HD', 'jpg', 270.1),
(38, 'Voici ma 38ème photo !', '2020-11-10 18:03:18', '/html/images/38.jpg', 11, 54, 'HD', 'jpg', 277.4),
(39, 'Voici ma 39ème photo !', '2020-11-10 18:03:18', '/html/images/39.jpg', 11, 54, 'FULL HD', 'jpg', 284.7);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE `tag` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `nom`) VALUES
(39, 'papillons'),
(40, 'insectes'),
(41, 'nature'),
(49, 'multifus'),
(48, 'logo'),
(45, 'test'),
(46, 'bonjour'),
(47, 'eliott');

-- --------------------------------------------------------

--
-- Structure de la table `tag_galerie`
--

CREATE TABLE `tag_galerie` (
  `id` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `tag_galerie`
--

INSERT INTO `tag_galerie` (`id`, `id_tag`, `id_galerie`) VALUES
(52, 46, 53),
(51, 45, 53),
(50, 39, 53),
(49, 45, 52),
(48, 39, 52);

-- --------------------------------------------------------

--
-- Structure de la table `tag_photo`
--

CREATE TABLE `tag_photo` (
  `id` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL,
  `id_photo` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `nom_complet` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `level` int(11) NOT NULL DEFAULT 100
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `nom_complet`, `mdp`, `level`) VALUES
(1, 'admin', 'John Doe', '$2y$10$y0F5Z.E7qGjhTQhkLAlwFOVaOu8J4VLGjSwh9V834GzGax.2K/yC6', 100),
(2, 'evann', 'Evann Gehin', 'azerty', 100),
(3, 'vivien', 'Vivien Klop', 'azerty', 100),
(11, 'lucaas855', 'Lucas PORTARO', '$2y$10$h03P9GiY4dzkImYZVXIPTejF8oOvMrhf3rQSD0r5x9QBGxNjaQ0/y', 100);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galerie_partage`
--
ALTER TABLE `galerie_partage`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag_galerie`
--
ALTER TABLE `tag_galerie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tag_photo`
--
ALTER TABLE `tag_photo`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `galerie`
--
ALTER TABLE `galerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `galerie_partage`
--
ALTER TABLE `galerie_partage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT pour la table `tag`
--
ALTER TABLE `tag`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `tag_galerie`
--
ALTER TABLE `tag_galerie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT pour la table `tag_photo`
--
ALTER TABLE `tag_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
