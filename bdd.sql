-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 09, 2020 at 05:14 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `BibliOvox`
--

-- --------------------------------------------------------

--
-- Table structure for table `audioRec`
--

DROP TABLE IF EXISTS `audioRec`;
CREATE TABLE `audioRec` (
  `idR` int(10) UNSIGNED NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL,
  `audio` varchar(255) NOT NULL COMMENT 'nom du fichier son'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `audioRec`
--

INSERT INTO `audioRec` (`idR`, `idU`, `audio`) VALUES
(1, 1, 'demain.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `classe`
--

DROP TABLE IF EXISTS `classe`;
CREATE TABLE `classe` (
  `idC` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `idUEnseignant` int(10) UNSIGNED NOT NULL,
  `annee` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `dicoContient`
--

DROP TABLE IF EXISTS `dicoContient`;
CREATE TABLE `dicoContient` (
  `idD` int(10) UNSIGNED NOT NULL,
  `idM` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dicoContient`
--

INSERT INTO `dicoContient` (`idD`, `idM`) VALUES
(1, 1),
(2, 1),
(2, 2),
(1, 3),
(1, 4),
(2, 5),
(1, 6),
(2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `dictionnaire`
--

DROP TABLE IF EXISTS `dictionnaire`;
CREATE TABLE `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL,
  `nomD` varchar(255) NOT NULL,
  `descriptionD` text,
  `imageD` varchar(255) NOT NULL COMMENT 'nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `dictionnaire`
--

INSERT INTO `dictionnaire` (`idD`, `nomD`, `descriptionD`, `imageD`) VALUES
(1, 'Le Moyen Age', 'Mots sur le moyen age et les chateaux, chevaliers, princes & princesses', 'chateau.jpg'),
(2, 'La Ville', 'Les mots de la ville et des villages', '');

-- --------------------------------------------------------

--
-- Table structure for table `eleve`
--

DROP TABLE IF EXISTS `eleve`;
CREATE TABLE `eleve` (
  `idC` int(10) UNSIGNED NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

DROP TABLE IF EXISTS `grade`;
CREATE TABLE `grade` (
  `idG` int(1) UNSIGNED NOT NULL,
  `type` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`idG`, `type`) VALUES
(1, 'Eleve');

-- --------------------------------------------------------

--
-- Table structure for table `mot`
--

DROP TABLE IF EXISTS `mot`;
CREATE TABLE `mot` (
  `idM` int(10) UNSIGNED NOT NULL,
  `texte` varchar(255) NOT NULL,
  `audio` varchar(255) NOT NULL COMMENT 'nom du fichier son',
  `image` varchar(255) NOT NULL COMMENT 'nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mot`
--

INSERT INTO `mot` (`idM`, `texte`, `audio`, `image`) VALUES
(1, 'arbre', 'arbre.mp3', ''),
(2, 'bus', 'bus.mp3', ''),
(3, 'chateau', 'chateau.mp3', 'chateau.jpg'),
(4, 'chevalier', 'chevalier.mp3', ''),
(5, 'maison', 'maison.mp3', ''),
(6, 'route', 'route.mp3', '');

-- --------------------------------------------------------

--
-- Table structure for table `motPerso`
--

DROP TABLE IF EXISTS `motPerso`;
CREATE TABLE `motPerso` (
  `idU` int(10) UNSIGNED NOT NULL,
  `idM` int(10) UNSIGNED NOT NULL,
  `audio` varchar(255) NOT NULL COMMENT 'nom du fichier son',
  `commentaire` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `production`
--

DROP TABLE IF EXISTS `production`;
CREATE TABLE `production` (
  `idP` int(10) UNSIGNED NOT NULL,
  `nomP` varchar(255) NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL,
  `commentaire` text,
  `dateP` date NOT NULL,
  `audio` varchar(255) NOT NULL COMMENT 'nom du fichier son'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `production`
--

INSERT INTO `production` (`idP`, `nomP`, `idU`, `commentaire`, `dateP`, `audio`) VALUES
(1, 'Prononciation de \"pizza\"', 1, 'Bonne prononciation !\r\nBravo :)', '2020-01-09', 'rec-1-lucas.mp3');

-- --------------------------------------------------------

--
-- Table structure for table `recueil`
--

DROP TABLE IF EXISTS `recueil`;
CREATE TABLE `recueil` (
  `idR` int(10) UNSIGNED NOT NULL,
  `nomR` varchar(255) NOT NULL,
  `descriptionR` text,
  `dateR` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recueil`
--

INSERT INTO `recueil` (`idR`, `nomR`, `descriptionR`, `dateR`) VALUES
(1, 'Première poésie : Demain dès l\'Aubre', 'Demain, dès l’aube, à l’heure où blanchit la campagne,\r\nJe partirai. Vois-tu, je sais que tu m’attends.\r\nJ’irai par la forêt, j’irai par la montagne.\r\nJe ne puis demeurer loin de toi plus longtemps.\r\n\r\nJe marcherai les yeux fixés sur mes pensées,\r\nSans rien voir au dehors, sans entendre aucun bruit,\r\nSeul, inconnu, le dos courbé, les mains croisées,\r\nTriste, et le jour pour moi sera comme la nuit.\r\n\r\nJe ne regarderai ni l’or du soir qui tombe,\r\nNi les voiles au loin descendant vers Harfleur,\r\nEt quand j’arriverai, je mettrai sur ta tombe\r\nUn bouquet de houx vert et de bruyère en fleur.\r\n\r\nVictor Hugo, extrait du recueil «Les Contemplations» (1856)', '2020-01-21'),
(2, 'Pomme de reinette et pomme d\'api', 'C\'est à la halle\r\nQue je m\'installe\r\nC\'est à Paris\r\nQue je vends mes fruits\r\nC\'est à Paris la capitale de France\r\nC\'est à Paris\r\nQue je vends mes fruits.\r\n\r\n(Refrain :)\r\nPomme de reinette et pomme d\'api\r\nD\'api d\'api rouge\r\nPomme de reinette et pomme d\'api\r\nD\'api d\'api gris.\r\n\r\nCache ton poing derrière ton dos\r\nOu je te donne un coup de marteau!', '2020-01-07');

-- --------------------------------------------------------

--
-- Table structure for table `tuteur`
--

DROP TABLE IF EXISTS `tuteur`;
CREATE TABLE `tuteur` (
  `idUEnfant` int(10) UNSIGNED NOT NULL,
  `idUTuteur` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `idG` int(10) UNSIGNED NOT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT 'nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`idU`, `nom`, `prenom`, `password`, `salt`, `mail`, `idG`, `avatar`) VALUES
(1, 'Sanzey', 'Lucas', 'none', 'none', 'none', 1, 'none');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audioRec`
--
ALTER TABLE `audioRec`
  ADD PRIMARY KEY (`idR`,`idU`),
  ADD KEY `idU` (`idU`);

--
-- Indexes for table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`idC`),
  ADD KEY `idUEnseignant` (`idUEnseignant`);

--
-- Indexes for table `dicoContient`
--
ALTER TABLE `dicoContient`
  ADD PRIMARY KEY (`idD`,`idM`),
  ADD KEY `idM` (`idM`);

--
-- Indexes for table `dictionnaire`
--
ALTER TABLE `dictionnaire`
  ADD PRIMARY KEY (`idD`);

--
-- Indexes for table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`idC`,`idU`),
  ADD KEY `idU` (`idU`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`idG`);

--
-- Indexes for table `mot`
--
ALTER TABLE `mot`
  ADD PRIMARY KEY (`idM`);

--
-- Indexes for table `motPerso`
--
ALTER TABLE `motPerso`
  ADD PRIMARY KEY (`idU`,`idM`),
  ADD KEY `idM` (`idM`);

--
-- Indexes for table `production`
--
ALTER TABLE `production`
  ADD PRIMARY KEY (`idP`),
  ADD KEY `idU` (`idU`);

--
-- Indexes for table `recueil`
--
ALTER TABLE `recueil`
  ADD PRIMARY KEY (`idR`);

--
-- Indexes for table `tuteur`
--
ALTER TABLE `tuteur`
  ADD PRIMARY KEY (`idUEnfant`,`idUTuteur`),
  ADD KEY `idUTuteur` (`idUTuteur`);

--
-- Indexes for table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idU`),
  ADD KEY `idG` (`idG`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `classe`
--
ALTER TABLE `classe`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `dictionnaire`
--
ALTER TABLE `dictionnaire`
  MODIFY `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mot`
--
ALTER TABLE `mot`
  MODIFY `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `production`
--
ALTER TABLE `production`
  MODIFY `idP` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `recueil`
--
ALTER TABLE `recueil`
  MODIFY `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audioRec`
--
ALTER TABLE `audioRec`
  ADD CONSTRAINT `audiorec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  ADD CONSTRAINT `audiorec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

--
-- Constraints for table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`);

--
-- Constraints for table `dicoContient`
--
ALTER TABLE `dicoContient`
  ADD CONSTRAINT `dicocontient_ibfk_1` FOREIGN KEY (`idD`) REFERENCES `dictionnaire` (`idD`),
  ADD CONSTRAINT `dicocontient_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`);

--
-- Constraints for table `eleve`
--
ALTER TABLE `eleve`
  ADD CONSTRAINT `eleve_ibfk_1` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`),
  ADD CONSTRAINT `eleve_ibfk_2` FOREIGN KEY (`idC`) REFERENCES `classe` (`idC`);

--
-- Constraints for table `motPerso`
--
ALTER TABLE `motPerso`
  ADD CONSTRAINT `motperso_ibfk_1` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`),
  ADD CONSTRAINT `motperso_ibfk_2` FOREIGN KEY (`idM`) REFERENCES `mot` (`idM`);

--
-- Constraints for table `production`
--
ALTER TABLE `production`
  ADD CONSTRAINT `production_ibfk_1` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

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
