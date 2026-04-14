<?php

declare(strict_types=1);

class Blog
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /** @return list<array<string,mixed>> */
    public function findAllPublished(): array
    {
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                ORDER BY b.date_publication DESC, b.id_article DESC';
        $st = $this->pdo->query($sql);
        return $st->fetchAll();
    }

    /** @return array<string,mixed>|null */
    public function findById(int $id): ?array
    {
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom, u.email AS auteur_email
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                WHERE b.id_article = :id LIMIT 1';
        $st = $this->pdo->prepare($sql);
        $st->execute(['id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    /** @return list<array<string,mixed>> */
    public function search(string $q): array
    {
        $q = trim($q);
        if ($q === '') {
            return [];
        }
        $like = '%' . $q . '%';
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                WHERE b.titre LIKE :q1 OR b.contenu LIKE :q2
                ORDER BY b.date_publication DESC';
        $st = $this->pdo->prepare($sql);
        $st->execute(['q1' => $like, 'q2' => $like]);
        return $st->fetchAll();
    }

    /** @return list<array<string,mixed>> */
    public function findAllForAdmin(): array
    {
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                ORDER BY b.date_publication DESC, b.id_article DESC';
        $st = $this->pdo->query($sql);
        return $st->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO blog (titre, contenu, date_publication, image, user_id)
                VALUES (:titre, :contenu, :date_publication, :image, :user_id)';
        $st = $this->pdo->prepare($sql);
        $st->execute([
            'titre'             => $data['titre'],
            'contenu'           => $data['contenu'],
            'date_publication'  => $data['date_publication'],
            'image'             => $data['image'] ?? '',
            'user_id'           => (int) $data['user_id'],
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE blog SET titre = :titre, contenu = :contenu,
                date_publication = :date_publication, image = :image
                WHERE id_article = :id';
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            'id'                => $id,
            'titre'             => $data['titre'],
            'contenu'           => $data['contenu'],
            'date_publication'  => $data['date_publication'],
            'image'             => $data['image'] ?? '',
        ]);
    }

    public function delete(int $id): bool
    {
        $st = $this->pdo->prepare('DELETE FROM blog WHERE id_article = :id');
        return $st->execute(['id' => $id]);
    }

    /** ID du premier utilisateur admin (pour formulaires sans auth complète) */
    public function getDefaultAdminUserId(): int
    {
        $st = $this->pdo->query("SELECT id_user FROM utilisateur WHERE role = 'admin' ORDER BY id_user ASC LIMIT 1");
        $id = $st->fetchColumn();
        return $id !== false ? (int) $id : 1;
    }
}
