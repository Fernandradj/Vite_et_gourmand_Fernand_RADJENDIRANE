-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 07 juil. 2026 à 16:26
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `viteetgourmand23_bdd`
CREATE DATABASE viteetgourmand23_bdd_test1;
USE viteetgourmand23_bdd_test1;

-- --------------------------------------------------------

--
-- Structure de la table `allergene`
--

CREATE TABLE `allergene` (
  `Allergene_Id` int(11) NOT NULL,
  `Nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `Avis_Id` int(11) NOT NULL,
  `Commentaire` varchar(255) DEFAULT NULL,
  `Note` int(11) NOT NULL,
  `Statut` enum('Rejeté','En attente de validation','Validé') NOT NULL,
  `Soumis_par` int(11) NOT NULL,
  `Valide_refuse_par` int(11) DEFAULT NULL,
  `Commande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `Numero_commande` int(11) NOT NULL,
  `Nombre_personne` int(11) NOT NULL,
  `Date_commande` date NOT NULL,
  `Date_Heure_livraison` datetime NOT NULL,
  `Prix_commande` decimal(10,2) NOT NULL,
  `Prix_livraison` double(10,2) NOT NULL,
  `Statut` enum('Commandé','Validé','En cours de préparation','Expédié','Annulé','Terminé','En attente de retour matériel') NOT NULL,
  `Pret_materiel` tinyint(1) DEFAULT 0,
  `Restitution_materiel` tinyint(1) DEFAULT 0,
  `Utilisateur_Id` int(11) NOT NULL,
  `Menu_Id` int(11) NOT NULL,
  `Entree_Id` int(11) NOT NULL,
  `Plat_Id` int(11) NOT NULL,
  `Dessert_Id` int(11) NOT NULL,
  `Adresse_livraison` text NOT NULL,
  `Reduction` decimal(10,2) NOT NULL,
  `Prix_totale` decimal(10,2) NOT NULL,
  `Prix_distance_livraison` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `composition`
--

CREATE TABLE `composition` (
  `Menu_Id` int(11) DEFAULT NULL,
  `Produit_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaire`
--

CREATE TABLE `horaire` (
  `Horaire_Id` int(11) NOT NULL,
  `Jour` varchar(10) DEFAULT NULL,
  `Heure_ouverture` time DEFAULT NULL,
  `Heure_fermeture` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `menu`
--

CREATE TABLE `menu` (
  `Menu_Id` int(11) NOT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Nombre_personne_minimum` int(11) DEFAULT NULL,
  `Prix_par_personne` double DEFAULT NULL,
  `Regime` enum('Classique','Vegan','Végétarien','') DEFAULT NULL,
  `Theme` enum('Noël','Pâques','Classique','Evénement') DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `Quantite_restante` int(11) DEFAULT NULL,
  `Conditions` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `nouveau_mot_de_passe`
--

CREATE TABLE `nouveau_mot_de_passe` (
  `Id` int(11) NOT NULL,
  `Token` text DEFAULT NULL,
  `Date_Expiration` date NOT NULL,
  `Utilisateur_Id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `Produit_Id` int(11) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Type` enum('Entrée','Plat','Dessert') NOT NULL,
  `Photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit_allergene`
--

CREATE TABLE `produit_allergene` (
  `Produit_Id` int(11) DEFAULT NULL,
  `Allergene_Id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suivi`
--

CREATE TABLE `suivi` (
  `Suivi_Id` int(11) NOT NULL,
  `Date` datetime NOT NULL,
  `Statut` enum('Commandé','Validé','En cours de préparation','Expédié','Terminé','En attente de retour matériel','Annulé') NOT NULL,
  `Numero_commande` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `Utilisateur_Id` int(11) NOT NULL,
  `Pseudo` varchar(50) NOT NULL,
  `Nom` varchar(50) NOT NULL,
  `Prenom` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Telephone` varchar(50) NOT NULL,
  `Password` varchar(250) NOT NULL,
  `Photo` mediumblob DEFAULT NULL,
  `Adresse` varchar(50) NOT NULL,
  `Ville` varchar(50) NOT NULL,
  `Code_postale` varchar(7) NOT NULL,
  `Date_naissance` date DEFAULT NULL,
  `Statut` enum('Actif','Inactif','Suspendu') NOT NULL,
  `Role` enum('Client','Employé','Administrateur') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `allergene`
--
ALTER TABLE `allergene`
  ADD PRIMARY KEY (`Allergene_Id`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`Avis_Id`),
  ADD KEY `FK_Utilisateur_Avis_Soumis` (`Soumis_par`),
  ADD KEY `FK_Utilisateur_Avis_Valide_Refuse` (`Valide_refuse_par`),
  ADD KEY `FK_Commande_Avis` (`Commande`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`Numero_commande`),
  ADD KEY `FK_Utilisateur_Command` (`Utilisateur_Id`),
  ADD KEY `FK_Command_Menu` (`Menu_Id`),
  ADD KEY `FK_Entree_Id` (`Entree_Id`),
  ADD KEY `FK_Plat_Id` (`Plat_Id`),
  ADD KEY `FK_Dessert_Id` (`Dessert_Id`);

--
-- Index pour la table `composition`
--
ALTER TABLE `composition`
  ADD KEY `FK_Menu_composition` (`Menu_Id`),
  ADD KEY `FK_Produit_composition` (`Produit_Id`);

--
-- Index pour la table `horaire`
--
ALTER TABLE `horaire`
  ADD PRIMARY KEY (`Horaire_Id`);

--
-- Index pour la table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`Menu_Id`);

--
-- Index pour la table `nouveau_mot_de_passe`
--
ALTER TABLE `nouveau_mot_de_passe`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `FK_Utilisateur_mot_passe` (`Utilisateur_Id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`Produit_Id`);

--
-- Index pour la table `produit_allergene`
--
ALTER TABLE `produit_allergene`
  ADD KEY `FK_PA_produit` (`Produit_Id`),
  ADD KEY `FK_PA_allergene` (`Allergene_Id`);

--
-- Index pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD PRIMARY KEY (`Suivi_Id`),
  ADD KEY `FK_Suivi_Commande` (`Numero_commande`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`Utilisateur_Id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `allergene`
--
ALTER TABLE `allergene`
  MODIFY `Allergene_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `Avis_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `Numero_commande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `horaire`
--
ALTER TABLE `horaire`
  MODIFY `Horaire_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `menu`
--
ALTER TABLE `menu`
  MODIFY `Menu_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `nouveau_mot_de_passe`
--
ALTER TABLE `nouveau_mot_de_passe`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `Produit_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `suivi`
--
ALTER TABLE `suivi`
  MODIFY `Suivi_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `Utilisateur_Id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `FK_Commande_Avis` FOREIGN KEY (`Commande`) REFERENCES `commande` (`Numero_commande`),
  ADD CONSTRAINT `FK_Utilisateur_Avis_Soumis` FOREIGN KEY (`Soumis_par`) REFERENCES `utilisateur` (`Utilisateur_Id`),
  ADD CONSTRAINT `FK_Utilisateur_Avis_Valide_Refuse` FOREIGN KEY (`Valide_refuse_par`) REFERENCES `utilisateur` (`Utilisateur_Id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_Command_Menu` FOREIGN KEY (`Menu_Id`) REFERENCES `menu` (`Menu_Id`),
  ADD CONSTRAINT `FK_Dessert_Id` FOREIGN KEY (`Dessert_Id`) REFERENCES `produit` (`Produit_Id`),
  ADD CONSTRAINT `FK_Entree_Id` FOREIGN KEY (`Entree_Id`) REFERENCES `produit` (`Produit_Id`),
  ADD CONSTRAINT `FK_Plat_Id` FOREIGN KEY (`Plat_Id`) REFERENCES `produit` (`Produit_Id`),
  ADD CONSTRAINT `FK_Utilisateur_Command` FOREIGN KEY (`Utilisateur_Id`) REFERENCES `utilisateur` (`Utilisateur_Id`);

--
-- Contraintes pour la table `composition`
--
ALTER TABLE `composition`
  ADD CONSTRAINT `FK_Menu_composition` FOREIGN KEY (`Menu_Id`) REFERENCES `menu` (`Menu_Id`),
  ADD CONSTRAINT `FK_Produit_composition` FOREIGN KEY (`Produit_Id`) REFERENCES `produit` (`Produit_Id`);

--
-- Contraintes pour la table `nouveau_mot_de_passe`
--
ALTER TABLE `nouveau_mot_de_passe`
  ADD CONSTRAINT `FK_Utilisateur_mot_passe` FOREIGN KEY (`Utilisateur_Id`) REFERENCES `utilisateur` (`Utilisateur_Id`);

--
-- Contraintes pour la table `produit_allergene`
--
ALTER TABLE `produit_allergene`
  ADD CONSTRAINT `FK_PA_allergene` FOREIGN KEY (`Allergene_Id`) REFERENCES `allergene` (`Allergene_Id`),
  ADD CONSTRAINT `FK_PA_produit` FOREIGN KEY (`Produit_Id`) REFERENCES `produit` (`Produit_Id`);

--
-- Contraintes pour la table `suivi`
--
ALTER TABLE `suivi`
  ADD CONSTRAINT `FK_Suivi_Commande` FOREIGN KEY (`Numero_commande`) REFERENCES `commande` (`Numero_commande`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
