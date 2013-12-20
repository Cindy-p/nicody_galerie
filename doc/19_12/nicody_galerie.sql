-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 19 Décembre 2013 à 09:34
-- Version du serveur: 5.6.12-log
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
  PRIMARY KEY (`idcategorie`,`idutilisateur`),
  KEY `fk_categorie_utilisateur` (`idutilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Contenu de la table `categorie`
--

INSERT INTO `categorie` (`idcategorie`, `nom`, `idutilisateur`) VALUES
(33, 'Chats', 3),
(34, 'Cupcakes', 3);

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
  PRIMARY KEY (`idimage`,`idcategorie`),
  KEY `fk_image_categorie1` (`idcategorie`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`idimage`, `nom`, `description`, `lien`, `ordre`, `idcategorie`) VALUES
(3, 'Chat 1', 'Description chat 1', 'chat1.jpg', 3, 33),
(4, 'Chat 2', 'Description chat 2', 'chat2.jpg', 4, 33),
(5, 'Cupcakes 1', 'Description cupcakes 1', 'cupcakes1.jpg', 5, 34),
(6, 'Cupcakes 2', 'Description cupcakes 2', 'cupcakes2.jpg', 6, 34),
(7, 'Cupcakes 3', 'Description cupcakes 3', 'cupcakes3.jpg', 7, 34),
(8, 'Cupcakes 4', 'Description cupcakes 4', 'cupcakes4.jpg', 8, 34);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `idtag` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) DEFAULT NULL,
  `idimage` int(11) NOT NULL,
  PRIMARY KEY (`idtag`,`idimage`),
  KEY `fk_Tag_image1` (`idimage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `tag`
--

INSERT INTO `tag` (`idtag`, `libelle`, `idimage`) VALUES
(1, 'tototo', 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf32 NOT NULL,
  PRIMARY KEY (`idutilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idutilisateur`, `login`, `password`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin@nicody.fr'),
(2, 'nicolas', '830d1ab1b2aeeb959613c80ff2c95d9a', 'nicolas.varnier@etu.univ-lyon1.fr'),
(3, 'cindy', '6ed61d4b80bb0f81937b32418e98adca', 'cindy.perat@etu.univ-lyon1.fr');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD CONSTRAINT `fk_categorie_utilisateur` FOREIGN KEY (`idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `fk_image_categorie1` FOREIGN KEY (`idcategorie`) REFERENCES `categorie` (`idcategorie`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tag`
--
ALTER TABLE `tag`
  ADD CONSTRAINT `fk_Tag_image1` FOREIGN KEY (`idimage`) REFERENCES `image` (`idimage`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
