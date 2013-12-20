-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 20 Décembre 2013 à 11:30
-- Version du serveur: 5.6.14
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `nicody_galerie`
--
CREATE DATABASE IF NOT EXISTS `nicody_galerie` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `nicody_galerie`;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE IF NOT EXISTS `categorie` (
  `idcategorie` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) DEFAULT NULL,
  `idutilisateur` int(11) NOT NULL,
  PRIMARY KEY (`idcategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idcategorie`, `nom`, `idutilisateur`) VALUES
(1, 'Espace', 1),
(3, 'Cupcakes', 1),
(4, 'Chats', 1);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `idimage` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `ordre` int(3) NOT NULL,
  `idcategorie` int(11) NOT NULL,
  PRIMARY KEY (`idimage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`idimage`, `nom`, `description`, `lien`, `ordre`, `idcategorie`) VALUES
(1, 'Monde', 'Le monde en couleur', 'Spaceé.jpg', 1, 1),
(2, 'Cupcakes 1', 'Description 1', 'wallpaper-1432077.jpg', 1, 3),
(3, 'Cupcakes 2', 'Description 2', 'wallpaper-2505278.jpg', 2, 3),
(4, 'Cupcakes 3', 'Description 3', 'wallpaper-2683147.jpg', 3, 3),
(5, 'Cupcakes 4', 'Description 4', 'wallpaper-2804359.jpg', 4, 3),
(6, 'Cupcakes5', 'Description 5', 'wallpaper-2956544.jpg', 5, 3),
(7, 'Chat1', 'Description1', 'wallpaper-61455.jpg', 1, 4),
(8, 'Chat2', 'Description 2', 'wallpaper-572271.jpg', 2, 4),
(9, 'Chat3', 'Description 3', 'wallpaper-939315.jpg', 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `idtag` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) DEFAULT NULL,
  `idimage` int(11) NOT NULL,
  PRIMARY KEY (`idtag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`idtag`, `libelle`, `idimage`) VALUES
(1, 'couleur', 1),
(2, 'Tag1', 2),
(3, 'Tags2', 3),
(4, 'Tags3', 4),
(5, 'Tags4', 5),
(6, 'Test', 6),
(7, 'Test', 6),
(8, 'Test', 6),
(9, 'Tags1', 7),
(10, 'Tags2', 8),
(11, 'Tags3', 9);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idutilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idutilisateur`, `login`, `password`) VALUES
(1, 'test', 'c564752a1e971d9e2d09d9cba750a13c');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
