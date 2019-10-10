-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 11, 2019 at 05:10 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `forumdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `cat_id` int(8) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_description` varchar(255) NOT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `cat_name_unique` (`cat_name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_description`) VALUES
(1, 'Jeux vidéo', 'forum de discussion sur divers jeux vidéo'),
(3, 'Chats', 'Discutez de vos chats!'),
(5, 'Plantes', 'Pour les amoureux de la nature'),
(9, 'Divers', 'Pour parler de tout et rien '),
(10, 'Papotages du soirs', 'l\'ennui du soir');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `msg_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_from` int(11) NOT NULL DEFAULT '0',
  `id_to` int(11) NOT NULL DEFAULT '0',
  `msg_date` datetime NOT NULL,
  `title` text NOT NULL,
  `message` text NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`msg_id`),
  KEY `id_from` (`id_from`),
  KEY `id_to` (`id_to`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`msg_id`, `id_from`, `id_to`, `msg_date`, `title`, `message`, `opened`) VALUES
(1, 9, 3, '2019-06-08 06:31:54', 'bonjour', 'message de test', 1),
(2, 9, 3, '2019-06-08 06:32:07', 'bonjour', 'message test', 1),
(3, 9, 3, '2019-06-08 06:32:39', 'bonjour', 'message test', 1),
(5, 6, 9, '2019-06-08 08:33:54', 'Bonjour2', 'deuxième test', 1),
(7, 4, 9, '2019-06-08 09:27:09', 'test  message 2', 'salut 2', 1),
(8, 6, 9, '2019-06-08 09:27:44', 'test5 msg', 'dernier msg', 1),
(9, 9, 3, '2019-06-08 11:03:09', 'Salut', 'erhtrht', 1),
(10, 9, 4, '2019-06-08 11:05:45', 'qrgrreh', 'réponse', 1),
(11, 9, 4, '2019-06-08 11:54:10', 'réponse au id 7', 'rep', 1),
(12, 4, 9, '2019-06-10 10:53:12', 'à lire', 'ehwe', 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(8) NOT NULL AUTO_INCREMENT,
  `post_content` text NOT NULL,
  `post_date` datetime NOT NULL,
  `post_topic` int(8) NOT NULL,
  `post_by` int(8) NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `post_topic` (`post_topic`),
  KEY `post_by` (`post_by`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `post_content`, `post_date`, `post_topic`, `post_by`) VALUES
(1, 'parlons de ce jeu', '2019-05-06 09:38:48', 2, 3),
(2, 'Bonsoir, je voulais savoir comment planter des haricots', '2019-05-09 20:31:34', 3, 4),
(4, 'c\'est nul comme jeu je stop tant pis', '2019-05-09 21:37:29', 2, 4),
(6, 'c\'est mieux 4', '2019-05-09 21:41:25', 2, 4),
(7, 'bawe facile je vous pl', '2019-05-09 21:43:20', 2, 4),
(8, 'DÃ©butant cherche des amis pour se lancer dans cette aventure', '2019-05-09 22:00:03', 4, 4),
(9, 'LA GROOOOSSSE WEED BRO', '2019-05-09 22:00:29', 5, 4),
(11, 'Poupousse est malade, help svp', '2019-05-11 14:55:45', 6, 4),
(16, 'Où achetez vous vos chaussettes pref ?', '2019-05-31 05:59:42', 7, 6),
(17, 'L\'administrateur joue aussi à ce jeu, puis il est meilleur', '2019-06-10 11:40:47', 2, 9),
(18, 'J\'adore :p', '2019-06-10 12:50:43', 2, 4);

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

DROP TABLE IF EXISTS `topics`;
CREATE TABLE IF NOT EXISTS `topics` (
  `topic_id` int(8) NOT NULL AUTO_INCREMENT,
  `topic_subject` varchar(255) NOT NULL,
  `topic_date` datetime NOT NULL,
  `topic_cat` int(8) NOT NULL,
  `topic_by` int(8) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `topic_cat` (`topic_cat`),
  KEY `topic_by` (`topic_by`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `topics`
--

INSERT INTO `topics` (`topic_id`, `topic_subject`, `topic_date`, `topic_cat`, `topic_by`) VALUES
(2, 'league of legends', '2019-05-06 09:38:48', 1, 3),
(3, 'Haricots verts', '2019-05-09 20:31:34', 5, 4),
(4, 'World of Warcraft', '2019-05-09 22:00:03', 1, 4),
(5, 'Cannabis', '2019-05-09 22:00:29', 5, 4),
(6, 'Poupousse', '2019-05-11 14:55:45', 3, 4),
(7, 'Les chaussettes', '2019-05-31 05:59:42', 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(30) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `profil_pic` varchar(260) NOT NULL DEFAULT 'default.png',
  `user_date` datetime NOT NULL,
  `user_level` int(8) NOT NULL DEFAULT '0',
  `description` varchar(300) DEFAULT NULL,
  `country` varchar(63) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name_unique` (`user_name`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_pass`, `user_email`, `profil_pic`, `user_date`, `user_level`, `description`, `country`) VALUES
(3, 'mhinzey', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test@test.com', 'default.png', '2019-05-05 11:40:22', 0, 'Utilisateur quelquonc rzgqg', 'Belgium'),
(4, 'test', 'd311b1c8e5fe83187cf2d83c8e080dbcff9fc4ef', 'test@test.com', 'default.png', '2019-05-09 18:13:19', 0, 'profile de test', 'France'),
(6, 'test5', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test5@test.com', 'default.png', '2019-05-31 13:51:50', 0, NULL, 'Albania\r\n						'),
(9, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin@hotmail.com', 'default.png', '2019-06-08 04:42:05', 1, 'Administrateur du forum', 'Belgium'),
(11, 'patate', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test@sgser.com', 'default.png', '2019-06-11 03:02:42', 0, '', 'Afghanistan');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`id_from`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`id_to`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`post_topic`) REFERENCES `topics` (`topic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`post_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
  

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`topic_cat`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`topic_by`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
