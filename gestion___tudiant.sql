-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 15 juin 2026 à 20:47
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_étudiant`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectation`
--

CREATE TABLE `affectation` (
  `id_affectation` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `annee_scolaire_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `affectation`
--

INSERT INTO `affectation` (`id_affectation`, `id_professeur`, `id_matiere`, `id_classe`, `annee_scolaire_id`) VALUES
(2, 2, 1, 7, 4),
(4, 2, 1, 14, 3);

-- --------------------------------------------------------

--
-- Structure de la table `annee_scolaire`
--

CREATE TABLE `annee_scolaire` (
  `id_annee` int(11) NOT NULL,
  `libelle` varchar(20) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `actif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `annee_scolaire`
--

INSERT INTO `annee_scolaire` (`id_annee`, `libelle`, `date_debut`, `date_fin`, `actif`) VALUES
(1, '2023-2025', '2026-04-17', '2026-05-02', 0),
(3, '2025-2026', NULL, NULL, 0),
(4, 'Licence 1 Informatiq', '2026-04-03', '2026-04-24', 1);

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `id_class` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `niveau` varchar(100) NOT NULL,
  `capacite` int(11) DEFAULT NULL,
  `id_annee` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`id_class`, `code`, `libelle`, `niveau`, `capacite`, `id_annee`) VALUES
(6, 'L2-PHYS', 'Licence 2 Physique', 'L2', 0, 0),
(8, 'L3-MATH', 'Licence 3 Mathématiques', 'L3', NULL, NULL),
(9, 'L3-PHYS', 'Licence 3 Physique', 'L3', NULL, NULL),
(10, 'M1-INFO', 'Master 1 Informatique', 'M1', NULL, NULL),
(11, 'M1-MATH', 'Master 1 Mathématiques', 'M1', NULL, NULL),
(12, 'M1-PHYS', 'Master 1 Physique', 'M1', NULL, NULL),
(13, 'M2-INFO', 'Master 2 Informatique', 'M2', NULL, NULL),
(14, 'M2-MATH', 'Master 2 Mathématiques', 'M2', NULL, NULL),
(15, 'M2-PHYS', 'Master 2 Physique', 'M2', NULL, NULL),
(16, 'L1-INFO', 'Licence 1 Informatique', 'L1', 6, 1);

-- --------------------------------------------------------

--
-- Structure de la table `emploi_du_temps`
--

