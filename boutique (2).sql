-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 29 juil. 2025 à 20:15
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `produits` text,
  `total` decimal(10,2) DEFAULT NULL,
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `date_commande` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) DEFAULT NULL,
  `description` text,
  `note` decimal(2,1) DEFAULT '4.0',
  `avis` int DEFAULT '0',
  `promo` int DEFAULT '0',
  `date_ajout` date DEFAULT (curdate()),
  `categorie` varchar(50) DEFAULT 'Autre',
  `type_taille` varchar(10) DEFAULT 'alpha',
  `prix` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `taille_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `note`, `avis`, `promo`, `date_ajout`, `categorie`, `type_taille`, `prix`, `image`, `couleur`, `taille_type`) VALUES
(47, 'T-shirt de travail bicolore AMBITION Clique', 'T-shirt de travail bicolore AMBITION Clique, robuste et élégant...', 4.5, 15, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 140.00, 'clique-029376.jpg', NULL, NULL),
(48, 'Gilet de travail matelassé vert foncé Molinel', 'Gilet de travail matelassé vert foncé Molinel, léger, chaud et confortable. Idéal pour les environnements professionnels froids. Conçu pour une utilisation intensive.', 4.0, 6, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 120.00, 'molinel-1150.jpg', NULL, NULL),
(27, 'Pantalon de Cuisine noir mixte MARMITON LMA', 'Pantalon de cuisine noir mixte MARMITON LMA, confortable et résistant. Idéal pour les environnements de restauration. Disponible en tailles numériques de 36 à 60.', 5.0, 9, 0, '2024-07-10', 'Restauration', 'numeric', 200.00, 'lma-marmiton.jpg', NULL, NULL),
(28, 'Veste de Cuisine Mixte NERO Robur', 'Veste de cuisine mixte NERO Robur. Design professionnel, confortable et respirant. Parfaite pour les environnements de restauration. Disponible en plusieurs couleurs vives.', 4.5, 10, 0, '2024-07-10', 'Restauration', 'alpha', 330.00, 'robur-nero-manches-courtes.jpg', NULL, NULL),
(29, 'Veste de cuisine Ripstop® camouflage NERO Robur', 'Veste de cuisine Ripstop® camouflage NERO Robur, ultra résistante et respirante. Parfaite pour les environnements professionnels exigeants. Design militaire moderne et confortable.', 4.5, 19, 6, '2024-07-10', 'Restauration', 'alpha', 436.20, 'robur-nero-mc-camouflage.jpg', NULL, NULL),
(30, 'Pantalon de cuisine TIMEO Robur', 'Pantalon de cuisine TIMEO Robur, confortable et résistant. Idéal pour les environnements de restauration. Disponible en plusieurs couleurs élégantes et sobres.', 5.0, 19, 0, '2024-07-10', 'Restauration', 'numeric', 270.00, 'robur-timeo.jpg', NULL, NULL),
(31, 'Pantalon de cuisine ARENAL Robur', 'Pantalon de cuisine ARENAL Robur, confortable, résistant et élégant. Idéal pour les environnements de restauration. Disponible en plusieurs couleurs sobres et professionnelles.', 5.0, 19, 2, '2024-07-10', 'Restauration', 'numeric', 612.00, 'robur-arenal.jpg', NULL, NULL),
(32, 'Combinaison Professionnelle double fermeture LMA', 'Combinaison professionnelle LMA avec double fermeture. Conçue pour offrir confort et résistance dans les environnements exigeants. Idéale pour le travail industriel ou artisanal. Disponible en plusieurs couleurs robustes et fonctionnelles.', 4.0, 9, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 220.00, 'lma-fusible-crocq-rondelle.jpg', NULL, NULL),
(33, 'Baskets de sécurité homme S3L CREMORNE New Balance', 'Baskets de sécurité homme S3L CREMORNE New Balance, conformes aux normes de protection élevées. Légères, respirantes et antidérapantes. Idéales pour les environnements industriels ou de construction. Disponibles en gris et noir.', 4.5, 5, 0, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 1000.00, 'new-balance-cremorne.jpg', NULL, NULL),
(34, 'Bermuda de travail renforcé LMA', 'Bermuda de travail renforcé LMA, conçu pour les environnements professionnels exigeants. Tissu résistant et confortable, idéal pour le travail quotidien. Disponible en noir et vert gras.', 4.0, 13, 0, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 360.00, 'lma-calcaire.jpg', NULL, NULL),
(35, 'Sweatshirt de Travail mixte BASIC ROUNDNECK Clique', 'Sweatshirt de travail en coton, idéal pour tous les jours. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes et professionnelles.', 4.5, 6, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 180.00, 'clique-basic-roundneck.jpg', NULL, NULL),
(36, 'Pantalon de travail 100% Coton ESSENTIELS Cepovett', 'Pantalon de travail 100% coton ESSENTIELS Cepovett, confortable, résistant et respirant. Idéal pour les environnements professionnels. Disponible en plusieurs couleurs sobres et fonctionnelles.', 4.5, 11, 2, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 132.60, 'cepovett-9027-9062.jpg', NULL, NULL),
(11, 'Polo pro manches longues CLASSIC LINCOLN Clique', 'Polo professionnel manches longues, idéal pour le travail en extérieur. Tissu résistant et respirant, disponible en plusieurs couleurs vives.', 3.5, 9, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 209.99, 'clique-classic-lincoln-ml-028245 (7).jpg', NULL, NULL),
(12, 'Sweat de travail à capuche BASIC HOODY Clique', 'Sweat de travail résistant et confortable avec capuche, idéal pour les environnements professionnels. Tissu épais et respirant, disponible en plusieurs couleurs vives.', 3.5, 8, 5, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 230.00, 'clique-021031 (3).jpg', NULL, NULL),
(13, 'T-Shirt professionnel homme en coton ARGO Herocket', 'T-shirt professionnel en coton résistant et respirant, idéal pour le travail quotidien. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes.', 4.0, 12, 20, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 180.00, 'herock-argo (3).jpg', NULL, NULL),
(14, 'Sweat professionnel zippé CLASSIC Helly Hansen', 'Sweat professionnel zippé résistant et chaud, idéal pour les environnements de travail extérieurs. Coupe ajustée, tissu respirant et thermorégulateur. Disponible en plusieurs couleurs élégantes.', 4.5, 15, 27, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 520.00, 'helly-hansen-79326 (3).jpg', NULL, NULL),
(15, 'Doudoune professionnelle femme recyclée IDAHO Clique', 'Doudoune professionnelle en matériau recyclé, idéale pour les environnements extérieurs. Coupe ajustée et thermorégulatrice. Disponible en plusieurs couleurs élégantes.', 4.5, 1, 4, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 660.00, 'clique-0200977 (2).jpg', NULL, NULL),
(38, 'Casque de chantier ventilé EVO 2 JSP', 'Casque de chantier ventilé EVO 2 JSP, léger, robuste et confortable. Idéal pour les environnements industriels et de construction. Disponible en plusieurs couleurs résistantes et fonctionnelles.', 4.5, 14, 0, '2024-07-10', 'BTP/INDUSTRIE', 'unique', 60.00, 'jsp-evo2.jpg', NULL, NULL),
(39, 'Bermuda de travail peintre bicolore PASTEL LMA', 'Bermuda de travail peintre bicolore PASTEL LMA, léger, confortable et résistant. Idéal pour les travaux de peinture ou les environnements industriels. Disponible en tailles numériques de 36 à 60.', 4.0, 3, 2, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 173.50, 'lma-pastel.jpg', NULL, NULL),
(40, 'Sweatshirt polaire professionnel ATLANTA LMA', 'Sweatshirt polaire professionnel ATLANTA LMA, idéal pour les environnements froids. Tissu doux, chaud et résistant. Parfait pour l\'hiver ou les environnements professionnels. Disponible en gris et noir.', 4.5, 9, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 177.00, 'lma-atlanta.jpg', NULL, NULL),
(41, 'Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett', 'Pantalon multinormes ATEX ACCESS ARMAGHAN Cepovett, conçu pour les environnements dangereux et explosifs. Résistant, confortable et conforme aux normes de sécurité les plus élevées. Disponible en bleu.', 4.0, 11, 3, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 500.00, 'cepovett-9b54-8680.jpg', NULL, NULL),
(42, 'Combinaison de travail double zip LMA', 'Combinaison de travail double zip LMA, robuste et confortable, idéale pour les environnements professionnels. Fermeture pratique et résistante. Disponible en plusieurs couleurs fonctionnelles et sobres.', 4.0, 13, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 330.00, 'lma-fusible-crocq-rondelle.jpg', NULL, NULL),
(43, 'T-shirt haute visibilité classe 2 SUZE Singer Safety', 'T-shirt haute visibilité classe 2 SUZE Singer Safety, conforme aux normes de sécurité élevées. Idéal pour les environnements industriels ou de construction. Disponible en orange et jaune pour une visibilité optimale.', 5.0, 10, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 120.00, 'singer-safety-suze.jpg', NULL, NULL),
(19, 'Sweat professionnel à capuche logotypé Carhartt', 'Sweat professionnel à capuche de la marque Carhartt. Design robuste et élégant, idéal pour les environnements de travail extérieurs. Disponible en plusieurs couleurs résistantes.', 4.0, 6, 31, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 700.00, 'carhartt-100074.jpg', NULL, NULL),
(20, 'Sweat de travail zippé à capuche Carhartt', 'Sweat zippé à capuche robuste et chaud, conçu pour les environnements de travail extérieurs. Coupe ajustée, tissu respirant et thermorégulateur. Disponible en plusieurs couleurs élégantes.', 4.5, 24, 21, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 700.00, 'carhartt-k122 (1).jpg', NULL, NULL),
(21, 'Sweat de travail à col rond Carhartt', 'Sweat de travail robuste et confortable à col rond, conçu pour résister à l\'usure quotidienne. Idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.', 4.5, 7, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 550.00, 'carhartt-k124.jpg', NULL, NULL),
(22, 'Tee shirt professionnel manches longues avec logo Carhartt', 'Tee shirt professionnel manches longues avec logo Carhartt. Tissu résistant et confortable, idéal pour les environnements de travail. Disponible en plusieurs couleurs élégantes.', 4.5, 12, 5, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 300.00, 'carhartt-ek231.jpg', NULL, NULL),
(9, 'Bermuda de travail bicolore LMA', 'Bermuda de travail bicolore LMA, conçu pour offrir confort et durabilité sur les chantiers. Disponible en plusieurs couleurs élégantes adaptées à tous les environnements professionnels.', 3.5, 8, 0, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 150.00, 'lma-fondeur-iridium.jpg', NULL, NULL),
(44, 'Bermuda Haute Visibilité DIGGER Lafont', 'Bermuda haute visibilité DIGGER Lafont, conçu pour les environnements industriels ou de construction. Tissu résistant et léger, avec bandes réfléchissantes pour une sécurité optimale. Disponible en jaune.', 4.5, 5, 0, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 100.00, 'bermuda-lafont-hs1236m5-digger.jpg', NULL, NULL),
(45, 'Pantalon anticoupure classe 1A AUTHENTIC Solidur', 'Pantalon anticoupure classe 1A AUTHENTIC Solidur, conçu pour les environnements dangereux. Protection élevée, confort et résistance optimale. Idéal pour les professionnels de l’industrie ou de la construction. Disponible en gris et rouge.', 4.0, 19, 0, '2024-07-10', 'BTP/INDUSTRIE', 'numeric', 740.00, 'solidur-aupa1a.jpg', NULL, NULL),
(46, 'Tee Shirt pro homme manches longues NOET Herock', 'Tee-shirt professionnel manches longues NOET Herock, conçu pour un usage intensif. Tissu épais, résistant et confortable. Idéal pour les environnements de travail. Disponible en bleu gras, gris et noir.', 4.0, 2, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 168.00, 'herock-noet.jpg', NULL, NULL),
(17, 'Sweatshirt de Travail mixte BASIC ROUNDNECK Clique', 'Sweatshirt de travail en coton, idéal pour tous les jours. Coupe ajustée et confortable. Disponible en plusieurs couleurs élégantes.', 3.5, 24, 10, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 200.00, 'clique-basic-roundneck.jpg', NULL, NULL),
(23, 'Tee shirt de travail manches longues avec logo Carhartt', 'Tee shirt de travail manches longues avec logo Carhartt. Tissu résistant et confortable, idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.', 4.0, 1, 0, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 310.00, 'carhartt-104891 (1).jpg', NULL, NULL),
(24, 'T-shirt de travail avec poche poitrine Carhartt', 'T-shirt de travail avec poche poitrine Carhartt. Tissu résistant et confortable, idéal pour les environnements professionnels. Disponible en plusieurs couleurs élégantes.', 4.5, 5, 7, '2024-07-10', 'BTP/INDUSTRIE', 'alpha', 236.50, 'carhartt-103296.jpg', NULL, NULL),
(37, 'Gilet haute visibilité respirant multipoches MADRID Portwest', 'Gilet haute visibilité respirant multipoches MADRID Portwest, idéal pour les environnements industriels ou de construction. Confortable, léger et très résistant. Disponible en orange et jaune pour une visibilité optimale.', 4.5, 17, 0, '2024-07-10', 'BTP/INDUSTRIE', 'unique', 100.00, 'portwest-madrid.jpg', NULL, NULL),
(51, 'Gants nitrile noirs jetables non poudrés Mutexil', 'Gants nitrile noirs jetables non poudrés, hautement résistants et adaptés aux environnements médicaux et de laboratoire. Protection optimale contre les contaminants. Boîte de 100 pièces.', 4.7, 20, 0, '2025-07-29', 'Santé', 'unique', 60.00, 'Mutexil-8887777.jpg', NULL, NULL),
(52, 'Sabots de sécurité légers en EVA SB NFORZ Nordways', 'Sabots de sécurité SB légers en EVA, idéaux pour les personnels médicaux et laborantins. Très confortables, antidérapants et faciles à nettoyer. Disponibles en plusieurs couleurs et tailles de 36 à 60.', 4.2, 11, 4, '2025-07-29', 'Santé', 'numeric', 590.00, 'nordways-nforz (1).jpg', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `civilite` varchar(10) DEFAULT NULL,
  `nom` varchar(255) NOT NULL,
  `prenom` varchar(255) NOT NULL,
  `societe` varchar(255) DEFAULT NULL,
  `nif` varchar(50) DEFAULT NULL,
  `email` varchar(191) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_naissance` date DEFAULT NULL,
  `role` enum('client','admin') DEFAULT 'client',
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `civilite`, `nom`, `prenom`, `societe`, `nif`, `email`, `mot_de_passe`, `date_naissance`, `role`, `date_inscription`) VALUES
(1, 'homme', 'Aouaj', 'Yassinosse', 'yourpay', '12345', 'aouajyassin@gmail.com', '$2y$10$oHXc/VQBtb/n24RcfgBJKuXbEBTccY3F9Ksdwn0xpREoBm5YvpGfm', '2000-01-21', 'client', '2025-07-18 02:06:51'),
(2, 'homme', 'Mohammed', 'Mohammed', 'jumia', '3434', 'jumia@gmail.com', '$2y$10$rsRCnCtv..UGvlbXCooDq.W62vfE1IutZhJqG9ge1wmW9gfvid4Xe', '1995-03-13', 'client', '2025-07-18 13:01:46');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
