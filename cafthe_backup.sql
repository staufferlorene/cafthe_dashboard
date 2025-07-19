-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20250622.6f986a7ff5
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : Sam. 19 Juil. 2025 à 18:48
-- Version du serveur : 8.0.30
-- Version de PHP : 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cafthe_backup`
--

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `Id_categorie` int NOT NULL,
  `Nom_categorie` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Tva_categorie` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`Id_categorie`, `Nom_categorie`, `Tva_categorie`) VALUES
(1, 'thés', 5.50),
(2, 'cafés', 5.50),
(3, 'accessoires', 20.00);

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `Id_client` int NOT NULL,
  `Nom_client` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom_client` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Telephone_client` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Mail_client` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Mdp_client` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Date_inscription` date NOT NULL,
  `Adresse_client` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`Id_client`, `Nom_client`, `Prenom_client`, `Telephone_client`, `Mail_client`, `Mdp_client`, `Date_inscription`, `Adresse_client`) VALUES
(1, 'Dupont', 'Jean', '0123456789', 'jean.dupont@example.com', 'mdp123', '2025-01-10', '123 Rue de Paris, 75001 Paris'),
(2, 'Martin', 'Sophie', '0987654321', 'sophie@email.com', '$2b$10$ekv9aLJNIbPmtuaFLvpC9uSNZqFGDQTVfX.s.VKU90E6MXGnrT2ca', '2025-01-15', '456 Avenue de Lyon, 69001 Lyon'),
(3, 'Durand', 'Pierre', '0123987654', 'pierre.durand@example.com', 'mdp789', '2025-01-20', '789 Boulevard de Marseille, 13001 Marseille'),
(4, 'Lefevre', 'Marie', '0987123456', 'marie.lefevre@example.com', 'mdp101', '2025-01-25', '321 Rue de Lille, 59000 Lille'),
(5, 'Moreau', 'Luc', '0123678945', 'luc.moreau@example.com', 'mdp202', '2025-01-30', '654 Rue de Nantes, 44000 Nantes'),
(6, 'Petit', 'Emma', '0123456789', 'emma.petit@example.com', 'mdp303', '2025-01-05', '123 Rue de Bordeaux, 33000 Bordeaux'),
(7, 'Roux', 'Louis', '0987654321', 'louis.roux@example.com', 'mdp404', '2025-01-08', '456 Avenue de Toulouse, 31000 Toulouse'),
(8, 'Blanc', 'Chloe', '0123987654', 'chloe.blanc@example.com', 'mdp505', '2025-01-12', '789 Boulevard de Nice, 06000 Nice'),
(9, 'Garnier', 'Hugo', '0000000000', 'test.maj@email.com', 'mdp606', '2025-01-18', 'rue du test 41200 Romo'),
(10, 'Chevalier', 'Alice', '0000000000', 'test.maj@email.com', 'mdp707', '2025-01-22', 'rue du test 41200 Romo'),
(11, 'Muller', 'Lucas', '0123456789', 'lucas.muller@example.com', 'mdp808', '2024-02-10', '123 Rue de Rennes, 35000 Rennes'),
(12, 'Lemoine', 'Julie', '0987654321', 'julie.lemoine@example.com', 'mdp909', '2024-03-15', '456 Avenue de Reims, 51100 Reims'),
(13, 'Dumas', 'Thomas', '0123987654', 'thomas.dumas@example.com', 'mdp010', '2024-04-20', '789 Boulevard de Dijon, 21000 Dijon'),
(14, 'Fournier', 'Sarah', '0000000000', 'tescvxc@email.com', 'mdp111', '2024-05-25', 'cvxc'),
(15, 'Girard', 'Maxime', '0000000000', 'test.maj@email.com', 'mdp222', '2024-06-30', 'rue du test 41200 Romo'),
(16, 'Andre', 'Camille', '0123456789', 'camille.andre@example.com', 'mdp333', '2024-07-05', '123 Rue de Clermont, 63000 Clermont-Ferrand'),
(17, 'Mercier', 'Nicolas', '0987654321', 'nicolas.mercier@example.com', 'mdp444', '2024-08-10', '456 Avenue de Tours, 37000 Tours'),
(18, 'Dupuis', 'Laura', '0123987654', 'laura.dupuis@example.com', 'mdp555', '2024-09-15', '789 Boulevard de Metz, 57000 Metz'),
(19, 'Leroux', 'Antoine', '0987123456', 'antoine.leroux@example.com', 'mdp666', '2024-10-20', '321 Rue de Perpignan, 66000 Perpignan'),
(20, 'Renaud', 'Elise', '0123678945', 'elise.renaud@example.com', 'mdp777', '2024-11-25', '654 Rue de Pau, 64000 Pau'),
(21, 'Doe', 'John', '0000000000', 'test.maj@email.com', '$2b$10$vLaxy8XK2PJt1PgjfRSDqe8wewes/ADPqlJlTtanEHoRifdIf6pui', '2025-02-03', 'rue de la maj 41200 Romo'),
(22, 'Doe2', 'John2', '0612345678', 'john.doe@email.com', '$2b$10$YDCqa5SSJ5nlC9KTVzLDteFKmlRlkdlTO.5pm21yHwSckRhzw.czK', '2025-02-03', 'rue du test 75000 Paris'),
(24, 'Doe3', 'John3', '0612345678', 'john.doe3@email.com', '$2b$10$ovWKwkvbrkGXM1k5m.tqhur6K2NRuDNdIW6xDcD8IHKgAWW7nXPWO', '2025-02-03', 'rue du test 75000 Paris'),
(25, 'Doe', 'John', '0612345678', 'john.doe@email.com', '$2b$10$POr1V5sgyq8M8kKXQDwnuuIC8RCrU01IhXlXxY63BDn3K6jJkmgcu', '2025-02-03', 'rue du test 75000 Paris'),
(26, 'Doe4', 'John4', '0612345678', 'john.doe4@email.com', '$2b$10$iHx5J0mj/NrjP2hyHErI2OzVpxY1auocymi8JsR9yagI662.SqJ3e', '2025-02-03', 'rue du test 75000 Paris'),
(27, 'Doe4', 'John4', '0612345678', 'john.doe5@email.com', '$2b$10$v.WnOahjJzvHaa5qEbHquujjJP244j9h4wyNAKoT1dT6VkUj6xAxC', '2025-02-03', 'rue du test 75000 Paris'),
(28, 'Doe5', 'John5', '0612345678', 'john.doe55@email.com', '$2b$10$dpvyxI/Yil31p758iR9WHurLgrNFPcH99nuadWr4JjBPW7Nj8fO/e', '2025-02-03', 'rue du test 75000 Paris'),
(29, 'Doe5', 'John5', '0612345678', 'john.doe55@email.com', '$2b$10$JWWoGIp6hnLSA2lFwhTRx.NH0j99ctxNg05Kjapof9wy1wtk02kM.', '2025-02-03', 'rue du test 75000 Paris'),
(30, 'Doe5', 'John5', '0612345678', 'john.doe55@email.com', '$2b$10$bdv/H3ZYnIWKzRYzi6RjKOQdpSYKMv5r38sigq6o/uEFHCVmrOOUO', '2025-02-03', 'rue du test 75000 Paris'),
(31, 'Doe6', 'John6', '0611223344', 'john6.does@email.com', '$2b$10$UpFDVUEPDI5gt1JJ3QqituUM9yaKcmZtJlczcJpDdVPA3SFBKQxRK', '2025-10-01', 'rue du test 41000 Romo'),
(32, 'Test', 'Lorene', '05 47 06 08 36', 'lorene@email.com', '$2b$10$gHOMeykF7rY1.lDdvBfho.IyNVPHZs8TaY9gKWDxN7ET9BnUgfTCW', '2025-12-10', 'modif adresse'),
(44, 'test', 'test', '0600000000', 'm@m.fr', '$2b$10$kVaXzYo26kMalANnjbGWFOM9XLQdT.k.2O.aTHvUVdJjIJ4c/sFKa', '2025-03-03', '1'),
(45, 't', 't', 't', 't@f.fr', '$2b$10$boHjH2r9r8hlclOVGJ58N.NM1qqS4eRruDwD2jw3WkHBhXIKnMjXa', '2025-03-03', 't'),
(46, 'h', 'h', 'h', 'h@l.fr', '$2b$10$hXXUM22yAtUYXOQBTPnLYOC0FNP/uTTIQwAeulFidf.SWvfe8i1q2', '2025-03-04', 'h'),
(47, 'bb', 'bb', '0600000000', 'bb@email.com', '$2b$10$oACIK4SyRM.5Tk6WVsLSbe5aNCK5PfDjzRECsdMXR5S6QYd9yfEgy', '2025-04-04', '1 rue test');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `Id_commande` int NOT NULL,
  `Date_commande` date NOT NULL,
  `Statut_commande` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Adresse_livraison` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Montant_commande_HT` decimal(10,2) NOT NULL,
  `Montant_TVA` decimal(10,2) NOT NULL,
  `Montant_commande_TTC` decimal(10,2) NOT NULL,
  `Id_vendeur` int NOT NULL,
  `Id_client` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`Id_commande`, `Date_commande`, `Statut_commande`, `Adresse_livraison`, `Montant_commande_HT`, `Montant_TVA`, `Montant_commande_TTC`, `Id_vendeur`, `Id_client`) VALUES
(1, '2025-01-12', 'en préparation', '123 Rue de Paris, 75001 Paris', 25.00, 5.00, 30.00, 2, 1),
(2, '2025-01-18', 'expédiée', '456 Avenue de Lyon, 69001 Lyon', 15.00, 3.00, 18.00, 2, 2),
(4, '2025-02-10', 'livrée', '321 Rue de Lille, 59000 Lille', 30.00, 6.00, 36.00, 1, 2),
(5, '2025-02-15', 'expédiée', '654 Rue de Nantes, 44000 Nantes', 40.00, 8.00, 48.00, 2, 5),
(6, '2025-02-20', 'livrée', '123 Rue de Bordeaux, 33000 Bordeaux', 50.00, 10.00, 60.00, 1, 6),
(7, '2025-03-05', 'en préparation', '456 Avenue de Toulouse, 31000 Toulouse', 35.00, 7.00, 42.00, 2, 7),
(8, '2025-03-10', 'expédiée', '789 Boulevard de Nice, 06000 Nice', 45.00, 9.00, 54.00, 1, 8),
(9, '2025-03-15', 'livrée', '321 Rue de Strasbourg, 67000 Strasbourg', 55.00, 11.00, 66.00, 2, 9),
(10, '2025-04-01', 'en préparation', '654 Rue de Montpellier, 34000 Montpellier', 60.00, 12.00, 72.00, 1, 10),
(77, '2025-04-04', 'en préparation', 'store', 5.00, 1.00, 6.00, 1, 47);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `Id_ligne_commande` int NOT NULL,
  `Nombre_ligne_commande` int NOT NULL,
  `Quantite_produit_ligne_commande` int NOT NULL,
  `Prix_unitaire_ligne_commande` decimal(10,2) NOT NULL,
  `Id_commande` int NOT NULL,
  `Id_produit` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`Id_ligne_commande`, `Nombre_ligne_commande`, `Quantite_produit_ligne_commande`, `Prix_unitaire_ligne_commande`, `Id_commande`, `Id_produit`) VALUES
(1, 1, 2, 10.00, 1, 1),
(2, 2, 1, 15.00, 1, 2),
(3, 1, 1, 25.00, 2, 3),
(6, 1, 1, 36.00, 4, 4),
(7, 2, 2, 20.00, 5, 5),
(8, 1, 1, 50.00, 6, 6),
(9, 1, 4, 10.00, 7, 1),
(10, 2, 1, 15.00, 8, 2),
(11, 1, 2, 25.00, 9, 3),
(12, 2, 3, 10.00, 10, 1),
(35, 1, 2, 10.00, 1, 1),
(36, 1, 2, 10.00, 1, 1),
(37, 2, 3, 15.00, 1, 2),
(38, 1, 2, 10.00, 1, 1),
(39, 2, 3, 15.00, 1, 2),
(45, 1, 2, 10.00, 1, 1),
(46, 1, 2, 10.00, 1, 1),
(71, 1, 1, 6.00, 77, 3);

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `Id_produit` int NOT NULL,
  `Nom_produit` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Description` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `Prix_HT` decimal(10,2) NOT NULL,
  `Prix_TTC` decimal(10,2) NOT NULL,
  `Stock` int NOT NULL,
  `Type_conditionnement` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Chemin_img` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Id_categorie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`Id_produit`, `Nom_produit`, `Description`, `Prix_HT`, `Prix_TTC`, `Stock`, `Type_conditionnement`, `Chemin_img`, `Id_categorie`) VALUES
(1, 'Thé Vert', 'Délicat et rafraîchissant, le thé vert offre des notes végétales et légèrement astringentes. Riche en antioxydants, il se déguste nature ou parfumé.', 10.00, 10.55, 100, 'unitaire', 'the_vert.webp', 1),
(2, 'Café Arabica', 'Un café doux et raffiné, aux arômes délicats de fleurs et de fruits. Cultivé en altitude, il offre une saveur équilibrée, légèrement acide avec des notes subtiles de chocolat. Parfait pour une expérience gustative douce et harmonieuse à tout moment de la journée.', 15.00, 1.10, 50, 'vrac', 'cafe_arabica.jpeg', 2),
(3, 'Tasse à Thé', 'Élégante et souvent en porcelaine, elle permet de savourer pleinement les arômes du thé.', 5.00, 6.00, 200, 'unitaire', 'tasse_the.webp', 3),
(4, 'Thé Noir', 'Puissant et corsé, notre thé noir de Ceylan dévoile des arômes maltés et épicés. Idéal pour un réveil tonique.\r\n\r\n\r\n', 12.00, 12.66, 80, 'unitaire', 'the_ceylan.webp', 1),
(5, 'Café Robusta', 'Corsé et intense, il se distingue par son amertume prononcée et sa teneur élevée en caféine.', 13.00, 13.72, 60, 'unitaire', 'cafe_robusta.jpeg', 2),
(6, 'Infuseur à Thé', 'Accessoire pratique pour infuser le thé en vrac, évitant les résidus dans la tasse.', 7.00, 8.40, 150, 'unitaire', 'infuseur_the.webp', 3),
(7, 'Thé Blanc', 'Subtil et raffiné, notre thé blanc de Chine peu oxydé révèle des notes florales et douces. Apprécié pour sa légèreté et sa richesse en antioxydants.\r\n\r\n', 20.00, 21.10, 40, 'unitaire', 'the_blanc_chine.webp', 1),
(8, 'Café Décaféiné', 'Tout le plaisir du café sans caféine, idéal pour une dégustation en douceur à tout moment.', 14.00, 1.20, 70, 'vrac', 'cafe_deca.jpeg', 2),
(9, 'Bouilloire', 'Permet de chauffer l’eau rapidement, idéale pour préparer thé et café à la température parfaite.', 25.00, 30.00, 30, 'unitaire', 'bouilloire.webp', 3),
(10, 'Thé Oolong', 'À mi-chemin entre thé vert et noir, il offre des arômes boisés et floraux, avec une légère douceur.', 18.00, 1.10, 50, 'vrac', 'the_oolong.jpeg', 1),
(11, 'Café Espresso', 'Court et intense, il révèle une crema onctueuse et des arômes riches et concentrés.', 16.00, 16.88, 90, 'unitaire', 'cafe_expresso.jpeg', 2),
(12, 'Filtre à Café', 'Indispensable pour une infusion propre, il retient le marc tout en laissant passer les arômes.', 8.00, 9.60, 120, 'unitaire', 'filtre_cafe.webp', 3),
(13, 'Thé Rooibos', 'Sans théine, il dévoile des notes rondes et vanillées, parfait pour une infusion douce et relaxante.', 15.00, 1.20, 60, 'vrac', 'the_rooibos.webp', 1),
(14, 'Café Moka', 'Issu d’Éthiopie ou préparé en cafetière italienne, il offre des notes fruitées et chocolatées.', 17.00, 17.94, 55, 'unitaire', 'cafe_moka.jpeg', 2),
(15, 'Théière', 'Traditionnelle et élégante, elle conserve parfaitement la chaleur pour une infusion optimale. Son matériau robuste améliore les arômes du thé au fil du temps.', 30.00, 36.00, 25, 'unitaire', 'theiere.webp', 3),
(16, 'Thé Matcha', 'Thé vert en poudre intense, au goût umami et végétal, riche en antioxydants.\r\n\r\n', 22.00, 1.30, 35, 'vrac', 'the_matcha.webp', 1),
(17, 'Café Lungo', 'Café allongé et doux, plus léger qu’un espresso mais conservant une belle intensité.', 18.00, 1.30, 65, 'vrac', 'cafe_lungo.jpeg', 2),
(18, 'Mug', 'Solide et isolant, il maintient la chaleur de votre boisson tout en offrant un design varié et une prise en main agréable.', 10.00, 12.00, 110, 'unitaire', 'mug.webp', 3),
(19, 'Thé Jasmin', 'Thé vert délicatement parfumé aux fleurs de jasmin, offrant une infusion florale et apaisante.', 14.00, 1.20, 75, 'vrac', 'the_jasmin.webp', 1),
(20, 'Café Blue Mountain', 'Grand cru de Jamaïque, il séduit par sa douceur, son équilibre et ses notes subtiles.', 25.00, 26.38, 45, 'unitaire', 'cafe_blue.jpeg', 2),
(21, 'Coffret Découverte Thés', 'Un assortiment de thés verts, noirs et blancs soigneusement sélectionnés. Parfait pour explorer de nouvelles saveurs et profiter de moments de détente.', 31.19, 32.90, 50, 'unitaire', 'coffret_the_decouverte.jpeg', 1),
(22, 'Coffret Thés Bio', 'Un coffret premium avec une sélection de thés biologiques aux arômes subtils. Une invitation à la dégustation pour les amateurs de thé naturel et raffiné.', 35.00, 38.50, 40, 'unitaire', 'coffret_the_bio.jpeg', 1),
(23, 'Coffret Café du Monde', 'Un voyage sensoriel avec des cafés d’Éthiopie, Colombie et Brésil. Découvrez des arômes riches et variés dans un coffret idéal pour les amateurs de café d’exception.', 40.00, 44.00, 30, 'unitaire', 'coffret_cafe_monde.jpeg', 2),
(24, 'Coffret Grands Crus Café', 'Sélection de cafés rares et prestigieux, offrant une palette de saveurs uniques. Torréfaction artisanale pour une expérience gustative intense et équilibrée.', 45.00, 49.50, 25, 'unitaire', 'coffret_cafe_grand_cru.jpeg', 2);

-- --------------------------------------------------------

--
-- Structure de la table `vendeur`
--

CREATE TABLE `vendeur` (
  `Id_vendeur` int NOT NULL,
  `Nom_vendeur` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Prenom_vendeur` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Mail_vendeur` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Mdp_vendeur` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vendeur`
--

INSERT INTO `vendeur` (`Id_vendeur`, `Nom_vendeur`, `Prenom_vendeur`, `Role`, `Mail_vendeur`, `Mdp_vendeur`) VALUES
(1, 'Bernard', 'Alice', 'admin', 'alice.bernard@example.com', 'admin123'),
(2, 'Robert', 'Julien', 'vendeur', 'julien.robert@example.com', 'vendeur123'),
(3, 'Simon', 'Paul', 'vendeur', 'paul.simon@example.com', 'vendeur234'),
(4, 'Durand', 'Claire', 'admin', 'claire.durand@example.com', 'admin345'),
(5, 'Lemoine', 'Marc', 'vendeur', 'marc.lemoine@example.com', 'vendeur456'),
(6, 'Petit', 'Nina', 'vendeur', 'nina.petit@example.com', 'vendeur567'),
(7, 'Roux', 'Leo', 'admin', 'leo.roux@example.com', 'admin678'),
(8, 'Blanc', 'Eva', 'vendeur', 'eva.blanc@example.com', 'vendeur789'),
(9, 'Garnier', 'Tom', 'vendeur', 'tom.garnier@example.com', 'vendeur890'),
(10, 'Chevalier', 'Anna', 'admin', 'anna.chevalier@example.com', 'admin901'),
(11, 'Muller', 'Hugo', 'vendeur', 'hugo.muller@example.com', 'vendeur012'),
(12, 'Lemoine', 'Emma', 'vendeur', 'emma.lemoine@example.com', 'vendeur123'),
(13, 'Dumas', 'Lucas', 'admin', 'lucas.dumas@example.com', 'admin234'),
(14, 'Fournier', 'Sarah', 'vendeur', 'sarah.fournier@example.com', 'vendeur345'),
(15, 'Girard', 'Maxime', 'vendeur', 'maxime.girard@example.com', 'vendeur456'),
(16, 'Andre', 'Camille', 'admin', 'camille.andre@example.com', 'admin567'),
(17, 'Mercier', 'Nicolas', 'vendeur', 'nicolas.mercier@example.com', 'vendeur678'),
(18, 'Dupuis', 'Laura', 'vendeur', 'laura.dupuis@example.com', 'vendeur789'),
(19, 'Leroux', 'Antoine', 'admin', 'antoine.leroux@example.com', 'admin890'),
(20, 'Renaud', 'Elise', 'vendeur', 'elise.renaud@example.com', 'vendeur901');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`Id_categorie`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`Id_client`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`Id_commande`),
  ADD KEY `Id_vendeur` (`Id_vendeur`),
  ADD KEY `Id_client` (`Id_client`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`Id_ligne_commande`),
  ADD KEY `Id_commande` (`Id_commande`),
  ADD KEY `Id_produit` (`Id_produit`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`Id_produit`),
  ADD KEY `Id_categorie` (`Id_categorie`);

--
-- Index pour la table `vendeur`
--
ALTER TABLE `vendeur`
  ADD PRIMARY KEY (`Id_vendeur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `Id_categorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `Id_client` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `Id_commande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  MODIFY `Id_ligne_commande` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `Id_produit` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `vendeur`
--
ALTER TABLE `vendeur`
  MODIFY `Id_vendeur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`Id_vendeur`) REFERENCES `vendeur` (`Id_vendeur`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`Id_client`) REFERENCES `client` (`Id_client`);

--
-- Contraintes pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD CONSTRAINT `ligne_commande_ibfk_1` FOREIGN KEY (`Id_commande`) REFERENCES `commande` (`Id_commande`),
  ADD CONSTRAINT `ligne_commande_ibfk_2` FOREIGN KEY (`Id_produit`) REFERENCES `produit` (`Id_produit`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`Id_categorie`) REFERENCES `categorie` (`Id_categorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
