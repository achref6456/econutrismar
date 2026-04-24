<?php

declare(strict_types=1);

class Commentaire
{
    private ?PDO $pdo = null;
    private string $jsonFile;

    public function __construct()
    {
        $this->jsonFile = __DIR__ . '/storage/commentaires.json';
        try {
            $this->pdo = Database::getConnection();
        } catch (Throwable $e) {
            $this->pdo = null;
            $this->ensureJsonStorage();
        }
    }

    /* ─── Créer un commentaire (statut = en_attente) ─── */

    public function create(int $articleId, string $pseudo, string $contenu): int
    {
        if ($this->pdo === null) {
            $rows = $this->loadJson();
            $nextId = 1;
            foreach ($rows as $r) {
                $nextId = max($nextId, (int) ($r['id_commentaire'] ?? 0) + 1);
            }
            $rows[] = [
                'id_commentaire'   => $nextId,
                'article_id'       => $articleId,
                'pseudo'           => $pseudo,
                'contenu'          => $contenu,
                'date_commentaire' => date('Y-m-d H:i:s'),
                'statut'           => 'en_attente',
            ];
            $this->saveJson($rows);
            return $nextId;
        }
        $sql = 'INSERT INTO commentaire (article_id, pseudo, contenu, date_commentaire, statut)
                VALUES (:article_id, :pseudo, :contenu, NOW(), :statut)';
        $st = $this->pdo->prepare($sql);
        $st->execute([
            'article_id' => $articleId,
            'pseudo'     => $pseudo,
            'contenu'    => $contenu,
            'statut'     => 'en_attente',
        ]);
        return (int) $this->pdo->lastInsertId();
    }

    /* ─── Commentaires approuvés d'un article (frontend) ─── */

    /** @return list<array<string,mixed>> */
    public function findApprovedByArticle(int $articleId): array
    {
        if ($this->pdo === null) {
            return array_values(array_filter(
                $this->loadJson(),
                static fn(array $r): bool =>
                    (int) ($r['article_id'] ?? 0) === $articleId
                    && ($r['statut'] ?? '') === 'approuve'
            ));
        }
        $sql = 'SELECT * FROM commentaire
                WHERE article_id = :id AND statut = :statut
                ORDER BY date_commentaire DESC';
        $st = $this->pdo->prepare($sql);
        $st->execute(['id' => $articleId, 'statut' => 'approuve']);
        return $st->fetchAll();
    }

    /* ─── Tous les commentaires (backoffice, avec filtres) ─── */

