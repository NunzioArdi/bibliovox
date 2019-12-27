drop table IF EXISTS eleve;
drop table IF EXISTS classe;
drop table IF EXISTS motPerso;
drop table IF EXISTS audioRec;
drop table IF EXISTS recueil;
drop table IF EXISTS tuteur;
drop table IF EXISTS dicoContient;
drop table IF EXISTS mot;
drop table IF EXISTS dictionnaire;
drop table IF EXISTS production;
drop table IF EXISTS utilisateur;
drop table IF EXISTS grade;

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
  `descriptionD` text DEFAULT NULL,
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

CREATE TABLE `production` (
  `idP` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomP` VARCHAR(255) NOT NULL,
  `idU` int UNSIGNED NOT NULL,
  `commentaire` text DEFAULT NULL,
  `dateP` date NOT NULL,
  `audio` VARCHAR(255) NOT NULL COMMENT 'nom du fichier son',
  PRIMARY KEY (`idP`),
  FOREIGN KEY (`idU`) REFERENCES utilisateur (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `recueil` (
  `idR` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomR` VARCHAR(255) NOT NULL,
  `descriptionR` text DEFAULT NULL,
  `dateR` date NOT NULL,
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `audioRec` (
  `idR` int UNSIGNED NOT NULL,
  `idU` int UNSIGNED NOT NULL,
  `audio` VARCHAR(255) NOT NULL COMMENT 'nom du fichier son',
  PRIMARY KEY (`idR`, `idU`),
  FOREIGN KEY (`idR`) REFERENCES recueil (`idR`),
  FOREIGN KEY (`idU`) REFERENCES utilisateur (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `motPerso` (
  `idU` int UNSIGNED NOT NULL,
  `texte` VARCHAR(255) NOT NULL,
  `audio` VARCHAR(255) NOT NULL COMMENT 'nom du fichier son',
  `commentaire` text DEFAULT NULL,
  PRIMARY KEY (`idU`, `texte`),
  FOREIGN KEY (`idU`) REFERENCES utilisateur (`idU`),
  FOREIGN KEY (`texte`) REFERENCES mot (`texte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `classe` (
  `idC` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(255) NOT NULL,
  `idUEnseignant` int UNSIGNED NOT NULL,
  `annee` int UNSIGNED NOT NULL,
  PRIMARY KEY (`idC`),
  FOREIGN KEY (`idUEnseignant`) REFERENCES utilisateur (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `eleve` (
  `idC` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `idU` int UNSIGNED NOT NULL,
  PRIMARY KEY (`idC`, `idU`),
  FOREIGN KEY (`idU`) REFERENCES utilisateur (`idU`),
  FOREIGN KEY (`idC`) REFERENCES classe (`idC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
