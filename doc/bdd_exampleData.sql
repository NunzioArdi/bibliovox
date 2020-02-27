-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3308
-- Généré le :  jeu. 27 fév. 2020 à 17:34
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

--
-- Déchargement des données de la table `audio`
--

INSERT INTO `audio` (`idAudio`, `idU`, `dateCreation`, `chemin`, `commentaire`) VALUES
(1, 0, '2020-02-26 14:18:36', 'media/aud/arbre.mp3', NULL),
(2, 0, '2020-02-26 14:20:36', 'media/aud/bus.mp3', NULL),
(3, 0, '2020-02-26 14:20:36', 'media/aud/chateau.mp3', NULL),
(4, 0, '2020-02-26 14:20:36', 'media/aud/chevalier.mp3', NULL),
(5, 0, '2020-02-26 14:20:36', 'media/aud/demain.mp3', NULL),
(6, 0, '2020-02-26 14:20:36', 'media/aud/maison.mp3', NULL),
(7, 1, '2020-02-26 14:20:36', 'media/aud/rec-1-lucas.mp3', NULL),
(8, 0, '2020-02-26 14:20:36', 'media/aud/route.mp3', NULL);

--
-- Déchargement des données de la table `audiomot`
--

INSERT INTO `audiomot` (`idAudio`, `idM`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4),
(6, 6),
(8, 7);

--
-- Déchargement des données de la table `audiorec`
--

INSERT INTO `audiorec` (`idR`, `idU`, `idAudio`) VALUES
(1, 1, 5);

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`idC`, `nom`, `idUEnseignant`, `annee`) VALUES
(1, 'Primaire 1A', 2, 2020);

--
-- Déchargement des données de la table `dicocontient`
--

INSERT INTO `dicocontient` (`idD`, `idM`) VALUES
(2, 1),
(2, 2),
(1, 3),
(1, 4),
(2, 6),
(1, 7),
(2, 7);

--
-- Déchargement des données de la table `dictionnaire`
--

INSERT INTO `dictionnaire` (`idD`, `nomD`, `descriptionD`, `imageD`) VALUES
(1, 'Moyen-Âge', 'Dictionnaire regroupant le vocabulaire de l\'univers du Moyen-äge.', 'chateau.jpg'),
(2, 'Ville', 'Vocabulaire sur l\'univers urbain.', '');

--
-- Déchargement des données de la table `grade`
--

INSERT INTO `grade` (`idG`, `type`) VALUES
(1, 'Eleve'),
(2, 'Enseignant'),
(3, 'Parent');

--
-- Déchargement des données de la table `mot`
--

INSERT INTO `mot` (`idM`, `texte`, `image`) VALUES
(1, 'Arbre', ''),
(2, 'Bus', ''),
(3, 'Château', 'chateau.jpg'),
(4, 'Chevalier', ''),
(6, 'Maison', ''),
(7, 'Route', '');

--
-- Déchargement des données de la table `production`
--

INSERT INTO `production` (`idP`, `idAudio`, `nomP`, `dateP`) VALUES
(1, 7, 'Pizza', '2020-02-26 15:14:03');

--
-- Déchargement des données de la table `recueil`
--

INSERT INTO `recueil` (`idR`, `nomR`, `descriptionR`, `dateR`) VALUES
(1, 'Demain dès l\'aube', 'Demain, dès l’aube, à l’heure où blanchit la campagne,\r\nJe partirai. Vois-tu, je sais que tu m’attends.\r\nJ’irai par la forêt, j’irai par la montagne.\r\nJe ne puis demeurer loin de toi plus longtemps.\r\n\r\nJe marcherai les yeux fixés sur mes pensées,\r\nSans rien voir au dehors, sans entendre aucun bruit,\r\nSeul, inconnu, le dos courbé, les mains croisées,\r\nTriste, et le jour pour moi sera comme la nuit.\r\n\r\nJe ne regarderai ni l’or du soir qui tombe,\r\nNi les voiles au loin descendant vers Harfleur,\r\nEt quand j’arriverai, je mettrai sur ta tombe\r\nUn bouquet de houx vert et de bruyère en fleur.', '2020-02-26 15:18:40');

--
-- Déchargement des données de la table `tuteur`
--

INSERT INTO `tuteur` (`idUEnfant`, `idUTuteur`) VALUES
(1, 3);

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`idU`, `nom`, `prenom`, `password`, `salt`, `mail`, `idG`, `avatar`) VALUES
(1, 'Loubart', 'Titouan', '637b7b0cc03d8b5b988407fd924fd264', '86309', 'Loubart.Titouan@example.com', 1, NULL),
(2, 'Gérald', 'Bertrand', '637b7b0cc03d8b5b988407fd924fd264', '86309', 'gerald.bertrand@example.com', 2, NULL),
(3, 'Loubart', 'Véronique', '637b7b0cc03d8b5b988407fd924fd264', '86309', NULL, 3, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
