<?php

declare(strict_types=1);

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** @return array<string,mixed>|null */
    public function findByEmail(string $email): ?array
    {
        $st = $this->pdo->prepare(
            'SELECT * FROM utilisateur WHERE email = :e LIMIT 1'
        );
        $st->execute(['e' => $email]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function verifyAdmin(string $email, string $password): bool
    {
        $u = $this->findByEmail($email);
        if ($u === null || ($u['role'] ?? '') !== 'admin') {
            return false;
        }
        return password_verify($password, $u['mot_de_passe']);
    }

    /** @return array<string,mixed>|null */
    public function findFirstAdmin(): ?array
    {
        $st = $this->pdo->query(
            "SELECT * FROM utilisateur WHERE role = 'admin' ORDER BY id_user ASC LIMIT 1"
        );
        $row = $st->fetch();
        return $row ?: null;
    }
}