    /** @return list<array<string,mixed>> */
    public function findAll(?string $statut = null, ?int $articleId = null): array
    {
        if ($this->pdo === null) {
            $rows = $this->loadJson();
            if ($statut !== null) {
                $rows = array_filter($rows, static fn(array $r): bool => ($r['statut'] ?? '') === $statut);
            }
            if ($articleId !== null) {
                $rows = array_filter($rows, static fn(array $r): bool => (int) ($r['article_id'] ?? 0) === $articleId);
            }
            usort($rows, static fn(array $a, array $b): int =>
                strcmp((string) ($b['date_commentaire'] ?? ''), (string) ($a['date_commentaire'] ?? ''))
            );
            // Joindre les titres d'articles depuis le fichier JSON du blog
            $blogFile = __DIR__ . '/storage/blog_articles.json';
            $titres = [];
            if (is_file($blogFile)) {
                $articles = json_decode((string) file_get_contents($blogFile), true);
                if (is_array($articles)) {
                    foreach ($articles as $a) {
                        $titres[(int) ($a['id_article'] ?? 0)] = (string) ($a['titre'] ?? '');
                    }
                }
            }
            $result = array_values($rows);
            foreach ($result as &$r) {
                $aid = (int) ($r['article_id'] ?? 0);
                $r['article_titre'] = $titres[$aid] ?? '';
            }
            unset($r);
            return $result;
        }

        $where = [];
        $params = [];

        if ($statut !== null) {
            $where[] = 'c.statut = :statut';
            $params['statut'] = $statut;
        }
        if ($articleId !== null) {
            $where[] = 'c.article_id = :article_id';
            $params['article_id'] = $articleId;
        }

        $sql = 'SELECT c.*, b.titre AS article_titre
                FROM commentaire c
                LEFT JOIN blog b ON b.id_article = c.article_id'
             . ($where !== [] ? ' WHERE ' . implode(' AND ', $where) : '')
             . ' ORDER BY c.date_commentaire DESC';

        $st = $this->pdo->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    /* ─── Nombre de commentaires en attente (badge) ─── */

    public function countPending(): int
    {
        if ($this->pdo === null) {
            return count(array_filter(
                $this->loadJson(),
                static fn(array $r): bool => ($r['statut'] ?? '') === 'en_attente'
            ));
        }
        $st = $this->pdo->query("SELECT COUNT(*) FROM commentaire WHERE statut = 'en_attente'");
        return (int) $st->fetchColumn();
    }

    /* ─── Actions admin ─── */

    public function approve(int $id): bool
    {
        return $this->setStatut($id, 'approuve');
    }

    public function refuse(int $id): bool
    {
        return $this->setStatut($id, 'refuse');
    }

    public function delete(int $id): bool
    {
        if ($this->pdo === null) {
            $rows = $this->loadJson();
            $before = count($rows);
            $rows = array_values(array_filter(
                $rows,
                static fn(array $r): bool => (int) ($r['id_commentaire'] ?? 0) !== $id
            ));
            if (count($rows) === $before) {
                return false;
            }
            $this->saveJson($rows);
            return true;
        }
        $st = $this->pdo->prepare('DELETE FROM commentaire WHERE id_commentaire = :id');
        return $st->execute(['id' => $id]);
    }

    /* ─── Liste des articles (pour le filtre backoffice) ─── */

    /** @return list<array{id_article:int,titre:string}> */
    public function getArticleList(): array
    {
        if ($this->pdo === null) {
            $blogFile = __DIR__ . '/storage/blog_articles.json';
            if (!is_file($blogFile)) {
                return [];
            }
            $articles = json_decode((string) file_get_contents($blogFile), true);
            if (!is_array($articles)) {
                return [];
            }
            $list = [];
            foreach ($articles as $a) {
                $list[] = [
                    'id_article' => (int) ($a['id_article'] ?? 0),
                    'titre'      => (string) ($a['titre'] ?? ''),
                ];
            }
            usort($list, static fn($a, $b) => strcmp($a['titre'], $b['titre']));
            return $list;
        }
        $st = $this->pdo->query('SELECT id_article, titre FROM blog ORDER BY titre ASC');
        return $st->fetchAll();
    }

    /* ─── Helpers internes ─── */

    private function setStatut(int $id, string $statut): bool
    {
        if ($this->pdo === null) {
            $rows = $this->loadJson();
            $found = false;
            foreach ($rows as &$r) {
                if ((int) ($r['id_commentaire'] ?? 0) === $id) {
                    $r['statut'] = $statut;
                    $found = true;
                    break;
                }
            }
            unset($r);
            if ($found) {
                $this->saveJson($rows);
            }
            return $found;
        }
        $st = $this->pdo->prepare('UPDATE commentaire SET statut = :statut WHERE id_commentaire = :id');
        return $st->execute(['statut' => $statut, 'id' => $id]);
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
    private function loadJson(): array
    {
        $this->ensureJsonStorage();
        $raw = (string) file_get_contents($this->jsonFile);
        $rows = json_decode($raw, true);
        return is_array($rows) ? $rows : [];
    }

    /** @param list<array<string,mixed>> $rows */
    private function saveJson(array $rows): void
    {
        $json = json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return;
        }
        file_put_contents($this->jsonFile, $json . PHP_EOL);
    }
}
