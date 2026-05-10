-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 10, 2026 at 02:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `econutri`
--

-- --------------------------------------------------------

--
-- Table structure for table `aliments`
--

CREATE TABLE `aliments` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `calories` int(11) NOT NULL DEFAULT 0,
  `proteines` float NOT NULL DEFAULT 0,
  `glucides` float NOT NULL DEFAULT 0,
  `lipides` float NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aliments`
--

INSERT INTO `aliments` (`id`, `nom`, `calories`, `proteines`, `glucides`, `lipides`, `image`) VALUES
(2, 'Poulet', 165, 31, 0, 3.6, 'poulet.jpg'),
(3, 'Riz blanc', 130, 2.7, 28, 0.3, 'riz.jpg'),
(4, 'Pâtes', 131, 5, 25, 1.1, 'pates.jpg'),
(5, 'Tomate', 18, 0.9, 3.9, 0.2, 'tomate.jpg'),
(6, 'Fromage', 402, 25, 1.3, 33, 'fromage.jpg'),
(7, 'Oeuf', 155, 13, 1.1, 11, 'oeuf.jpg'),
(8, 'Lait', 42, 3.4, 5, 1, 'lait.jpg'),
(9, 'Pain', 265, 9, 49, 3.2, 'pain.jpg'),
(10, 'Pomme de terre', 77, 2, 17, 0.1, 'pdt.jpg'),
(11, 'Carotte', 41, 0.9, 10, 0.2, 'carotte.jpg'),
(12, 'Thon', 132, 28, 0, 1, 'thon.jpg'),
(13, 'Saumon', 208, 20, 0, 13, 'saumon.jpg'),
(14, 'Beurre', 717, 0.9, 0.1, 81, 'beurre.jpg'),
(15, 'Huile d olive', 884, 0, 0, 100, 'huile.jpg'),
(16, 'Banane', 89, 1.1, 23, 0.3, 'banane.jpg'),
(17, 'Fraise', 32, 0.6, 7.7, 0.3, 'fraise.jpg'),
(18, 'Yaourt', 59, 10, 3.6, 0.4, 'yaourt.jpg'),
(19, 'Viande rouge', 250, 26, 0, 15, 'viande.jpg'),
(20, 'Poivron', 20, 0.9, 4.6, 0.2, 'poivron.jpg'),
(21, 'Oignon', 40, 1.1, 9.3, 0.1, 'oignon.jpg'),
(23, 'Tomatooooo', 10, 20, 10, 10, 'uploads/aliment_69e3af0a7c2b8.png'),
(24, 'pomme', 15, 2, 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aliment_categorie`
--

