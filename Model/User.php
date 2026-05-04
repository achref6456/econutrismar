<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getPdo();
    }

    public function findAll(): array {
        $stmt = $this->pdo->query(
            "SELECT id, nom, prenom, email, role, created_at, banned_until FROM users ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    // ── Recherche + Tri ───────────────────────────────────────────────────
    public function search(string $keyword, string $tri = 'created_at', string $ordre = 'DESC'): array {
        $colonnesAutorisees = ['id', 'nom', 'prenom', 'email', 'role', 'created_at'];
        $ordresAutorises    = ['ASC', 'DESC'];

        if (!in_array($tri, $colonnesAutorisees))   $tri   = 'created_at';
        if (!in_array($ordre, $ordresAutorises))     $ordre = 'DESC';

        $stmt = $this->pdo->prepare(
            "SELECT id, nom, prenom, email, role, created_at, banned_until, avatar
             FROM users
             WHERE nom LIKE ? OR prenom LIKE ? OR email LIKE ?
             OR CONCAT(prenom, ' ', nom) LIKE ?
             OR CONCAT(nom, ' ', prenom) LIKE ?
             ORDER BY {$tri} {$ordre}"
        );
        $k = '%' . $keyword . '%';
        $stmt->execute([$k, $k, $k, $k, $k]);
        return $stmt->fetchAll();
    }

    // ── Statistiques ──────────────────────────────────────────────────────
    public function getStats(): array {
        $total  = $this->pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $admins = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $users  = $this->pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
        $recent = $this->pdo->query(
            "SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)"
        )->fetchColumn();

        return [
            'total'  => $total,
            'admins' => $admins,
            'users'  => $users,
            'recent' => $recent,
        ];
    }

    public function findById(int $id): array|false {
        $stmt = $this->pdo->prepare(
            "SELECT id, nom, prenom, email, role, created_at, banned_until, avatar FROM users WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false {
        $stmt = $this->pdo->prepare("SELECT id, nom, prenom, email, password, role, created_at, banned_until FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(string $nom, string $prenom, string $email, string $password, string $role = 'user', ?string $avatar = null): bool {
        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (nom, prenom, email, password, role, avatar, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())"
        );
        return $stmt->execute([$nom, $prenom, $email, $hash, $role, $avatar]);
    }

    public function update(int $id, string $nom, string $prenom, string $email, string $role, ?string $avatar = null): bool {
        if ($avatar !== null) {
            $stmt = $this->pdo->prepare(
                "UPDATE users SET nom = ?, prenom = ?, email = ?, role = ?, avatar = ? WHERE id = ?"
            );
            // Si avatar = '' on met NULL en BDD
            return $stmt->execute([$nom, $prenom, $email, $role, $avatar === '' ? null : $avatar, $id]);
        }
        $stmt = $this->pdo->prepare(
            "UPDATE users SET nom = ?, prenom = ?, email = ?, role = ? WHERE id = ?"
        );
        return $stmt->execute([$nom, $prenom, $email, $role, $id]);
    }

    public function ban(int $id, int $jours = 3): bool {
        $until = date('Y-m-d H:i:s', strtotime("+{$jours} days"));
        $stmt  = $this->pdo->prepare("UPDATE users SET banned_until = ? WHERE id = ?");
        return $stmt->execute([$until, $id]);
    }

    public function unban(int $id): bool {
        $stmt = $this->pdo->prepare("UPDATE users SET banned_until = NULL WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function isBanned(int $id): bool {
        $stmt = $this->pdo->prepare("SELECT banned_until FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $row  = $stmt->fetch();
        if (!$row || $row['banned_until'] === null) return false;
        return strtotime($row['banned_until']) > time();
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

    public function updatePassword(string $email, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_ARGON2ID);
        $stmt = $this->pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([$hash, $email]);
    }

    public function emailExists(string $email): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return (bool) $stmt->fetchColumn();
    }
}
