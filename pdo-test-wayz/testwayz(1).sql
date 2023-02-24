-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 24 fév. 2023 à 09:24
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `testwayz`
--

-- --------------------------------------------------------

--
-- Structure de la table `collaborateurs`
--

DROP TABLE IF EXISTS `collaborateurs`;
CREATE TABLE IF NOT EXISTS `collaborateurs` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_projet` int NOT NULL,
  `id_user` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_post` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_posts` (`id_post`),
  KEY `comments_utilisateurs` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `dislikes`
--

DROP TABLE IF EXISTS `dislikes`;
CREATE TABLE IF NOT EXISTS `dislikes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_post` int UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `dislikes_posts` (`id_post`),
  KEY `dislikes_utilisateurs` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `follow`
--

DROP TABLE IF EXISTS `follow`;
CREATE TABLE IF NOT EXISTS `follow` (
  `id` int NOT NULL AUTO_INCREMENT,
  `follower_id` int NOT NULL,
  `followed_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_one` int NOT NULL,
  `user_two` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_one` (`user_one`),
  KEY `user_two` (`user_two`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Structure de la table `friend_request`
--

DROP TABLE IF EXISTS `friend_request`;
CREATE TABLE IF NOT EXISTS `friend_request` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender` int NOT NULL,
  `receiver` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sender` (`sender`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Structure de la table `likes`
--

DROP TABLE IF EXISTS `likes`;
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `liked_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_post` int UNSIGNED NOT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `likes_posts` (`id_post`),
  KEY `likes_utilisateurs` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Structure de la table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `_from` int NOT NULL,
  `_to` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb3;
--
-- Structure de la table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `posted_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id` int NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `posts_utilisateurs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb3;

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE IF NOT EXISTS `projects` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(120) NOT NULL,
  `genre` varchar(120) DEFAULT NULL,
  `cover` varchar(255) DEFAULT NULL,
  `date_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `projects_utilisateurs` (`user`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(100) NOT NULL,
  `nom` varchar(200) DEFAULT NULL,
  `prenom` varchar(200) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `pays` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `bio` text,
  `date_inscription` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `telephone` varchar(200) DEFAULT NULL,
  `token` text NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `online` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`post_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `comments_utilisateurs` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `dislikes`
--
ALTER TABLE `dislikes`
  ADD CONSTRAINT `dislikes_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`post_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `dislikes_utilisateurs` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_one`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`user_two`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `friend_request`
--
ALTER TABLE `friend_request`
  ADD CONSTRAINT `friend_request_ibfk_1` FOREIGN KEY (`sender`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friend_request_ibfk_2` FOREIGN KEY (`receiver`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_posts` FOREIGN KEY (`id_post`) REFERENCES `posts` (`post_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `likes_utilisateurs` FOREIGN KEY (`user_id`) REFERENCES `utilisateurs` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_utilisateurs` FOREIGN KEY (`id`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_utilisateurs` FOREIGN KEY (`user`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
