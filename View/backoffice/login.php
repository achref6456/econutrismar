<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/Model/bootstrap.php';

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $user = new User();
    if ($email === '' || $password === '') {
        $error = 'Veuillez remplir tous les champs.';
    } elseif (!$user->verifyAdmin($email, $password)) {
        $error = 'Identifiants incorrects ou accès non autorisé.';
    } else {
        $row = $user->findByEmail($email);
        if ($row) {
            $_SESSION['admin_id'] = (int) $row['id_user'];
            $_SESSION['admin_name'] = ($row['prenom'] ?? '') . ' ' . ($row['nom'] ?? '');
            header('Location: index.php');
            exit;
        }
    }
}

$pageTitle = 'Connexion administration — Blog';
require dirname(__DIR__, 2) . '/View/backoffice/login_form.php';
