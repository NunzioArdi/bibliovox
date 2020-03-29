-- Adminer 4.7.6 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `audio`;
CREATE TABLE `audio` (
  `idAudio` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID de l''audio',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur propriétaire',
  `dateCreation` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date de la création',
  `chemin` varchar(255) NOT NULL COMMENT 'Chemin menant au fichier audio',
  `commentaire` varchar(265) DEFAULT NULL COMMENT 'Commentaire de l''audio',
  PRIMARY KEY (`idAudio`),
  KEY `idU` (`idU`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `audioMot`;
CREATE TABLE `audioMot` (
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `idM` int(11) NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idAudio`,`idM`),
  KEY `idAudio` (`idAudio`,`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `audioRec`;
CREATE TABLE `audioRec` (
  `idR` int(10) unsigned NOT NULL COMMENT 'ID du Recueil',
  `idU` int(10) unsigned NOT NULL COMMENT 'ID de l''utilisateur',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `partage` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`idR`,`idU`,`idAudio`),
  KEY `idU` (`idU`),
  KEY `idAudio` (`idAudio`),
  KEY `idR` (`idR`),
  CONSTRAINT `audioRec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  CONSTRAINT `audioRec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `classe`;
CREATE TABLE `classe` (
  `idC` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID de la classe',
  `nom` varchar(255) NOT NULL COMMENT 'Nom de la classe',
  `idUEnseignant` int(10) unsigned NOT NULL COMMENT 'ID utilisateur de l''enseignant',
  `annee` int(10) unsigned NOT NULL COMMENT 'Année de la classe',
  PRIMARY KEY (`idC`),
  KEY `idUEnseignant` (`idUEnseignant`),
  CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dicoContient`;
CREATE TABLE `dicoContient` (
  `idD` int(10) unsigned NOT NULL COMMENT 'ID du dictionnaire',
  `idM` int(10) unsigned NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idD`,`idM`),
  KEY `idM` (`idM`),
  KEY `idD` (`idD`),
  CONSTRAINT `dicoContient_ibfk_1` FOREIGN KEY (`idD`) REFERENCES `dictionnaire` (`idD`),
  CONSTRAINT `dicoContient_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE `dictionnaire` (
  `idD` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID du dictionnaire',
  `nomD` varchar(255) NOT NULL COMMENT 'Nom du dictionnaire',
  `descriptionD` text DEFAULT NULL COMMENT 'Description du dictionnaire',
  `imageD` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idD`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `eleve`;
CREATE TABLE `eleve` (
  `idC` int(11) NOT NULL COMMENT 'ID de la classe',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur',
  PRIMARY KEY (`idC`,`idU`),
  KEY `idC` (`idC`,`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade` (
  `idG` int(1) unsigned NOT NULL COMMENT 'ID du grade',
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `mot`;
CREATE TABLE `mot` (
  `idM` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID du mot',
  `texte` varchar(255) NOT NULL COMMENT 'Mot en lettre',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idM`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `production`;
CREATE TABLE `production` (
  `idP` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la production',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `nomP` varchar(255) NOT NULL COMMENT 'Nom de la production',
  `dateP` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date de création de la production',
  PRIMARY KEY (`idP`,`idAudio`),
  KEY `idAudio` (`idAudio`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `recueil`;
CREATE TABLE `recueil` (
  `idR` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID du Recueil',
  `nomR` varchar(255) NOT NULL COMMENT 'Nom',
  `descriptionR` text DEFAULT NULL COMMENT 'Description',
  `dateR` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Date de création',
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tuteur`;
CREATE TABLE `tuteur` (
  `idUEnfant` int(10) unsigned NOT NULL COMMENT 'ID de l''enfant',
  `idUTuteur` int(10) unsigned NOT NULL COMMENT 'ID du tuteur',
  PRIMARY KEY (`idUEnfant`,`idUTuteur`),
  KEY `idUTuteur` (`idUTuteur`,`idUEnfant`) USING BTREE,
  CONSTRAINT `tuteur_ibfk_1` FOREIGN KEY (`idUEnfant`) REFERENCES `utilisateur` (`idU`),
  CONSTRAINT `tuteur_ibfk_2` FOREIGN KEY (`idUTuteur`) REFERENCES `utilisateur` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `idU` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID de l''utilisateur',
  `nom` varchar(255) NOT NULL COMMENT 'Nom',
  `prenom` varchar(255) NOT NULL COMMENT 'Prenom',
  `password` varchar(255) NOT NULL COMMENT 'Mot de passe salé',
  `mail` varchar(255) NOT NULL COMMENT 'Adresse mail',
  `idG` int(10) unsigned NOT NULL COMMENT 'ID du grade associé',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idU`),
  KEY `idG` (`idG`),
  CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`idG`) REFERENCES `grade` (`idG`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;


-- 2020-03-29 16:03:41
