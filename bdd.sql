drop table IF EXISTS tuteur;
drop table IF EXISTS utilisateur;
drop table IF EXISTS grade;
drop table IF EXISTS dicoContient;
drop table IF EXISTS mot;
drop table IF EXISTS dictionnaire;

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE TABLE `grade` (
  `idG` int(1) UNSIGNED NOT NULL,
  `type` varchar(15),
  PRIMARY KEY (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `utilisateur` (
  `idU` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `mail` varchar(255),
  `idG` int UNSIGNED NOT NULL, 
  `avatar` varchar(255) DEFAULT NULL COMMENT 'nom du fichier image', 
  PRIMARY KEY (`idU`),
  FOREIGN KEY (`idG`) REFERENCES grade (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `tuteur` (
  `idUEnfant` int UNSIGNED NOT NULL,
  `idUTuteur` int UNSIGNED NOT NULL,
  PRIMARY KEY (`idUEnfant`, `idUTuteur`),
  FOREIGN KEY (`idUEnfant`) REFERENCES utilisateur (`idU`),
  FOREIGN KEY (`idUTuteur`) REFERENCES utilisateur (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `mot` (
  `texte` VARCHAR(255) NOT NULL,
  `audio` VARCHAR(255) NOT NULL COMMENT 'nom du fichier son',
  `image` varchar(255) NOT NULL COMMENT 'nom du fichier image',
  PRIMARY KEY (`texte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dictionnaire` (
  `idD` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomD` VARCHAR(255) NOT NULL,
  `descriptionD` varchar(255) DEFAULT NULL,
  `imageD` VARCHAR(255) NOT NULL COMMENT 'nom du fichier image',
  PRIMARY KEY (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dicoContient` (
  `idD` int UNSIGNED NOT NULL,
  `texte` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`idD`, `texte`),
  FOREIGN KEY (`idD`) REFERENCES dictionnaire (`idD`),
  FOREIGN KEY (`texte`) REFERENCES mot (`texte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
