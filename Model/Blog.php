<?php

declare(strict_types=1);

class Blog
{
    private ?PDO $pdo = null;
    private string $jsonFile;
    private string $likesFile;
    private string $viewsFile;

    public function __construct()
    {
        $storageDir = __DIR__ . '/storage';
        $this->jsonFile  = $storageDir . '/blog_articles.json';
        $this->likesFile = $storageDir . '/blog_likes.json';
        $this->viewsFile = $storageDir . '/blog_vues.json';
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
            $rows = array_filter(
                $this->loadJsonArticles(),
                static fn(array $r): bool => ($r['statut'] ?? 'publie') === 'publie'
            );
            return $this->sortArticles(array_values($rows));
        }
        $sql = 'SELECT b.*, u.prenom, u.nom AS auteur_nom
                FROM blog b
                JOIN utilisateur u ON u.id_user = b.user_id
                WHERE b.statut = \'publie\'
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
                $aid = (int) ($r['id_article'] ?? 0);
                $r['vues']  = $this->countJsonViews($aid);
                $r['likes'] = $this->countJsonLikes($aid);
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
                'statut' => (string) ($data['statut'] ?? 'publie'),
                'user_id' => (int) ($data['user_id'] ?? 1),
                'prenom' => 'Admin',
                'auteur_nom' => 'EcoNutri',
                'auteur_email' => 'admin@local.dev',
            ];
            $this->saveJsonArticles($rows);
            return $nextId;
        }
        $sql = 'INSERT INTO blog (titre, contenu, date_publication, image, statut, user_id)
                VALUES (:titre, :contenu, :date_publication, :image, :statut, :user_id)';
        $st = $this->pdo->prepare($sql);
        $st->execute([
            'titre'             => $data['titre'],
            'contenu'           => $data['contenu'],
            'date_publication'  => $data['date_publication'],
            'image'             => $data['image'] ?? '',
            'statut'            => $data['statut'] ?? 'publie',
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
                $row['statut'] = (string) ($data['statut'] ?? $row['statut'] ?? 'publie');
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
                date_publication = :date_publication, image = :image, statut = :statut
                WHERE id_article = :id';
        $st = $this->pdo->prepare($sql);
        return $st->execute([
            'id'                => $id,
            'titre'             => $data['titre'],
            'contenu'           => $data['contenu'],
            'date_publication'  => $data['date_publication'],
            'image'             => $data['image'] ?? '',
            'statut'            => $data['statut'] ?? 'publie',
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
            // Nettoyer les vues et likes associés à l'article supprimé
            $this->clearJsonStatsForArticle($id);
            return true;
        }
        $st = $this->pdo->prepare('DELETE FROM blog WHERE id_article = :id');
        return $st->execute(['id' => $id]);
    }

    /** Publier automatiquement les articles programmés dont la date est dépassée */
    public function publishScheduled(): int
    {
        if ($this->pdo === null) {
            $rows = $this->loadJsonArticles();
            $now = date('Y-m-d H:i:s');
            $count = 0;
            foreach ($rows as &$r) {
                if (($r['statut'] ?? '') === 'programme') {
                    // Normaliser la date stockée pour la comparaison
                    $pubDate = $this->normalizeDatetime((string) ($r['date_publication'] ?? ''));
                    if ($pubDate <= $now) {
                        $r['statut'] = 'publie';
                        $count++;
                    }
                }
            }
            unset($r);
            if ($count > 0) {
                $this->saveJsonArticles($rows);
            }
            return $count;
        }
        $st = $this->pdo->prepare(
            "UPDATE blog SET statut = 'publie' WHERE statut = 'programme' AND date_publication <= NOW()"
        );
        $st->execute();
        return $st->rowCount();
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
        if ($this->pdo === null) {
            // Fallback JSON — une IP = une vue par 24h
            $views = $this->loadJsonFile($this->viewsFile);
            $now = time();
            $cutoff = $now - 86400; // 24h
            // Vérifier si déjà vu dans les 24h
            foreach ($views as $v) {
                if ((int) ($v['article_id'] ?? 0) === $articleId
                    && ($v['ip'] ?? '') === $ip
                    && ($v['timestamp'] ?? 0) >= $cutoff) {
                    return; // Déjà compté
                }
            }
            $views[] = [
                'article_id' => $articleId,
                'ip'         => $ip,
                'timestamp'  => $now,
            ];
            $this->saveJsonFile($this->viewsFile, $views);
            return;
        }
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
        if ($this->pdo === null) {
            // Fallback JSON
            $likes = $this->loadJsonFile($this->likesFile);
            // Chercher un like existant
            foreach ($likes as $k => $l) {
                if ((int) ($l['article_id'] ?? 0) === $articleId && ($l['ip'] ?? '') === $ip) {
                    // Unlike — supprimer
                    array_splice($likes, $k, 1);
                    $this->saveJsonFile($this->likesFile, $likes);
                    return false;
                }
            }
            // Like — ajouter
            $likes[] = [
                'article_id' => $articleId,
                'ip'         => $ip,
                'timestamp'  => time(),
            ];
            $this->saveJsonFile($this->likesFile, $likes);
            return true;
        }
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
        if ($this->pdo === null) {
            $likes = $this->loadJsonFile($this->likesFile);
            foreach ($likes as $l) {
                if ((int) ($l['article_id'] ?? 0) === $articleId && ($l['ip'] ?? '') === $ip) {
                    return true;
                }
            }
            return false;
        }
        $st = $this->pdo->prepare('SELECT id FROM blog_likes WHERE article_id = :id AND ip_address = :ip LIMIT 1');
        $st->execute(['id' => $articleId, 'ip' => $ip]);
        return (bool) $st->fetch();
    }

    public function getStats(string $period = 'all'): array
    {
        if ($this->pdo === null) {
            // Fallback JSON — stats basiques
            $articles = $this->loadJsonArticles();
            $topViews = [];
            $topLikes = [];
            foreach ($articles as $a) {
                $aid = (int) ($a['id_article'] ?? 0);
                $topViews[] = ['titre' => $a['titre'] ?? '', 'total' => $this->countJsonViews($aid)];
                $topLikes[] = ['titre' => $a['titre'] ?? '', 'total' => $this->countJsonLikes($aid)];
            }
            usort($topViews, static fn($a, $b) => (int)$b['total'] <=> (int)$a['total']);
            usort($topLikes, static fn($a, $b) => (int)$b['total'] <=> (int)$a['total']);
            $topViews = array_slice($topViews, 0, 5);
            $topLikes = array_slice($topLikes, 0, 5);
            return [
                'topViews'    => $topViews,
                'topLikes'    => $topLikes,
                'chartLabels' => array_column($topViews, 'titre'),
                'chartData'   => array_column($topViews, 'total'),
            ];
        }

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

    // --- JSON Likes/Views helpers ---

    private function countJsonViews(int $articleId): int
    {
        $views = $this->loadJsonFile($this->viewsFile);
        $count = 0;
        foreach ($views as $v) {
            if ((int) ($v['article_id'] ?? 0) === $articleId) {
                $count++;
            }
        }
        return $count;
    }

    private function countJsonLikes(int $articleId): int
    {
        $likes = $this->loadJsonFile($this->likesFile);
        $count = 0;
        foreach ($likes as $l) {
            if ((int) ($l['article_id'] ?? 0) === $articleId) {
                $count++;
            }
        }
        return $count;
    }

    /** Supprimer toutes les vues et likes associés à un article (nettoyage après suppression) */
    private function clearJsonStatsForArticle(int $articleId): void
    {
        // Nettoyer les vues
        $views = $this->loadJsonFile($this->viewsFile);
        $views = array_values(array_filter($views, static fn(array $v): bool => (int) ($v['article_id'] ?? 0) !== $articleId));
        $this->saveJsonFile($this->viewsFile, $views);

        // Nettoyer les likes
        $likes = $this->loadJsonFile($this->likesFile);
        $likes = array_values(array_filter($likes, static fn(array $l): bool => (int) ($l['article_id'] ?? 0) !== $articleId));
        $this->saveJsonFile($this->likesFile, $likes);
    }

    /** Normaliser une date (accepte T et espace) en format comparable Y-m-d H:i:s */
    private function normalizeDatetime(string $date): string
    {
        // 2026-04-24T01:24 → 2026-04-24 01:24:00
        $d = DateTime::createFromFormat('Y-m-d\TH:i', $date)
          ?: DateTime::createFromFormat('Y-m-d H:i:s', $date)
          ?: DateTime::createFromFormat('Y-m-d H:i', $date)
          ?: DateTime::createFromFormat('Y-m-d', $date);
        return $d ? $d->format('Y-m-d H:i:s') : $date;
    }

    // --- JSON file helpers (generic) ---

    /** @return list<array<string,mixed>> */
    private function loadJsonFile(string $path): array
    {
        if (!is_file($path)) {
            return [];
        }
        $raw = (string) file_get_contents($path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    /** @param list<array<string,mixed>> $data */
    private function saveJsonFile(string $path, array $data): void
    {
        $dir = dirname($path);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $json = json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json !== false) {
            file_put_contents($path, $json . PHP_EOL);
        }
    }

    // --- Articles JSON storage ---

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