CREATE TABLE `aliment_categorie` (
  `aliment_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aliment_categorie`
--

INSERT INTO `aliment_categorie` (`aliment_id`, `categorie_id`) VALUES
(5, 2),
(10, 2),
(11, 2),
(16, 1),
(17, 1),
(21, 2);

-- --------------------------------------------------------

--
-- Table structure for table `categorie`
--

CREATE TABLE `categorie` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categorie`
--

INSERT INTO `categorie` (`id`, `nom`) VALUES
(1, 'Fruits'),
(2, 'Veggies'),
(4, 'desert');

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--

CREATE TABLE `commandes` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_phone` varchar(20) DEFAULT NULL,
  `recettes` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `admin_message` text DEFAULT NULL,
  `date_commande` datetime DEFAULT current_timestamp(),
  `date_traitement` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commandes`
--

INSERT INTO `commandes` (`id`, `user_name`, `user_email`, `user_phone`, `recettes`, `status`, `admin_message`, `date_commande`, `date_traitement`) VALUES
(1, 'Ayla', 'Aylahamdaoui@gmail.com', '216985515', '[{\"id\":4,\"name\":\"Salad de fruits\",\"image\":\"uploads/recette_69e3aed755704.jpeg\",\"time\":15}]', 'accepted', 'Bonjour Ayla,\r\n\r\nVotre commande a bien été acceptée ! Nous vous contacterons très prochainement.\r\n\r\nCordialement,\r\nL\'équipe EcoNutri', '2026-04-25 21:49:59', '2026-04-25 21:50:14'),
(2, 'yassmine', 'yassminehzemi@gmail.com', '6348678', '[{\"id\":5,\"name\":\"3ija\",\"image\":\"uploads/recette_69e7401961111.jpeg\",\"time\":15},{\"id\":4,\"name\":\"Salad de fruits\",\"image\":\"uploads/recette_69e3aed755704.jpeg\",\"time\":15}]', 'accepted', 'Bonjour yassmine,\r\n\r\nVotre commande a bien été acceptée ! Nous vous contacterons très prochainement.\r\n\r\nCordialement,\r\nL\'équipe EcoNutri', '2026-04-27 15:01:29', '2026-04-27 15:01:47'),
(3, 'Ayla', 'aylahm@gmqil.com', '28498642', '[{\"id\":8,\"name\":\"Banana Smoothie\",\"image\":\"uploads/recette_69eff08c7baeb.jpg\",\"time\":5,\"qty\":2}]', 'accepted', 'Bonjour Ayla,\r\n\r\nVotre commande a bien été acceptée ! Nous vous contacterons très prochainement.\r\n\r\nCordialement,\r\nL\'équipe EcoNutri', '2026-05-05 11:35:21', '2026-05-05 11:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `recettes`
--

CREATE TABLE `recettes` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `temps_preparation` int(11) NOT NULL DEFAULT 0,
  `difficulte` varchar(50) NOT NULL DEFAULT 'facile',
  `date_creation` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recettes`
--

INSERT INTO `recettes` (`id`, `nom`, `description`, `image`, `temps_preparation`, `difficulte`, `date_creation`) VALUES
(7, 'Tagliatelle', 'Delicious tagliatelle pasta served with a flavorful sauce, offering a perfect balance of taste and texture. This classic dish is rich, satisfying, and ideal for a comforting lunch or dinner.', 'uploads/recette_69efef95df081.webp', 10, 'moyen', NULL),
(8, 'Banana Smoothie', 'Creamy and refreshing banana smoothie made with ripe bananas and nutritious ingredients, perfect for breakfast or as a healthy snack. Rich in vitamins, potassium, and energy, it’s both delicious and satisfying', 'uploads/recette_69eff08c7baeb.jpg', 5, 'facile', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recette_aliment`
--

CREATE TABLE `recette_aliment` (
  `id` int(11) NOT NULL,
  `recette_id` int(11) NOT NULL,
  `aliment_id` int(11) NOT NULL,
  `quantite` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recette_aliment`
--

INSERT INTO `recette_aliment` (`id`, `recette_id`, `aliment_id`, `quantite`) VALUES
(13, 7, 4, '500'),
(14, 7, 15, '1'),
(15, 7, 21, '1'),
(16, 7, 5, '3'),
(17, 7, 2, '1'),
(18, 8, 16, '1'),
(19, 8, 8, '1');

-- --------------------------------------------------------

--
-- Table structure for table `recette_categorie`
--

CREATE TABLE `recette_categorie` (
  `recette_id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recette_categorie`
--

INSERT INTO `recette_categorie` (`recette_id`, `categorie_id`) VALUES
(8, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aliments`
--
ALTER TABLE `aliments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aliment_categorie`
--
ALTER TABLE `aliment_categorie`
  ADD PRIMARY KEY (`aliment_id`,`categorie_id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Indexes for table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recettes`
--
ALTER TABLE `recettes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recette_aliment`
--
ALTER TABLE `recette_aliment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recette_id` (`recette_id`),
  ADD KEY `aliment_id` (`aliment_id`);

--
-- Indexes for table `recette_categorie`
--
ALTER TABLE `recette_categorie`
  ADD PRIMARY KEY (`recette_id`,`categorie_id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aliments`
--
ALTER TABLE `aliments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recettes`
--
ALTER TABLE `recettes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recette_aliment`
--
ALTER TABLE `recette_aliment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aliment_categorie`
--
ALTER TABLE `aliment_categorie`
  ADD CONSTRAINT `aliment_categorie_ibfk_1` FOREIGN KEY (`aliment_id`) REFERENCES `aliments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `aliment_categorie_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recette_aliment`
--
ALTER TABLE `recette_aliment`
  ADD CONSTRAINT `recette_aliment_ibfk_1` FOREIGN KEY (`recette_id`) REFERENCES `recettes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recette_aliment_ibfk_2` FOREIGN KEY (`aliment_id`) REFERENCES `aliments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `recette_categorie`
--
ALTER TABLE `recette_categorie`
  ADD CONSTRAINT `recette_categorie_ibfk_1` FOREIGN KEY (`recette_id`) REFERENCES `recettes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `recette_categorie_ibfk_2` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
