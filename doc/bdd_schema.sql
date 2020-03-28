-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le :  sam. 28 mars 2020 à 18:04
-- Version du serveur :  5.7.26
-- Version de PHP :  7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `BibliOvox`
--
CREATE DATABASE IF NOT EXISTS `BibliOvox` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `BibliOvox`;

-- --------------------------------------------------------

--
-- Structure de la table `audio`
--

DROP TABLE IF EXISTS `audio`;
CREATE TABLE `audio` (
  `idAudio` int(11) UNSIGNED NOT NULL COMMENT 'ID de l''audio',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur propriétaire',
  `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de la création',
  `chemin` varchar(255) NOT NULL COMMENT 'Chemin menant au fichier audio',
  `commentaire` varchar(265) DEFAULT NULL COMMENT 'Commentaire de l''audio'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audioMot`
--

DROP TABLE IF EXISTS `audioMot`;
CREATE TABLE `audioMot` (
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `idM` int(11) NOT NULL COMMENT 'ID du mot',
  `partage` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audioRec`
--

DROP TABLE IF EXISTS `audioRec`;
CREATE TABLE `audioRec` (
  `idR` int(10) UNSIGNED NOT NULL COMMENT 'ID du Recueil',
  `idU` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''utilisateur',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `partage` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE `classe` (
  `idC` int(10) UNSIGNED NOT NULL COMMENT 'ID de la classe',
  `nom` varchar(255) NOT NULL COMMENT 'Nom de la classe',
  `idUEnseignant` int(10) UNSIGNED NOT NULL COMMENT 'ID utilisateur de l''enseignant',
  `annee` int(10) UNSIGNED NOT NULL COMMENT 'Année de la classe'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dicoContient`
--

DROP TABLE IF EXISTS `dicoContient`;
CREATE TABLE `dicoContient` (
  `idD` int(10) UNSIGNED NOT NULL COMMENT 'ID du dictionnaire',
  `idM` int(10) UNSIGNED NOT NULL COMMENT 'ID du mot'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dictionnaire`
--

DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL COMMENT 'ID du dictionnaire',
  `nomD` varchar(255) NOT NULL COMMENT 'Nom du dictionnaire',
  `descriptionD` text COMMENT 'Description du dictionnaire',
  `imageD` varchar(255) NOT NULL COMMENT 'Nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE `eleve` (
  `idC` int(11) NOT NULL COMMENT 'ID de la classe',
  `idU` int(11) NOT NULL COMMENT 'ID de l''utilisateur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade` (
  `idG` int(1) UNSIGNED NOT NULL COMMENT 'ID du grade',
  `type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `mot`
--

DROP TABLE IF EXISTS `mot`;
CREATE TABLE `mot` (
  `idM` int(10) UNSIGNED NOT NULL COMMENT 'ID du mot',
  `texte` varchar(255) NOT NULL COMMENT 'Mot en lettre',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE `production` (
  `idP` int(11) NOT NULL COMMENT 'ID de la production',
  `idAudio` int(11) NOT NULL COMMENT 'ID de l''audio',
  `nomP` varchar(255) NOT NULL COMMENT 'Nom de la production',
  `dateP` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création de la production'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recueil`
--

DROP TABLE IF EXISTS `recueil`;
CREATE TABLE `recueil` (
  `idR` int(10) UNSIGNED NOT NULL COMMENT 'ID du Recueil',
  `nomR` varchar(255) NOT NULL COMMENT 'Nom',
  `descriptionR` text COMMENT 'Description',
  `dateR` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de création'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `tuteur`
--

DROP TABLE IF EXISTS `tuteur`;
CREATE TABLE `tuteur` (
  `idUEnfant` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''enfant',
  `idUTuteur` int(10) UNSIGNED NOT NULL COMMENT 'ID du tuteur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL COMMENT 'ID de l''utilisateur',
  `nom` varchar(255) NOT NULL COMMENT 'Nom',
  `prenom` varchar(255) NOT NULL COMMENT 'Prenom',
  `password` varchar(255) NOT NULL COMMENT 'Mot de passe salé',
  `salt` varchar(255) NOT NULL COMMENT 'Sel du mot de passe',
  `mail` varchar(255) DEFAULT NULL COMMENT 'Adresse mail',
  `idG` int(10) UNSIGNED NOT NULL COMMENT 'ID du grade associé',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `audio`
--
ALTER TABLE `audio`
  ADD PRIMARY KEY (`idAudio`),
  ADD KEY `idU` (`idU`);

--
-- Index pour la table `audioMot`
--
ALTER TABLE `audioMot`
  ADD PRIMARY KEY (`idAudio`,`idM`),
  ADD KEY `idAudio` (`idAudio`,`idM`);

--
-- Index pour la table `audioRec`
--
ALTER TABLE `audioRec`
  ADD PRIMARY KEY (`idR`,`idU`,`idAudio`),
  ADD KEY `idU` (`idU`),
  ADD KEY `idAudio` (`idAudio`),
  ADD KEY `idR` (`idR`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`idC`),
  ADD KEY `idUEnseignant` (`idUEnseignant`);

--
-- Index pour la table `dicoContient`
--
ALTER TABLE `dicoContient`
  ADD PRIMARY KEY (`idD`,`idM`),
  ADD KEY `idM` (`idM`),
  ADD KEY `idD` (`idD`);

--
-- Index pour la table `dictionnaire`
--
ALTER TABLE `dictionnaire`
  ADD PRIMARY KEY (`idD`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`idC`,`idU`),
  ADD KEY `idC` (`idC`,`idU`);

--
-- Index pour la table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`idG`);

--
-- Index pour la table `mot`
--
ALTER TABLE `mot`
  ADD PRIMARY KEY (`idM`);

--
-- Index pour la table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`idP`,`idAudio`),
  ADD KEY `idAudio` (`idAudio`);

--
-- Index pour la table `recueil`
--
ALTER TABLE `recueil`
  ADD PRIMARY KEY (`idR`);

--
-- Index pour la table `tuteur`
--
ALTER TABLE `tuteur`
  ADD PRIMARY KEY (`idUEnfant`,`idUTuteur`),
  ADD KEY `idUTuteur` (`idUTuteur`,`idUEnfant`) USING BTREE;

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idU`),
  ADD KEY `idG` (`idG`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `audio`
--
ALTER TABLE `audio`
  MODIFY `idAudio` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''audio';

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de la classe';

--
-- AUTO_INCREMENT pour la table `dictionnaire`
--
ALTER TABLE `dictionnaire`
  MODIFY `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du dictionnaire';

--
-- AUTO_INCREMENT pour la table `mot`
--
ALTER TABLE `mot`
  MODIFY `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du mot';

--
-- AUTO_INCREMENT pour la table `production`
--
ALTER TABLE `production`
  MODIFY `idP` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la production';

--
-- AUTO_INCREMENT pour la table `recueil`
--
ALTER TABLE `recueil`
  MODIFY `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID du Recueil';

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID de l''utilisateur';

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
