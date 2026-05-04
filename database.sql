-- ============================================================
--  EcoNutriSmar – Script de création de la base de données
--  Importer dans phpMyAdmin ou via : mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS econutrismar
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE econutrismar;

-- ── Table users ──────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id         INT          AUTO_INCREMENT PRIMARY KEY,
    nom        VARCHAR(100) NOT NULL,
    prenom     VARCHAR(100) NOT NULL,
    email      VARCHAR(191) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    role       ENUM('user','admin') NOT NULL DEFAULT 'user',
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Les comptes de test sont insérés via le script PHP ci-dessous ─────────
-- Exécuter insert_test_users.php UNE SEULE FOIS après avoir importé ce fichier
-- OU utiliser phpMyAdmin pour insérer manuellement avec les hashs générés.
