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
    ],
];
