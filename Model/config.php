<?php

/**
 * Configuration BDD (nom aligné sur phpMyAdmin : econutri_db).
 * Dossier d’upload : uniquement sous View/ (accessible par le navigateur).
 */
return [
    'db' => [
        'host'     => '127.0.0.1',
        'name'     => 'econutri_db',
        'user'     => 'econutri_user',
        'password' => '123456',
        'charset'  => 'utf8mb4',
    ],
    'app' => [
        'upload_dir' => __DIR__ . '/../View/uploads/blog',
        /**
         * Si true : le lien « Admin blog » (front) ouvre le back-office sans formulaire,
         * en réutilisant le premier compte admin en base (module User / SQL).
         * Mettre à false avant toute mise en production.
         */
        'dev_blog_admin_bypass' => true,
        /**
         * URL de base pour les QR (sans slash final). Si Apache utilise le dossier View comme racine du site,
         * mettez seulement http://VOTRE_IP:8000 — pas de /View dans l’adresse (les pages sont /frontoffice/…).
         * Sinon : http://192.168.1.10:8000/projet%20web — vous pouvez terminer par /View : il sera ignoré pour le QR.
         * Laisser vide pour détection automatique depuis le navigateur sur le PC.
         */
        'public_base_url' => '',
        /**
         * Si true : depuis localhost / 127.0.0.1, l’URL du QR utilise l’IPv4 locale du PC
         * (pour que le téléphone sur le même Wi‑Fi puisse joindre XAMPP). Désactiver si besoin.
         * Avant tout : double-cliquez View/tools/Ouvrir_reseau_pour_QR.bat (admin) puis redémarrez Apache.
         * Sinon : page blog → « Problème d’accès… » → formulaire, ou fichier Model/local_public_url.txt (voir .example).
         */
        'qr_use_lan_ip_when_localhost' => true,
    ],
];
