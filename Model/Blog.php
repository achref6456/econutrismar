<?php

declare(strict_types=1);

class Blog
{
    private ?PDO $pdo = null;
    private string $jsonFile;

    public function __construct()
    {
        $this->jsonFile = __DIR__ . '/storage/blog_articles.json';
        try {
            $this->pdo = Database::getConnection();
        } catch (Throwable $e) {
            $this->pdo = null;
            $this->ensureJsonStorage();
        }
    }

    /** @return list<array<string,mixed>> */
    public function findAllPublished(): array
    {
        if ($this->pdo === null) {
            return $this->sortArticles($this->loadJsonArticles());
        }
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
        if ($this->pdo === null) {
            foreach ($this->loadJsonArticles() as $row) {
                if ((int) ($row['id_article'] ?? 0) === $id) {
                    return $row;
                }
            }
            return null;
        }
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
        if ($this->pdo === null) {
            $q = mb_strtolower($q);
            $rows = array_filter($this->loadJsonArticles(), static function (array $a) use ($q): bool {
                $titre = mb_strtolower((string) ($a['titre'] ?? ''));
                $contenu = mb_strtolower((string) ($a['contenu'] ?? ''));
                return str_contains($titre, $q) || str_contains($contenu, $q);
            });
            return $this->sortArticles(array_values($rows));
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
        if ($this->pdo === null) {
            $rows = $this->sortArticles($this->loadJsonArticles());
            foreach ($rows as &$r) {
                $r['vues'] = 0;
                $r['likes'] = 0;
            }
            return $rows;
        }
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom,
                       (SELECT COUNT(*) FROM blog_vues WHERE article_id = b.id_article) AS vues,
                       (SELECT COUNT(*) FROM blog_likes WHERE article_id = b.id_article) AS likes
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                ORDER BY b.date_publication DESC, b.id_article DESC';
        $st = $this->pdo->query($sql);
        return $st->fetchAll();
    }

    public function create(array $data): int
    {
        if ($this->pdo === null) {
            $rows = $this->loadJsonArticles();
            $nextId = 1;
            foreach ($rows as $r) {
                $nextId = max($nextId, (int) ($r['id_article'] ?? 0) + 1);
            }
            $rows[] = [
                'id_article' => $nextId,
                'titre' => (string) $data['titre'],
                'contenu' => (string) $data['contenu'],
                'date_publication' => (string) $data['date_publication'],
                'image' => (string) ($data['image'] ?? ''),
                'user_id' => (int) ($data['user_id'] ?? 1),
                'prenom' => 'Admin',
                'auteur_nom' => 'EcoNutri',
                'auteur_email' => 'admin@local.dev',
            ];
            $this->saveJsonArticles($rows);
            return $nextId;
        }
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
        if ($this->pdo === null) {
            $rows = $this->loadJsonArticles();
            $updated = false;
            foreach ($rows as &$row) {
                if ((int) ($row['id_article'] ?? 0) !== $id) {
                    continue;
                }
                $row['titre'] = (string) $data['titre'];
                $row['contenu'] = (string) $data['contenu'];
                $row['date_publication'] = (string) $data['date_publication'];
                $row['image'] = (string) ($data['image'] ?? '');
                $updated = true;
                break;
            }
            unset($row);
            if ($updated) {
                $this->saveJsonArticles($rows);
            }
            return $updated;
        }
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
        if ($this->pdo === null) {
            $rows = $this->loadJsonArticles();
            $before = count($rows);
            $rows = array_values(array_filter($rows, static fn(array $r): bool => (int) ($r['id_article'] ?? 0) !== $id));
            if (count($rows) === $before) {
                return false;
            }
            $this->saveJsonArticles($rows);
            return true;
        }
        $st = $this->pdo->prepare('DELETE FROM blog WHERE id_article = :id');
        return $st->execute(['id' => $id]);
    }

    /** ID du premier utilisateur admin (pour formulaires sans auth complète) */
    public function getDefaultAdminUserId(): int
    {
        if ($this->pdo === null) {
            return 1;
        }
        $st = $this->pdo->query("SELECT id_user FROM utilisateur WHERE role = 'admin' ORDER BY id_user ASC LIMIT 1");
        $id = $st->fetchColumn();
        return $id !== false ? (int) $id : 1;
    }

    // --- Statistiques de Lecture ---

    public function trackView(int $articleId, string $ip): void
    {
        if ($this->pdo === null) return; // Fallback ignore analytics
        // Une IP = une vue par 24h
        $sqlCheck = 'SELECT id FROM blog_vues WHERE article_id = :id AND ip_address = :ip AND viewed_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) LIMIT 1';
        $st = $this->pdo->prepare($sqlCheck);
        $st->execute(['id' => $articleId, 'ip' => $ip]);
        if (!$st->fetch()) {
            $sqlInsert = 'INSERT INTO blog_vues (article_id, ip_address, viewed_at) VALUES (:id, :ip, NOW())';
            $this->pdo->prepare($sqlInsert)->execute(['id' => $articleId, 'ip' => $ip]);
        }
    }

    public function toggleLike(int $articleId, string $ip): bool
    {
        if ($this->pdo === null) return false;
        $sqlCheck = 'SELECT id FROM blog_likes WHERE article_id = :id AND ip_address = :ip LIMIT 1';
        $st = $this->pdo->prepare($sqlCheck);
        $st->execute(['id' => $articleId, 'ip' => $ip]);
        $row = $st->fetch();
        if ($row) {
            $this->pdo->prepare('DELETE FROM blog_likes WHERE id = :id')->execute(['id' => $row['id']]);
            return false; // Unlike
        } else {
            $this->pdo->prepare('INSERT INTO blog_likes (article_id, ip_address, liked_at) VALUES (:id, :ip, NOW())')->execute(['id' => $articleId, 'ip' => $ip]);
            return true; // Like
        }
    }

    public function hasLiked(int $articleId, string $ip): bool
    {
        if ($this->pdo === null) return false;
        $st = $this->pdo->prepare('SELECT id FROM blog_likes WHERE article_id = :id AND ip_address = :ip LIMIT 1');
        $st->execute(['id' => $articleId, 'ip' => $ip]);
        return (bool) $st->fetch();
    }

    public function getStats(string $period = 'all'): array
    {
        if ($this->pdo === null) return ['topViews' => [], 'topLikes' => [], 'chartLabels' => [], 'chartData' => []];

        $whereClause = '';
        if ($period === 'week') $whereClause = '>= DATE_SUB(NOW(), INTERVAL 7 DAY)';
        elseif ($period === 'month') $whereClause = '>= DATE_SUB(NOW(), INTERVAL 1 MONTH)';

        // Top Vues
        $sqlVues = 'SELECT b.titre, COUNT(v.id) AS total FROM blog b LEFT JOIN blog_vues v ON b.id_article = v.article_id ' .
                   ($period !== 'all' ? 'AND v.viewed_at ' . $whereClause : '') .
                   ' GROUP BY b.id_article ORDER BY total DESC LIMIT 5';
        $topViews = $this->pdo->query($sqlVues)->fetchAll(PDO::FETCH_ASSOC);

        // Top Likes
        $sqlLikes = 'SELECT b.titre, COUNT(l.id) AS total FROM blog b LEFT JOIN blog_likes l ON b.id_article = l.article_id ' .
                    ($period !== 'all' ? 'AND l.liked_at ' . $whereClause : '') .
                    ' GROUP BY b.id_article ORDER BY total DESC LIMIT 5';
        $topLikes = $this->pdo->query($sqlLikes)->fetchAll(PDO::FETCH_ASSOC);

        // Chart Data (Toutes les vues groupées par article, pour le graphe)
        $sqlChart = 'SELECT b.titre, COUNT(v.id) AS total FROM blog b LEFT JOIN blog_vues v ON b.id_article = v.article_id ' .
                    ($period !== 'all' ? 'AND v.viewed_at ' . $whereClause : '') .
                    ' GROUP BY b.id_article ORDER BY total DESC';
        $chartRaw = $this->pdo->query($sqlChart)->fetchAll(PDO::FETCH_ASSOC);
        $chartLabels = array_column($chartRaw, 'titre');
        $chartData = array_column($chartRaw, 'total');

        return [
            'topViews' => $topViews,
            'topLikes' => $topLikes,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData
        ];
    }

    private function ensureJsonStorage(): void
    {
        $dir = dirname($this->jsonFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (!is_file($this->jsonFile)) {
            file_put_contents($this->jsonFile, "[]\n");
        }
    }

    /** @return list<array<string,mixed>> */
    private function loadJsonArticles(): array
    {
        $this->ensureJsonStorage();
        $raw = (string) file_get_contents($this->jsonFile);
        $rows = json_decode($raw, true);
        return is_array($rows) ? $rows : [];
    }

    /** @param list<array<string,mixed>> $rows */
    private function saveJsonArticles(array $rows): void
    {
        $json = json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return;
        }
        file_put_contents($this->jsonFile, $json . PHP_EOL);
    }

    /** @param list<array<string,mixed>> $rows
     *  @return list<array<string,mixed>>
     */
    private function sortArticles(array $rows): array
    {
        usort($rows, static function (array $a, array $b): int {
            $da = (string) ($a['date_publication'] ?? '');
            $db = (string) ($b['date_publication'] ?? '');
            $cmp = strcmp($db, $da);
            if ($cmp !== 0) {
                return $cmp;
            }
            return (int) ($b['id_article'] ?? 0) <=> (int) ($a['id_article'] ?? 0);
        });
        return $rows;
    }
}
