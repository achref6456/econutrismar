<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query(
            "SELECT id, nom, prenom, email, role, created_at FROM users ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id, nom, prenom, email, role, created_at FROM users WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(string $nom, string $prenom, string $email, string $password, string $role = 'user'): bool {
        // Algorithme Argon2id — le plus sécurisé, résistant aux attaques par force brute et par canal auxiliaire
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nom, prenom, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$nom, $prenom, $email, $hash, $role]);
    }

    public function update(int $id, string $nom, string $prenom, string $email, string $role): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET nom = ?, prenom = ?, email = ?, role = ? WHERE id = ?"
        );
        return $stmt->execute([$nom, $prenom, $email, $role, $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyCredentials(string $email, string $password): array|false {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }
}
