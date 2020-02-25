-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  mar. 25 fév. 2020 à 16:02
-- Version du serveur :  8.0.18
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bibliovox`
--

-- --------------------------------------------------------

--
-- Structure de la table `audio`
--

DROP TABLE IF EXISTS `audio`;
CREATE TABLE IF NOT EXISTS `audio` (
  `idAudio` int(11) NOT NULL AUTO_INCREMENT,
  `idU` int(11) NOT NULL,
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `chemin` varchar(255) NOT NULL,
  `commentaire` varchar(265) DEFAULT NULL,
  PRIMARY KEY (`idAudio`),
  KEY `idU` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audiorec`
--

DROP TABLE IF EXISTS `audiorec`;
CREATE TABLE IF NOT EXISTS `audiorec` (
  `idR` int(10) UNSIGNED NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL,
  `idAudio` int(11) NOT NULL,
  PRIMARY KEY (`idR`,`idU`,`idAudio`),
  KEY `idU` (`idU`),
  KEY `idAudio` (`idAudio`),
  KEY `idR` (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `idUEnseignant` int(10) UNSIGNED NOT NULL,
  `annee` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idC`),
  KEY `idUEnseignant` (`idUEnseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dicocontient`
--

DROP TABLE IF EXISTS `dicocontient`;
CREATE TABLE IF NOT EXISTS `dicocontient` (
  `idD` int(10) UNSIGNED NOT NULL,
  `idM` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idD`,`idM`),
  KEY `idM` (`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dictionnaire`
--

DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE IF NOT EXISTS `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomD` varchar(255) NOT NULL,
  `descriptionD` text,
  `imageD` varchar(255) NOT NULL COMMENT 'nom du fichier image',
  PRIMARY KEY (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `idU` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idC`,`idU`),
  KEY `idU` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `idG` int(1) UNSIGNED NOT NULL,
  `type` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mot`
--

DROP TABLE IF EXISTS `mot`;
CREATE TABLE IF NOT EXISTS `mot` (
  `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `texte` varchar(255) NOT NULL,
  `idAudio` int(11) NOT NULL COMMENT 'Nom du fichier son',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idM`,`idAudio`),
  KEY `idAudio` (`idAudio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE IF NOT EXISTS `production` (
  `idP` int(11) NOT NULL AUTO_INCREMENT,
  `idAudio` int(11) NOT NULL,
  `nomP` varchar(255) NOT NULL,
  `dateP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idP`,`idAudio`),
  KEY `idAudio` (`idAudio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recueil`
--

DROP TABLE IF EXISTS `recueil`;
CREATE TABLE IF NOT EXISTS `recueil` (
  `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomR` varchar(255) NOT NULL,
  `descriptionR` text,
  `dateR` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tuteur`
--

DROP TABLE IF EXISTS `tuteur`;
CREATE TABLE IF NOT EXISTS `tuteur` (
  `idUEnfant` int(10) UNSIGNED NOT NULL,
  `idUTuteur` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idUEnfant`,`idUTuteur`),
  KEY `idUTuteur` (`idUTuteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `idG` int(10) UNSIGNED NOT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idU`),
  KEY `idG` (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `audiorec`
--
ALTER TABLE `audiorec`
  ADD CONSTRAINT `audiorec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  ADD CONSTRAINT `audiorec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `dicocontient`
--
ALTER TABLE `dicocontient`
  ADD CONSTRAINT `dicocontient_ibfk_1` FOREIGN KEY (`idD`) REFERENCES `dictionnaire` (`idD`),
  ADD CONSTRAINT `dicocontient_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`);

--
-- Contraintes pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD CONSTRAINT `eleve_ibfk_1` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`),
  ADD CONSTRAINT `eleve_ibfk_2` FOREIGN KEY (`idC`) REFERENCES `classe` (`idC`);

--
-- Contraintes pour la table `tuteur`
--
ALTER TABLE `tuteur`
  ADD CONSTRAINT `tuteur_ibfk_1` FOREIGN KEY (`idUEnfant`) REFERENCES `utilisateur` (`idU`),
  ADD CONSTRAINT `tuteur_ibfk_2` FOREIGN KEY (`idUTuteur`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idG`) REFERENCES `grade` (`idG`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
