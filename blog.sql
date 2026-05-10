-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 10 mai 2026 à 14:52
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `econutri_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `blog`
--

CREATE TABLE `blog` (
  `id_article` int(10) UNSIGNED NOT NULL,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_publication` datetime NOT NULL,
  `image` varchar(512) NOT NULL DEFAULT '',
  `statut` varchar(20) NOT NULL DEFAULT 'publie',
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blog`
--

INSERT INTO `blog` (`id_article`, `titre`, `contenu`, `date_publication`, `image`, `statut`, `user_id`) VALUES
(2, 'Le gaspillage alimentaire : un problème mondial', 'Chaque année, des millions de tonnes de nourriture sont gaspillées dans le monde, alors que des millions de personnes souffrent encore de la faim.\r\n\r\nLe gaspillage alimentaire se produit à tous les niveaux : production, distribution et consommation. À la maison, il est souvent causé par une mauvaise gestion des achats ou des dates de péremption mal comprises.\r\n\r\nRéduire ce gaspillage est essentiel pour protéger l’environnement et économiser les ressources naturelles comme l’eau et l’énergie.\r\n\r\nAdopter des gestes simples comme planifier ses repas ou conserver correctement les aliments peut faire une grande différence.', '2026-04-16 00:00:00', 'uploads/blog/blog_ae87a901f11cc5ec.webp', 'publie', 1),
(3, 'Comment réduire le gaspillage au quotidien', 'Réduire le gaspillage ne demande pas de grands efforts, mais plutôt de bonnes habitudes.\r\n\r\nCommencez par acheter uniquement ce dont vous avez besoin. Vérifiez votre réfrigérateur avant de faire les courses et privilégiez les produits locaux et de saison.\r\n\r\nApprenez à conserver vos aliments correctement et à réutiliser les restes dans de nouvelles recettes.\r\n\r\nLe tri des déchets et le recyclage sont également essentiels pour limiter l’impact environnemental.\r\n\r\nChaque petit geste compte et contribue à un mode de vie plus responsable.', '2026-04-16 00:00:00', 'uploads/blog/blog_134e8c6d0a93b35d.jpg', 'publie', 1),
(4, 'Les bienfaits d’une alimentation saine', 'Adopter une alimentation saine est essentiel pour maintenir une bonne santé physique et mentale.\r\n\r\nLes aliments naturels comme les fruits, les légumes et les céréales complètes apportent les nutriments nécessaires au bon fonctionnement du corps.\r\n\r\nUne alimentation équilibrée permet de renforcer le système immunitaire, d’améliorer l’énergie au quotidien et de prévenir plusieurs maladies.\r\n\r\nIl est conseillé de limiter les produits transformés et de privilégier des repas faits maison.\r\n\r\nPrendre soin de son alimentation, c’est investir dans sa santé sur le long terme.', '2026-04-16 00:00:00', 'uploads/blog/blog_c7449e329a8f75d1.jpg', 'publie', 1),
(6, 'giyyyyyyyyy', 'vvvvvvvvvvvvviiiiiiiiiiiiiitttttttttttddddddddd', '2026-04-24 00:48:00', '', 'publie', 1);

-- --------------------------------------------------------

--
-- Structure de la table `blog_likes`
--

CREATE TABLE `blog_likes` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `liked_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blog_likes`
--

INSERT INTO `blog_likes` (`id`, `article_id`, `ip_address`, `liked_at`) VALUES
(4, 2, '::1', '2026-04-16 19:33:54'),
(8, 4, '::1', '2026-04-23 23:37:32');

-- --------------------------------------------------------

--
-- Structure de la table `blog_vues`
--

CREATE TABLE `blog_vues` (
  `id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `viewed_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `blog_vues`
--

INSERT INTO `blog_vues` (`id`, `article_id`, `ip_address`, `viewed_at`) VALUES
(2, 4, '::1', '2026-04-16 19:33:41'),
(3, 3, '::1', '2026-04-16 19:33:49'),
(4, 2, '::1', '2026-04-16 19:33:53'),
(5, 4, '::1', '2026-04-19 03:48:52'),
(6, 3, '::1', '2026-04-19 03:49:09'),
(7, 2, '::1', '2026-04-19 03:49:18'),
(8, 3, '::1', '2026-04-20 16:51:02'),
(9, 4, '::1', '2026-04-21 11:01:30'),
(10, 4, '::1', '2026-04-23 23:37:31'),
(11, 3, '::1', '2026-04-24 00:31:10');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id_commentaire` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `pseudo` varchar(100) NOT NULL,
  `contenu` text NOT NULL,
  `date_commentaire` datetime NOT NULL DEFAULT current_timestamp(),
  `statut` varchar(20) NOT NULL DEFAULT 'en_attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `article_id`, `pseudo`, `contenu`, `date_commentaire`, `statut`) VALUES
(2, 4, 'mohamed', 'excellent', '2026-04-17 02:54:29', 'approuve'),
(3, 3, 'oueslati', 'mmmmmm', '2026-04-17 02:54:46', 'refuse'),
(6, 3, 'kimou', 'excellent', '2026-04-17 17:14:14', 'approuve'),
(7, 3, 'emna', 'bien', '2026-04-20 16:51:17', 'approuve'),
(8, 3, 'OUESLATII', 'BIEN', '2026-04-21 11:00:43', 'approuve'),
(9, 4, 'emna', 'interrissent', '2026-04-23 23:38:45', 'approuve'),
(10, 3, 'mohamed', 'grod', '2026-04-24 00:32:01', 'refuse');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `prenom` varchar(100) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `date_creation` date NOT NULL,
  `statut` varchar(20) NOT NULL DEFAULT 'actif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_user`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`, `date_creation`, `statut`) VALUES
(1, 'Admin', 'EcoNutri', 'admin@econutri.local', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-16', 'actif');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `blog`
--
ALTER TABLE `blog`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `fk_blog_user` (`user_id`);

--
-- Index pour la table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_like_blog` (`article_id`);

--
-- Index pour la table `blog_vues`
--
ALTER TABLE `blog_vues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vue_blog` (`article_id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id_commentaire`),
  ADD KEY `fk_com_blog` (`article_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `blog`
--
ALTER TABLE `blog`
  MODIFY `id_article` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `blog_likes`
--
ALTER TABLE `blog_likes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `blog_vues`
--
ALTER TABLE `blog_vues`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id_commentaire` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blog`
--
ALTER TABLE `blog`
  ADD CONSTRAINT `fk_blog_user` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id_user`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `blog_likes`
--
ALTER TABLE `blog_likes`
  ADD CONSTRAINT `fk_like_blog` FOREIGN KEY (`article_id`) REFERENCES `blog` (`id_article`) ON DELETE CASCADE;

--
-- Contraintes pour la table `blog_vues`
--
ALTER TABLE `blog_vues`
  ADD CONSTRAINT `fk_vue_blog` FOREIGN KEY (`article_id`) REFERENCES `blog` (`id_article`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_com_blog` FOREIGN KEY (`article_id`) REFERENCES `blog` (`id_article`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
