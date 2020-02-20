-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  jeu. 20 fév. 2020 à 22:15
-- Version du serveur :  5.5.64-MariaDB
-- Version de PHP :  7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `bernar323u`
--

-- --------------------------------------------------------

--
-- Structure de la table `audio`
--

CREATE TABLE `audio` (
  `idAudio` int(11) NOT NULL,
  `idU` int(11) NOT NULL,
  `dateCreation` date NOT NULL,
  `chemin` varchar(255) NOT NULL,
  `commentaire` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `audioRec`
--

CREATE TABLE `audioRec` (
  `idR` int(10) UNSIGNED NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL,
  `idAudio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `audioRec`
--

INSERT INTO `audioRec` (`idR`, `idU`, `idAudio`) VALUES
(1, 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `idC` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `idUEnseignant` int(10) UNSIGNED NOT NULL,
  `annee` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `dicoContient`
--

CREATE TABLE `dicoContient` (
  `idD` int(10) UNSIGNED NOT NULL,
  `idM` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `dicoContient`
--

INSERT INTO `dicoContient` (`idD`, `idM`) VALUES
(1, 1),
(1, 3),
(1, 4),
(1, 6),
(2, 1),
(2, 2),
(2, 5),
(2, 6);

-- --------------------------------------------------------

--
-- Structure de la table `dictionnaire`
--

CREATE TABLE `dictionnaire` (
  `idD` int(10) UNSIGNED NOT NULL,
  `nomD` varchar(255) NOT NULL,
  `descriptionD` text,
  `imageD` varchar(255) NOT NULL COMMENT 'nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `dictionnaire`
--

INSERT INTO `dictionnaire` (`idD`, `nomD`, `descriptionD`, `imageD`) VALUES
(1, 'Le Moyen Age', 'Mots sur le moyen age et les chateaux, chevaliers, princes & princesses', 'chateau.jpg'),
(2, 'La Ville', 'Les mots de la ville et des villages', '');

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `idC` int(10) UNSIGNED NOT NULL,
  `idU` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

CREATE TABLE `grade` (
  `idG` int(1) UNSIGNED NOT NULL,
  `type` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `grade`
--

INSERT INTO `grade` (`idG`, `type`) VALUES
(1, 'Eleve');

-- --------------------------------------------------------

--
-- Structure de la table `mot`
--

CREATE TABLE `mot` (
  `idM` int(10) UNSIGNED NOT NULL,
  `texte` varchar(255) NOT NULL,
  `idAudio` int(11) NOT NULL COMMENT 'Nom du fichier son',
  `image` varchar(255) NOT NULL COMMENT 'Nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `mot`
--

INSERT INTO `mot` (`idM`, `texte`, `idAudio`, `image`) VALUES
(1, 'Arbre', 0, ''),
(2, 'Bus', 0, ''),
(3, 'Chateau', 0, 'chateau.jpg'),
(4, 'Chevalier', 0, ''),
(5, 'Maison', 0, ''),
(6, 'Route', 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `production`
--

CREATE TABLE `production` (
  `idP` int(11) NOT NULL,
  `idAudio` int(11) NOT NULL,
  `nomP` varchar(255) NOT NULL,
  `dateP` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `recueil`
--

CREATE TABLE `recueil` (
  `idR` int(10) UNSIGNED NOT NULL,
  `nomR` varchar(255) NOT NULL,
  `descriptionR` text,
  `dateR` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `recueil`
--

INSERT INTO `recueil` (`idR`, `nomR`, `descriptionR`, `dateR`) VALUES
(1, 'Première poésie : Demain dès l\'Aubre', 'Demain, dès l’aube, à l’heure où blanchit la campagne,\r\nJe partirai. Vois-tu, je sais que tu m’attends.\r\nJ’irai par la forêt, j’irai par la montagne.\r\nJe ne puis demeurer loin de toi plus longtemps.\r\n\r\nJe marcherai les yeux fixés sur mes pensées,\r\nSans rien voir au dehors, sans entendre aucun bruit,\r\nSeul, inconnu, le dos courbé, les mains croisées,\r\nTriste, et le jour pour moi sera comme la nuit.\r\n\r\nJe ne regarderai ni l’or du soir qui tombe,\r\nNi les voiles au loin descendant vers Harfleur,\r\nEt quand j’arriverai, je mettrai sur ta tombe\r\nUn bouquet de houx vert et de bruyère en fleur.\r\n\r\nVictor Hugo, extrait du recueil «Les Contemplations» (1856)', '2020-01-21'),
(2, 'Pomme de reinette et pomme d\'api', 'C\'est à la halle\r\nQue je m\'installe\r\nC\'est à Paris\r\nQue je vends mes fruits\r\nC\'est à Paris la capitale de France\r\nC\'est à Paris\r\nQue je vends mes fruits.\r\n\r\n(Refrain :)\r\nPomme de reinette et pomme d\'api\r\nD\'api d\'api rouge\r\nPomme de reinette et pomme d\'api\r\nD\'api d\'api gris.\r\n\r\nCache ton poing derrière ton dos\r\nOu je te donne un coup de marteau!', '2020-01-07');

-- --------------------------------------------------------

--
-- Structure de la table `tuteur`
--

CREATE TABLE `tuteur` (
  `idUEnfant` int(10) UNSIGNED NOT NULL,
  `idUTuteur` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idU` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `idG` int(10) UNSIGNED NOT NULL,
  `avatar` varchar(255) DEFAULT NULL COMMENT 'Nom du fichier image'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idU`, `nom`, `prenom`, `password`, `salt`, `mail`, `idG`, `avatar`) VALUES
(1, 'Sanzey', 'Lucas', 'none', 'none', 'none', 1, 'none');

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
  ADD KEY `idM` (`idM`);

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
  ADD KEY `idU` (`idU`);

--
-- Index pour la table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`idG`);

--
-- Index pour la table `mot`
--
ALTER TABLE `mot`
  ADD PRIMARY KEY (`idM`,`idAudio`),
  ADD KEY `idAudio` (`idAudio`);

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
  ADD KEY `idUTuteur` (`idUTuteur`);

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
  MODIFY `idAudio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `dictionnaire`
--
ALTER TABLE `dictionnaire`
  MODIFY `idD` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `idC` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `mot`
--
ALTER TABLE `mot`
  MODIFY `idM` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `production`
--
ALTER TABLE `production`
  MODIFY `idP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `recueil`
--
ALTER TABLE `recueil`
  MODIFY `idR` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idU` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `audioRec`
--
ALTER TABLE `audioRec`
  ADD CONSTRAINT `audiorec_ibfk_1` FOREIGN KEY (`idR`) REFERENCES `recueil` (`idR`),
  ADD CONSTRAINT `audiorec_ibfk_2` FOREIGN KEY (`idU`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `classe`
--
ALTER TABLE `classe`
  ADD CONSTRAINT `classe_ibfk_1` FOREIGN KEY (`idUEnseignant`) REFERENCES `utilisateur` (`idU`);

--
-- Contraintes pour la table `dicoContient`
--
ALTER TABLE `dicoContient`
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
