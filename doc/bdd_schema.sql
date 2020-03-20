-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 20, 2020 at 10:50 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `BibliOvox`
--

-- --------------------------------------------------------

--
-- Table structure for table `audio`
--

DROP TABLE IF EXISTS `audio`;
CREATE TABLE IF NOT EXISTS `audio` (
  `idAudio` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''audio',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur propriétaire',
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de la création',
  `chemin` varchar(255) NOT NULL COMMENT 'Chemin menant au fichier audio',
  `commentaire` varchar(265) DEFAULT NULL COMMENT 'Commentaire de l''audio',
  PRIMARY KEY (`idAudio`),
  KEY `idU` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `audiomot`
--

DROP TABLE IF EXISTS `audiomot`;
CREATE TABLE IF NOT EXISTS `audiomot` (
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `idM` int(11) NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idAudio`,`idM`),
  KEY `idAudio` (`idAudio`,`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `audiorec`
--

DROP TABLE IF EXISTS `audiorec`;
CREATE TABLE IF NOT EXISTS `audiorec` (
  `idR` int(10) UNSIGNED NOT NULL COMMENT 'ID du Recueil',
  `idU` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''utilisateur',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `partage` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`idR`,`idU`,`idAudio`),
  KEY `idU` (`idU`),
  KEY `idAudio` (`idAudio`),
  KEY `idR` (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la classe',
  `nom` varchar(255) NOT NULL COMMENT 'Nom de la classe',
  `idUEnseignant` int(10) UNSIGNED NOT NULL COMMENT 'ID utilisateur de l''enseignant',
  `annee` int(10) UNSIGNED NOT NULL COMMENT 'Année de la classe',
  PRIMARY KEY (`idC`),
  KEY `idUEnseignant` (`idUEnseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dicocontient`
--

DROP TABLE IF EXISTS `dicocontient`;
CREATE TABLE IF NOT EXISTS `dicocontient` (
  `idD` int(10) UNSIGNED NOT NULL COMMENT 'ID du dictionnaire',
  `idM` int(10) UNSIGNED NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idD`,`idM`),
  KEY `idM` (`idM`),
  KEY `idD` (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dictionnaire`
--

DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE IF NOT EXISTS `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du dictionnaire',
  `nomD` varchar(255) NOT NULL COMMENT 'Nom du dictionnaire',
  `descriptionD` text COMMENT 'Description du dictionnaire',
  `imageD` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE IF NOT EXISTS `eleve` (
  `idC` int(11) NOT NULL COMMENT 'ID de la classe',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur',
  PRIMARY KEY (`idC`,`idU`),
  KEY `idC` (`idC`,`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `idG` int(1) UNSIGNED NOT NULL COMMENT 'ID du grade',
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `mot`
--

DROP TABLE IF EXISTS `mot`;
CREATE TABLE IF NOT EXISTS `mot` (
  `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du mot',
  `texte` varchar(255) NOT NULL COMMENT 'Mot en lettre',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE IF NOT EXISTS `production` (
  `idP` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la production',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `nomP` varchar(255) NOT NULL COMMENT 'Nom de la production',
  `dateP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création de la production',
  PRIMARY KEY (`idP`,`idAudio`),
  KEY `idAudio` (`idAudio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recueil`
--

DROP TABLE IF EXISTS `recueil`;
CREATE TABLE IF NOT EXISTS `recueil` (
  `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du Recueil',
  `nomR` varchar(255) NOT NULL COMMENT 'Nom',
  `descriptionR` text COMMENT 'Description',
  `dateR` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tuteur`
--

DROP TABLE IF EXISTS `tuteur`;
CREATE TABLE IF NOT EXISTS `tuteur` (
  `idUEnfant` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''enfant',
  `idUTuteur` int(10) UNSIGNED NOT NULL COMMENT 'ID du tuteur',
  PRIMARY KEY (`idUEnfant`,`idUTuteur`),
  KEY `idUTuteur` (`idUTuteur`,`idUEnfant`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''utilisateur',
  `nom` varchar(255) NOT NULL COMMENT 'Nom',
  `prenom` varchar(255) NOT NULL COMMENT 'Prenom',
  `password` varchar(255) NOT NULL COMMENT 'Mot de passe salé',
  `salt` varchar(255) NOT NULL COMMENT 'Sel du mot de passe',
  `mail` varchar(255) DEFAULT NULL COMMENT 'Adresse mail',
  `idG` int(10) UNSIGNED NOT NULL COMMENT 'ID du grade associé',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idU`),
  KEY `idG` (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audiorec`
--
ALTER TABLE `audiorec`
  ADD CONSTRAINT `audiorec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  ADD CONSTRAINT `audiorec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

--
-- Constraints for table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`);

--
-- Constraints for table `dicocontient`
--
ALTER TABLE `dicocontient`
  ADD CONSTRAINT `dicocontient_ibfk_1` FOREIGN KEY (`idD`) REFERENCES `dictionnaire` (`idD`),
  ADD CONSTRAINT `dicocontient_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`);

--
-- Constraints for table `tuteur`
--
ALTER TABLE `tuteur`
  ADD CONSTRAINT `tuteur_ibfk_1` FOREIGN KEY (`idUEnfant`) REFERENCES `utilisateur` (`idU`),
  ADD CONSTRAINT `tuteur_ibfk_2` FOREIGN KEY (`idUTuteur`) REFERENCES `utilisateur` (`idU`);

--
-- Constraints for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idG`) REFERENCES `grade` (`idG`);
