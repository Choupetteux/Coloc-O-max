-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  jeu. 01 mars 2018 à 16:53
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `colocomax`
--

-- --------------------------------------------------------

--
-- Structure de la table `colocations`
--

DROP TABLE IF EXISTS `colocations`;
CREATE TABLE IF NOT EXISTS `colocations` (
  `colocation_id` int(11) NOT NULL AUTO_INCREMENT,
  `colocation_nom` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_pass` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_creator` int(11) NOT NULL,
  PRIMARY KEY (`colocation_id`),
  UNIQUE KEY `colocation_pass` (`colocation_pass`),
  KEY `FK_Colocations_colocation_creator` (`colocation_creator`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

DROP TABLE IF EXISTS `factures`;
CREATE TABLE IF NOT EXISTS `factures` (
  `facture_id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`facture_id`),
  KEY `FK_Factures_utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `montant` float NOT NULL,
  `raison` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paiement_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`paiement_id`),
  KEY `FK_Paiements_utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

DROP TABLE IF EXISTS `participer`;
CREATE TABLE IF NOT EXISTS `participer` (
  `type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `montant` double NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`paiement_id`,`utilisateur_id`),
  KEY `FK_Participer_utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `sexe` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passwd` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_id` int(11) DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'placeholder.jpg',
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  KEY `FK_Utilisateurs_colocation_id` (`colocation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `colocations`
--
ALTER TABLE `colocations`
  ADD CONSTRAINT `FK_Colocations_colocation_creator` FOREIGN KEY (`colocation_creator`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Contraintes pour la table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `FK_Factures_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Contraintes pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `FK_Paiements_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Contraintes pour la table `participer`
--
ALTER TABLE `participer`
  ADD CONSTRAINT `FK_Participer_paiement_id` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`paiement_id`),
  ADD CONSTRAINT `FK_Participer_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `FK_Utilisateurs_colocation_id` FOREIGN KEY (`colocation_id`) REFERENCES `colocations` (`colocation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