CREATE TABLE `emploi_du_temps` (
  `id_seance` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `id_professeur` int(11) NOT NULL,
  `jour` enum('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi') NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `salle` varchar(50) DEFAULT NULL,
  `type_cours` enum('cours','td','tp') DEFAULT 'cours',
  `semaine` int(11) DEFAULT NULL,
  `annee_scolaire_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `emploi_du_temps`
--

INSERT INTO `emploi_du_temps` (`id_seance`, `id_classe`, `id_matiere`, `id_professeur`, `jour`, `heure_debut`, `heure_fin`, `salle`, `type_cours`, `semaine`, `annee_scolaire_id`) VALUES
(1, 16, 1, 2, 'Lundi', '08:00:00', '12:00:00', 'SALLE 2', 'cours', NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `telephone` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `sexe` varchar(1) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `matricule`, `nom`, `prenom`, `mail`, `telephone`, `date_naissance`, `sexe`, `adresse`, `photo`, `created_at`) VALUES
(5, 'MAT0005', 'Sarr', 'Cheikh', 'cheikh.sarr@gmail.com', '751234571', '1998-07-25', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(6, 'MAT0006', 'Dialloo', 'Mariama', 'mariama.diallo@gmail.com', '771234572', '2000-09-14', '', '', 'default.png', '2026-04-03 17:34:53'),
(7, 'MAT0007', 'Kane', 'Ousmane', 'ousmane.kane@gmail.com', '781234573', '2001-04-05', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(8, 'MAT0008', 'Sy', 'Aminata', 'aminata.sy@gmail.com', '761234574', '1999-12-22', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(9, 'MAT0009', 'Gueye', 'Babacar', 'babacar.gueye@gmail.com', '701234575', '2002-06-30', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(10, 'MAT0010', 'Faye', 'Khady', 'khady.faye@gmail.com', '751234576', '2000-01-17', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(11, 'MAT0011', 'Toure', 'Moussa', 'moussa.toure@gmail.com', '771234577', '1998-08-09', '', '', 'default.png', '2026-04-03 11:12:06'),
(12, 'MAT0012', 'Sow', 'Aissatou', 'aissatou.sow@gmail.com', '781234578', '2001-03-11', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(16, 'MAT0016', 'Seye', 'Rokhaya', 'rokhaya.seye@gmail.com', '771234582', '1998-06-15', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(17, 'MAT0017', 'Mbaye', 'Modou', 'modou.mbaye@gmail.com', '781234583', '2001-09-23', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(18, 'MAT0018', 'Lo', 'Sokhna', 'sokhna.lo@gmail.com', '761234584', '1999-02-04', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(19, 'MAT0019', 'Seck', 'Pape', 'pape.seck@gmail.com', '701234585', '2002-11-13', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(20, 'MAT0020', 'Niang', 'Mame', 'mame.niang@gmail.com', '751234586', '2000-05-21', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(21, 'MAT0021', 'Dieng', 'Lamine', 'lamine.dieng@gmail.com', '771234587', '1998-03-08', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(22, 'MAT0022', 'Ka', 'Ndeye', 'ndeye.ka@gmail.com', '781234588', '2001-07-26', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(23, 'MAT0023', 'Samb', 'Malick', 'malick.samb@gmail.com', '761234589', '1999-01-16', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(24, 'MAT0024', 'Tall', 'Coumba', 'coumba.tall@gmail.com', '701234590', '2002-10-02', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(25, 'MAT0025', 'Wade', 'Abdou', 'abdou.wade@gmail.com', '751234591', '2000-04-14', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(26, 'MAT0026', 'Ly', 'Aicha', 'aicha.ly@gmail.com', '771234592', '1998-12-05', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(28, 'MAT0028', 'Sakho', 'Nafi', 'nafi.sakho@gmail.com', '761234594', '1999-09-27', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(30, 'MAT0030', 'Thiam', 'Astou', 'astou.thiam@gmail.com', '751234596', '2000-08-24', NULL, NULL, 'default.png', '2026-04-03 11:12:06'),
(31, 'MAT0031', 'khadija ', 'fall', 'fakhady2021@gmail.com', '708861672', '2026-03-26', 'M', 'fann', '2fec7c8c37e802bfbc575e82ebd597c6.jpeg', '2026-04-03 11:12:06'),
(32, 'MAT0032', 'FALL', 'Khadidiatou', 'fakhady2021@gmail.com', '708861672', '2026-03-06', 'F', 'fann', '3731719ce0051abf876dbda6b2d3ce76.jpeg', '2026-04-03 11:12:06'),
(33, 'MAT0033', 'MBALLO', 'Ramatoulaye', 'mrama@gmail.com', '77 788 78 90', '2026-02-28', 'F', 'fann', '5b4eb9bcf84d88fcac30bb2d6663bdbb.jpeg', '2026-04-03 11:12:06'),
(34, 'MAT0034', 'FATOU', 'FAYE', 'fakhady2021@gmail.com', '708861672', '2026-03-10', 'F', 'fann', 'a9de283fc19925d844a1743045e6db44.jpeg', '2026-04-03 11:12:06');

-- --------------------------------------------------------

--
-- Structure de la table `frais_scolaires`
--

CREATE TABLE `frais_scolaires` (
  `id_frais` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `annee_scolaire_id` int(11) NOT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `montant_inscription` decimal(10,2) DEFAULT NULL,
  `mensualite` decimal(10,2) DEFAULT NULL,
  `nb_mensualites` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id_inscription` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `date_inscription` date DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `annee_scolaire_id` int(11) DEFAULT NULL,
  `montant_total` decimal(10,2) DEFAULT 0.00,
  `montant_paye` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id_inscription`, `id_etudiant`, `id_classe`, `date_inscription`, `statut`, `annee_scolaire_id`, `montant_total`, `montant_paye`) VALUES
(1, 6, 6, '2026-04-11', 'actif', NULL, 0.00, 0.00),
(2, 11, 16, '2026-04-25', 'inactif', 1, 0.00, 0.00),
(4, 8, 13, '2026-04-11', 'actif', 4, 0.00, 1000.00),
(5, 9, 13, '2026-04-03', 'actif', 3, 0.00, 0.00),
(6, 5, 6, '2026-04-03', 'actif', 4, 0.00, 0.00),
(7, 6, 16, '2026-04-03', 'actif', 4, 0.00, 0.00),
(8, 10, 6, '2026-04-08', 'actif', 3, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id_matiere` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `coefficient` int(11) DEFAULT 1,
  `volume_horaire` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id_matiere`, `code`, `libelle`, `coefficient`, `volume_horaire`, `description`) VALUES
(1, 'L1-INFO', 'Licence 2 Informatique', 4, 20, '');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id_note` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `annee_scolaire_id` int(11) DEFAULT NULL,
  `note_cc` decimal(5,2) DEFAULT NULL,
  `note_exam` decimal(5,2) DEFAULT NULL,
  `note_finale` decimal(5,2) DEFAULT NULL,
  `date_saisie` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id_note`, `id_etudiant`, `id_matiere`, `id_classe`, `annee_scolaire_id`, `note_cc`, `note_exam`, `note_finale`, `date_saisie`) VALUES
(1, 6, 1, 16, 4, 9.00, 15.50, 12.90, '2026-04-03 18:54:04'),
(2, 6, 1, 15, 3, 12.00, 16.00, 14.40, '2026-04-03 18:55:14');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `id_paiement` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `annee_scolaire_id` int(11) NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `date_paiement` date NOT NULL,
  `mode_paiement` enum('especes','cheque','carte','virement','mobile_money') NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `type_paiement` enum('inscription','mensualite','autre') DEFAULT 'mensualite',
  `mois` varchar(20) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `date_saisie` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `paiement`
--

INSERT INTO `paiement` (`id_paiement`, `id_etudiant`, `id_classe`, `annee_scolaire_id`, `montant`, `date_paiement`, `mode_paiement`, `reference`, `type_paiement`, `mois`, `commentaire`, `date_saisie`) VALUES
(1, 8, 13, 4, 1000.00, '2026-04-03', 'especes', '', 'mensualite', 'Juin', '', '2026-04-03 20:07:07');

-- --------------------------------------------------------

--
-- Structure de la table `presence`
--

CREATE TABLE `presence` (
  `id_presence` int(11) NOT NULL,
  `id_etudiant` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `date_seance` date NOT NULL,
  `present` tinyint(1) DEFAULT 0,
  `justifie` tinyint(1) DEFAULT 0,
  `commentaire` text DEFAULT NULL,
  `date_saisie` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `presence`
--

INSERT INTO `presence` (`id_presence`, `id_etudiant`, `id_classe`, `id_matiere`, `date_seance`, `present`, `justifie`, `commentaire`, `date_saisie`) VALUES
(1, 6, 6, 1, '2026-04-03', 1, 0, NULL, '2026-04-03 19:13:29'),
(2, 6, 16, 1, '2026-04-12', 1, 0, NULL, '2026-04-03 19:45:42');

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `id_professeur` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `specialite` varchar(100) DEFAULT NULL,
  `date_embauche` date DEFAULT NULL,
  `photo` varchar(255) DEFAULT 'default.png',
  `statut` enum('actif','inactif') DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`id_professeur`, `nom`, `prenom`, `email`, `telephone`, `specialite`, `date_embauche`, `photo`, `statut`) VALUES
(2, 'FALL', 'mouhamadou', 'mouhamadou2@gmail.com', '789098876', 'informatique', '2026-05-01', 'default.png', 'actif'),
(3, 'bamba', 'ngom', 'bamba99@gmail.com', '789098987', 'maths', '2026-04-29', '174ac373cc376fd6885bd26a5c9899b2.jpeg', 'actif');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom_utilisateur` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','secretaire','professeur') NOT NULL DEFAULT 'secretaire',
  `nom_complet` varchar(100) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `est_actif` tinyint(1) NOT NULL DEFAULT 1,
  `date_creation` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom_utilisateur`, `email`, `password`, `role`, `nom_complet`, `telephone`, `est_actif`, `date_creation`) VALUES
(1, 'admin', 'admin@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Administrateur Principal', NULL, 1, '2026-04-04 00:54:55'),
(2, 'secretaire', 'secretariat@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'secretaire', 'Secrétaire Général', NULL, 1, '2026-04-04 00:54:55'),
(3, 'professeur', 'prof@ecole.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'professeur', 'Professeur Test', NULL, 1, '2026-04-04 00:54:55');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `affectation`
--
ALTER TABLE `affectation`
  ADD PRIMARY KEY (`id_affectation`),
  ADD KEY `id_professeur` (`id_professeur`),
  ADD KEY `id_matiere` (`id_matiere`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `annee_scolaire`
--
ALTER TABLE `annee_scolaire`
  ADD PRIMARY KEY (`id_annee`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`id_class`);

--
-- Index pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  ADD PRIMARY KEY (`id_seance`),
  ADD UNIQUE KEY `unique_seance` (`id_classe`,`jour`,`heure_debut`,`salle`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `id_matiere` (`id_matiere`),
  ADD KEY `id_professeur` (`id_professeur`),
  ADD KEY `annee_scolaire_id` (`annee_scolaire_id`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `frais_scolaires`
--
ALTER TABLE `frais_scolaires`
  ADD PRIMARY KEY (`id_frais`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `annee_scolaire_id` (`annee_scolaire_id`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id_inscription`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`id_matiere`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id_note`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_matiere` (`id_matiere`),
  ADD KEY `id_classe` (`id_classe`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `annee_scolaire_id` (`annee_scolaire_id`);

--
-- Index pour la table `presence`
--
ALTER TABLE `presence`
  ADD PRIMARY KEY (`id_presence`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_classe` (`id_classe`),
  ADD KEY `id_matiere` (`id_matiere`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`id_professeur`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_nom_utilisateur` (`nom_utilisateur`),
  ADD UNIQUE KEY `uk_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `affectation`
--
ALTER TABLE `affectation`
  MODIFY `id_affectation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `annee_scolaire`
--
ALTER TABLE `annee_scolaire`
  MODIFY `id_annee` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `id_class` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `emploi_du_temps`
--
ALTER TABLE `emploi_du_temps`
  MODIFY `id_seance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `frais_scolaires`
--
ALTER TABLE `frais_scolaires`
  MODIFY `id_frais` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id_inscription` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id_matiere` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id_note` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `presence`
--
ALTER TABLE `presence`
  MODIFY `id_presence` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `professeur`
--
ALTER TABLE `professeur`
  MODIFY `id_professeur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`id`),
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`id_classe`) REFERENCES `classe` (`id_class`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
