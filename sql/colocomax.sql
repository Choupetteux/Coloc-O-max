-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 21, 2018 at 11:49 AM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `colocomax`
--

-- --------------------------------------------------------

--
-- Table structure for table `colocations`
--

DROP TABLE IF EXISTS `colocations`;
CREATE TABLE IF NOT EXISTS `colocations` (
  `colocation_id` int(11) NOT NULL AUTO_INCREMENT,
  `colocation_nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_pass` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_creator` int(11) NOT NULL,
  PRIMARY KEY (`colocation_id`),
  UNIQUE KEY `colocation_pass` (`colocation_pass`),
  KEY `FK_Colocations_colocation_creator` (`colocation_creator`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colocations`
--

INSERT INTO `colocations` (`colocation_id`, `colocation_nom`, `adresse`, `ville`, `colocation_pass`, `colocation_creator`) VALUES
(1, 'OfflineTV', NULL, 'Los Angeles', 'WW3-k6i-0W1', 1),
(2, 'ProjetS4', NULL, 'Reims', 'Xzq-3lr-RrL', 5);

-- --------------------------------------------------------

--
-- Table structure for table `factures`
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
-- Table structure for table `paiements`
--

DROP TABLE IF EXISTS `paiements`;
CREATE TABLE IF NOT EXISTS `paiements` (
  `montant` float NOT NULL,
  `raison` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `typePaiement` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
<<<<<<< HEAD
  --`paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `datePaiement` Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP

) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
=======
  `datePaiement` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paiement_id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`paiement_id`),
  KEY `FK_Paiements_utilisateur_id` (`utilisateur_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
>>>>>>> 412b7840a1902b5f5d62d2d0d29247c06007da58

--
-- Dumping data for table `paiements`
--

INSERT INTO `paiements` (`montant`, `raison`, `typePaiement`, `datePaiement`, `paiement_id`, `utilisateur_id`) VALUES
(50, '', 'Avance', '2018-03-21 09:58:05', 13, 6),
(45, '', 'Remboursement', '2018-03-21 09:58:35', 14, 4),
(100, 'Remboursement machine à laver', 'Remboursement', '2018-03-21 09:58:45', 15, 8),
(75, 'Course pour soirée du 16/03/18 ', 'Dépense', '2018-03-21 09:58:56', 16, 4),
(50, 'Demande de Scarra', 'Avance', '2018-03-21 10:02:47', 18, 3),
(130, 'Remboursement Poki, Lily', 'Remboursement', '2018-03-21 10:03:28', 19, 7),
(400, 'Machine à laver (Supprimer sans faire exprès)', 'Dépense', '2018-03-21 10:15:28', 20, 6);

-- --------------------------------------------------------

--
-- Table structure for table `participer`
--

DROP TABLE IF EXISTS `participer`;
CREATE TABLE IF NOT EXISTS `participer` (
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`paiement_id`,`utilisateur_id`),
  KEY `FK_Participer_utilisateur_id` (`utilisateur_id`),
  KEY `FK_Participer_paiement_id` (`paiement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `participer`
--

INSERT INTO `participer` (`type`, `montant`, `paiement_id`, `utilisateur_id`) VALUES
('partegale', 50, 13, 4),
('partegale', 45, 14, 6),
('partegale', 100, 15, 6),
('partegale', 18.75, 16, 3),
('partegale', 18.75, 16, 4),
('partegale', 18.75, 16, 6),
('partegale', 18.75, 16, 8),
('partegale', 50, 18, 7),
('montant', 50, 19, 3),
('montant', 80, 19, 6),
('partegale', 100, 20, 4),
('partegale', 100, 20, 6),
('partegale', 100, 20, 7),
('partegale', 100, 20, 8);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateurs`
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
  `LASTTIMESEEN` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `pseudo` (`pseudo`),
  KEY `FK_Utilisateurs_colocation_id` (`colocation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `utilisateurs`
--

INSERT INTO `utilisateurs` (`utilisateur_id`, `nom`, `prenom`, `date_de_naissance`, `sexe`, `pseudo`, `passwd`, `colocation_id`, `avatar`, `LASTTIMESEEN`) VALUES
(1, 'Ribbi', 'Luigi', NULL, NULL, 'Luigito', '$2y$10$kCiZsf3hA9d9H2BzmYrkjuSd0sGhzlIe665mraLp5hN.W3bd32tEG', NULL, 'placeholder.jpg', NULL),
(2, 'Dielh', 'Lorenza', NULL, NULL, 'mikaiix', '$2y$10$tKa9BwXLyFAWQz5hYUxIb.hZSxzvjVXX8RhUA0f2ATpz7kspIPW3a', NULL, 'placeholder.jpg', NULL),
(3, 'Pichu', 'Lily', '1998-02-19', 'F', 'Lilypichu', '$2y$10$9z/bt.TF/jCGKKL.X4FtB.02kJgJKm01MdniCfcVkpaaXl4NBn4Z.', 1, '4ed78a3f31bde0f919033a242cb38e5f8dcb91414f1c9f0bf0c58ca3b7b10eb8.jpeg', '2018-03-21 09:54:06'),
(4, 'Myster', 'Federico', NULL, 'M', 'Fedmyster', '$2y$10$mFuLX1IeyJL2eDJRiSqPzuDV0R/j4tTkRMTQeeVbVUbe5s/33ccOu', 1, '9883e2142f1d1bade0cbf9fa10c6f96ebd461e366df0c7c929e2d804accda74d.jpeg', '2018-03-21 10:17:06'),
(5, 'Marchand', 'Antoine', NULL, NULL, 'marchand', '$2y$10$6Kbov5YtI50MlS1qaFMzR.5Gh8.badAcUmqNU9YOKkA3AgbVoeRl.', 2, 'placeholder.jpg', NULL),
(6, 'Imane', 'Poki', '1992-02-05', 'F', 'Pokimane', '$2y$10$Je1SscAi6L7GSczvSQYefeibqFTds1sikN/hBiK/.AVN5dNMURiFy', 1, '043906d634f43bee460d079c0e168e2efcdca68507e00277dac2fbe178557825.jpeg', '2018-03-21 11:37:19'),
(7, 'Scar', 'Ra', NULL, NULL, 'Scarra', '$2y$10$hW.koNHM1BI2KoS.8Oe8luAFWJjFUE21pWV1BjCK8rplzKt8aAtV6', 1, '5ec7ca4cfeb70effc27c913ee7c26c84e7bcce0bac1155eb8b71a748dfd4cfdb.jpeg', '2018-03-21 10:12:12'),
(8, 'Chan', 'Rebecca', NULL, NULL, 'Pecca', '$2y$10$f9uTgwtXAClZIhCz8slGEuMrbIJ1mky.vaL5KKcPbBn2s1RQDdAa6', 1, '47187873db9b949200519a48bd969cc8271f872b363bb563adca3663aec34fc6.jpeg', '2018-03-21 09:55:13'),
(9, 'Chan', 'Chris', NULL, NULL, 'ChrisTo', '$2y$10$Gb0XsJ5nxvbA8Tr5D/cLMOIpe.JNrwVLrZfGIpOhcWywGDCwJFl.K', 1, '13704f8eba8a9388ce61d013fd54e60c04d4b8a87ac012d8b6bc0959838fd137.jpeg', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `colocations`
--
ALTER TABLE `colocations`
  ADD CONSTRAINT `FK_Colocations_colocation_creator` FOREIGN KEY (`colocation_creator`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Constraints for table `factures`
--
ALTER TABLE `factures`
  ADD CONSTRAINT `FK_Factures_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Constraints for table `paiements`
--
ALTER TABLE `paiements`
  ADD CONSTRAINT `FK_Paiements_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Constraints for table `participer`
--
ALTER TABLE `participer`
  ADD CONSTRAINT `FK_Participer_paiement_id` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`paiement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_Participer_utilisateur_id` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`utilisateur_id`);

--
-- Constraints for table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `FK_Utilisateurs_colocation_id` FOREIGN KEY (`colocation_id`) REFERENCES `colocations` (`colocation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
