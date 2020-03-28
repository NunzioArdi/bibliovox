-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  jeu. 27 fév. 2020 à 17:33
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
  `idAudio` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''audio',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur propriétaire',
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de la création',
  `chemin` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Chemin menant au fichier audio',
  `commentaire` varchar(265) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Commentaire de l''audio',
  PRIMARY KEY (`idAudio`),
  KEY `idU` (`idU`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audioMot`
--

DROP TABLE IF EXISTS `audioMot`;
CREATE TABLE IF NOT EXISTS `audioMot` (
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `idM` int(11) NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idAudio`,`idM`),
  KEY `idAudio` (`idAudio`,`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audioRec`
--

DROP TABLE IF EXISTS `audioRec`;
CREATE TABLE IF NOT EXISTS `audioRec` (
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
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE IF NOT EXISTS `classe` (
  `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la classe',
  `nom` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom de la classe',
  `idUEnseignant` int(10) UNSIGNED NOT NULL COMMENT 'ID utilisateur de l''enseignant',
  `annee` int(10) UNSIGNED NOT NULL COMMENT 'Année de la classe',
  PRIMARY KEY (`idC`),
  KEY `idUEnseignant` (`idUEnseignant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dicoContient`
--

DROP TABLE IF EXISTS `dicoContient`;
CREATE TABLE IF NOT EXISTS `dicoContient` (
  `idD` int(10) UNSIGNED NOT NULL COMMENT 'ID du dictionnaire',
  `idM` int(10) UNSIGNED NOT NULL COMMENT 'ID du mot',
  PRIMARY KEY (`idD`,`idM`),
  KEY `idM` (`idM`),
  KEY `idD` (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dictionnaire`
--

DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE IF NOT EXISTS `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du dictionnaire',
  `nomD` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom du dictionnaire',
  `descriptionD` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Description du dictionnaire',
  `imageD` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idD`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
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
-- Structure de la table `mot`
--

DROP TABLE IF EXISTS `mot`;
CREATE TABLE IF NOT EXISTS `mot` (
  `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du mot',
  `texte` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Mot en lettre',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idM`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE IF NOT EXISTS `production` (
  `idP` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la production',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `nomP` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom de la production',
  `dateP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création de la production',
  PRIMARY KEY (`idP`,`idAudio`),
  KEY `idAudio` (`idAudio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recueil`
--

DROP TABLE IF EXISTS `recueil`;
CREATE TABLE IF NOT EXISTS `recueil` (
  `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du Recueil',
  `nomR` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom',
  `descriptionR` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT 'Description',
  `dateR` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création',
  PRIMARY KEY (`idR`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tuteur`
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
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''utilisateur',
  `nom` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Nom',
  `prenom` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Prenom',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Mot de passe salé',
  `mail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Adresse mail',
  `idG` int(10) UNSIGNED NOT NULL COMMENT 'ID du grade associé',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image',
  PRIMARY KEY (`idU`),
  KEY `idG` (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE IF NOT EXISTS `grade` (
  `idG` int(1) UNSIGNED NOT NULL COMMENT 'ID du grade',
  `type` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`idG`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `audioRec`
--
ALTER TABLE `audioRec`
  ADD CONSTRAINT `audioRec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  ADD CONSTRAINT `audioRec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `dicoContient`
--
ALTER TABLE `dicoContient`
  ADD CONSTRAINT `dicoContient_ibfk_1` FOREIGN KEY (`idD`) REFERENCES `dictionnaire` (`idD`),
  ADD CONSTRAINT `dicoContient_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`);

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
