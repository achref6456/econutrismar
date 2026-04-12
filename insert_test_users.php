<?php
// ============================================================
//  Script d'insertion des comptes de test
//  Ouvrir dans le navigateur UNE SEULE FOIS : http://localhost/.../insert_test_users.php
//  Supprimer ce fichier ensuite !
// ============================================================
require_once __DIR__ . '/config/database.php';

$pdo = Database::getInstance()->getPdo();

$users = [
    [
        'nom'      => 'Admin',
        'prenom'   => 'Super',
        'email'    => 'admin@econutri.com',
        'password' => 'Admin1234',
        'role'     => 'admin',
    ],
    [
        'nom'      => 'Utilisateur',
        'prenom'   => 'Test',
        'email'    => 'user@econutri.com',
        'password' => 'User1234',
        'role'     => 'user',
    ],
];

foreach ($users as $u) {
    // Vérifier si l'email existe déjà
    $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $check->execute([$u['email']]);
    if ($check->fetchColumn() > 0) {
        echo "⚠️ Email déjà existant, ignoré : {$u['email']}<br>";
        continue;
    }

    $hash = password_hash($u['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare(
        "INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->execute([$u['nom'], $u['prenom'], $u['email'], $hash, $u['role']]);
    echo "✅ Compte créé : {$u['email']} (mot de passe : {$u['password']})<br>";
}

echo "<br><strong>⚠️ Supprimez ce fichier maintenant !</strong>";
