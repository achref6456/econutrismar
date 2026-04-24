-- Créer la base : CREATE DATABASE IF NOT EXISTS econutri_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Puis sélectionner econutri_db et importer ce fichier.

SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS utilisateur (
  id_user INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL DEFAULT '',
  prenom VARCHAR(100) NOT NULL DEFAULT '',
  email VARCHAR(255) NOT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  date_creation DATE NOT NULL,
  statut VARCHAR(20) NOT NULL DEFAULT 'actif',
  UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog (
  id_article INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre VARCHAR(255) NOT NULL,
  contenu TEXT NOT NULL,
  date_publication DATETIME NOT NULL,
  image VARCHAR(512) NOT NULL DEFAULT '',
  statut VARCHAR(20) NOT NULL DEFAULT 'publie',
  user_id INT UNSIGNED NOT NULL,
  CONSTRAINT fk_blog_user FOREIGN KEY (user_id) REFERENCES utilisateur (id_user) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Migration pour une base existante :
-- ALTER TABLE blog MODIFY date_publication DATETIME NOT NULL;
-- ALTER TABLE blog ADD COLUMN statut VARCHAR(20) NOT NULL DEFAULT 'publie' AFTER image;

-- Jointure : commentaire.article_id → blog.id_article
-- SELECT c.*, b.titre AS article_titre
-- FROM commentaire c
-- LEFT JOIN blog b ON b.id_article = c.article_id;
CREATE TABLE IF NOT EXISTS commentaire (
  id_commentaire INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  article_id INT UNSIGNED NOT NULL,
  pseudo VARCHAR(100) NOT NULL,
  contenu TEXT NOT NULL,
  date_commentaire DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(20) NOT NULL DEFAULT 'en_attente',
  CONSTRAINT fk_com_blog FOREIGN KEY (article_id) REFERENCES blog (id_article) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog_vues (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  article_id INT UNSIGNED NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  viewed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_vue_blog FOREIGN KEY (article_id) REFERENCES blog (id_article) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS blog_likes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  article_id INT UNSIGNED NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  liked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_like_blog FOREIGN KEY (article_id) REFERENCES blog (id_article) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, role, date_creation, statut)
VALUES (
  'Admin',
  'EcoNutri',
  'admin@econutri.local',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'admin',
  CURDATE(),
  'actif'
) ON DUPLICATE KEY UPDATE email = email;

INSERT INTO blog (titre, contenu, date_publication, image, statut, user_id)
SELECT
  'Bienvenue sur le blog EcoNutri',
  'Ce premier article est inséré par le script SQL. Vous pouvez le modifier ou le supprimer depuis le back-office Blog après connexion.',
  NOW(),
  '',
  'publie',
  id_user
FROM utilisateur WHERE role = 'admin' ORDER BY id_user ASC LIMIT 1;
