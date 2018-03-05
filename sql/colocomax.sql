-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le :  Dim 04 mars 2018 à 10:13
-- Version du serveur :  10.1.30-MariaDB
-- Version de PHP :  7.2.1

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

CREATE TABLE `colocations` (
  `colocation_id` int(11) NOT NULL,
  `colocation_nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_pass` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_creator` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `colocations`
--

INSERT INTO `colocations` (`colocation_id`, `colocation_nom`, `adresse`, `ville`, `colocation_pass`, `colocation_creator`) VALUES
(1, 'OfflineTV', NULL, 'Los Angeles', 'WW3-k6i-0W1', 1),
(2, 'ProjetS4', NULL, 'Reims', 'Xzq-3lr-RrL', 5);

-- --------------------------------------------------------

--
-- Structure de la table `factures`
--

CREATE TABLE `factures` (
  `facture_id` int(11) NOT NULL,
  `libelle` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  `utilisateur_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiements`
--

CREATE TABLE `paiements` (
  `montant` float NOT NULL,
  `raison` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `typePaiement` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `paiements`
--

INSERT INTO `paiements` (`montant`, `raison`, `typePaiement`, `paiement_id`, `utilisateur_id`) VALUES
(400, 'Machine à laver', 'depense', 3, 6),
(20, '', 'avance', 7, 6),
(35, '', 'remboursement', 8, 6),
(20, '', 'remboursement', 9, 4);

-- --------------------------------------------------------

--
-- Structure de la table `participer`
--

CREATE TABLE `participer` (
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` double NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `participer`
--

INSERT INTO `participer` (`type`, `montant`, `paiement_id`, `utilisateur_id`) VALUES
('partegale', 100, 3, 4),
('partegale', 100, 3, 6),
('partegale', 100, 3, 7),
('partegale', 100, 3, 8),
('partegale', 20, 7, 8),
('partegale', 35, 8, 7),
('partegale', 20, 9, 6);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `utilisateur_id` int(11) NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `sexe` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passwd` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colocation_id` int(11) DEFAULT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'placeholder.jpg',
  `LASTTIMESEEN` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`utilisateur_id`, `nom`, `prenom`, `date_de_naissance`, `sexe`, `pseudo`, `passwd`, `colocation_id`, `avatar`, `LASTTIMESEEN`) VALUES
(1, 'Ribbi', 'Luigi', NULL, NULL, 'Luigito', '$2y$10$kCiZsf3hA9d9H2BzmYrkjuSd0sGhzlIe665mraLp5hN.W3bd32tEG', NULL, 'placeholder.jpg', NULL),
(2, 'Dielh', 'Lorenza', NULL, NULL, 'mikaiix', '$2y$10$tKa9BwXLyFAWQz5hYUxIb.hZSxzvjVXX8RhUA0f2ATpz7kspIPW3a', NULL, 'placeholder.jpg', NULL),
(3, 'Pichu', 'Lily', '1998-02-19', 'F', 'Lilypichu', '$2y$10$9z/bt.TF/jCGKKL.X4FtB.02kJgJKm01MdniCfcVkpaaXl4NBn4Z.', 1, '4ed78a3f31bde0f919033a242cb38e5f8dcb91414f1c9f0bf0c58ca3b7b10eb8.jpeg', '2018-03-03 14:16:16'),
(4, 'Myster', 'Federico', NULL, 'M', 'Fedmyster', '$2y$10$mFuLX1IeyJL2eDJRiSqPzuDV0R/j4tTkRMTQeeVbVUbe5s/33ccOu', 1, '9883e2142f1d1bade0cbf9fa10c6f96ebd461e366df0c7c929e2d804accda74d.jpeg', '2018-03-03 17:06:41'),
(5, 'Marchand', 'Antoine', NULL, NULL, 'marchand', '$2y$10$6Kbov5YtI50MlS1qaFMzR.5Gh8.badAcUmqNU9YOKkA3AgbVoeRl.', 2, 'placeholder.jpg', NULL),
(6, 'Imane', 'Poki', '1992-02-05', 'F', 'Pokimane', '$2y$10$Je1SscAi6L7GSczvSQYefeibqFTds1sikN/hBiK/.AVN5dNMURiFy', 1, '043906d634f43bee460d079c0e168e2efcdca68507e00277dac2fbe178557825.jpeg', '2018-03-03 17:20:18'),
(7, 'Scar', 'Ra', NULL, NULL, 'Scarra', '$2y$10$hW.koNHM1BI2KoS.8Oe8luAFWJjFUE21pWV1BjCK8rplzKt8aAtV6', 1, '5ec7ca4cfeb70effc27c913ee7c26c84e7bcce0bac1155eb8b71a748dfd4cfdb.jpeg', '2018-03-03 14:15:19'),
(8, 'Chan', 'Rebecca', NULL, NULL, 'Pecca', '$2y$10$f9uTgwtXAClZIhCz8slGEuMrbIJ1mky.vaL5KKcPbBn2s1RQDdAa6', 1, '47187873db9b949200519a48bd969cc8271f872b363bb563adca3663aec34fc6.jpeg', NULL),
(9, 'Chan', 'Chris', NULL, NULL, 'ChrisTo', '$2y$10$Gb0XsJ5nxvbA8Tr5D/cLMOIpe.JNrwVLrZfGIpOhcWywGDCwJFl.K', 1, '13704f8eba8a9388ce61d013fd54e60c04d4b8a87ac012d8b6bc0959838fd137.jpeg', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `colocations`
--
ALTER TABLE `colocations`
  ADD PRIMARY KEY (`colocation_id`),
  ADD UNIQUE KEY `colocation_pass` (`colocation_pass`),
  ADD KEY `FK_Colocations_colocation_creator` (`colocation_creator`);

--
-- Index pour la table `factures`
--
ALTER TABLE `factures`
  ADD PRIMARY KEY (`facture_id`),
  ADD KEY `FK_Factures_utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `paiements`
--
ALTER TABLE `paiements`
  ADD PRIMARY KEY (`paiement_id`),
  ADD KEY `FK_Paiements_utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `participer`
--
ALTER TABLE `participer`
  ADD PRIMARY KEY (`paiement_id`,`utilisateur_id`),
  ADD KEY `FK_Participer_utilisateur_id` (`utilisateur_id`),
  ADD KEY `FK_Participer_paiement_id` (`paiement_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD UNIQUE KEY `pseudo` (`pseudo`),
  ADD KEY `FK_Utilisateurs_colocation_id` (`colocation_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `colocations`
--
ALTER TABLE `colocations`
  MODIFY `colocation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `factures`
--
ALTER TABLE `factures`
  MODIFY `facture_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiements`
--
ALTER TABLE `paiements`
  MODIFY `paiement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  ADD CONSTRAINT `FK_Participer_paiement_id` FOREIGN KEY (`paiement_id`) REFERENCES `paiements` (`paiement_id`) ON DELETE CASCADE ON UPDATE CASCADE,
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
