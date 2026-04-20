<?php

declare(strict_types=1);

class ApiBlogController
{
    private Blog $model;

    public function __construct()
    {
        $this->model = new Blog();
    }

    public function vue(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ID']);
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->model->trackView($id, $ip);

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function like(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ID']);
            return;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $liked = $this->model->toggleLike($id, $ip);

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'liked' => $liked]);
    }

    public function stats(): void
    {
        $period = isset($_GET['period']) ? (string) $_GET['period'] : 'all';
        $stats = $this->model->getStats($period);

        header('Content-Type: application/json');
        echo json_encode($stats);
    }

    /* ═══════════════════════════════════════════════════════
     *  Commentaires — Visiteur
     * ═══════════════════════════════════════════════════════ */

    /** POST — Soumettre un commentaire (statut = en_attente) */
    public function submitComment(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method Not Allowed']);
            return;
        }

        $articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($articleId < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'ID article invalide']);
            return;
        }

        // Lire le corps JSON ou les données POST
        $input = json_decode((string) file_get_contents('php://input'), true);
        $pseudo  = trim((string) ($input['pseudo']  ?? ($_POST['pseudo']  ?? '')));
        $contenu = trim((string) ($input['contenu'] ?? ($_POST['contenu'] ?? '')));

        $errors = [];
        if ($pseudo === '' || mb_strlen($pseudo) < 2) {
            $errors[] = 'Le pseudo doit contenir au moins 2 caractères.';
        }
        if (mb_strlen($pseudo) > 100) {
            $errors[] = 'Le pseudo ne doit pas dépasser 100 caractères.';
        }
        if ($contenu === '' || mb_strlen($contenu) < 3) {
            $errors[] = 'Le commentaire doit contenir au moins 3 caractères.';
        }

        if ($errors !== []) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        $commentModel = new Commentaire();
        $id = $commentModel->create($articleId, $pseudo, $contenu);

        echo json_encode(['success' => true, 'id' => $id]);
    }

    /** GET — Récupérer les commentaires approuvés d'un article */
    public function getComments(): void
    {
        header('Content-Type: application/json');

        $articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($articleId < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'ID article invalide']);
            return;
        }

        $commentModel = new Commentaire();
        $comments = $commentModel->findApprovedByArticle($articleId);

        echo json_encode(['success' => true, 'comments' => $comments]);
    }

    /* ═══════════════════════════════════════════════════════
     *  Commentaires — Admin
     * ═══════════════════════════════════════════════════════ */

    /** GET — Tous les commentaires (avec filtres optionnels) */
    public function adminGetComments(): void
    {
        header('Content-Type: application/json');

        $statut    = isset($_GET['statut']) && $_GET['statut'] !== '' ? (string) $_GET['statut'] : null;
        $articleId = isset($_GET['article_id']) && $_GET['article_id'] !== '' ? (int) $_GET['article_id'] : null;

        $commentModel = new Commentaire();
        $comments = $commentModel->findAll($statut, $articleId);
        $articles = $commentModel->getArticleList();

        echo json_encode([
            'success'  => true,
            'comments' => $comments,
            'articles' => $articles,
        ]);
    }

    /** PUT — Approuver un commentaire */
    public function adminApprove(): void
    {
        header('Content-Type: application/json');

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'ID invalide']);
            return;
        }

        $commentModel = new Commentaire();
        $commentModel->approve($id);

        echo json_encode(['success' => true]);
    }

    /** PUT — Refuser un commentaire */
    public function adminRefuse(): void
    {
        header('Content-Type: application/json');

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'ID invalide']);
            return;
        }

        $commentModel = new Commentaire();
        $commentModel->refuse($id);

        echo json_encode(['success' => true]);
    }

    /** DELETE — Supprimer un commentaire */
    public function adminDelete(): void
    {
        header('Content-Type: application/json');

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(400);
            echo json_encode(['error' => 'ID invalide']);
            return;
        }

        $commentModel = new Commentaire();
        $commentModel->delete($id);

        echo json_encode(['success' => true]);
    }

    /** GET — Nombre de commentaires en attente (pour le badge) */
    public function adminPendingCount(): void
    {
        header('Content-Type: application/json');

        $commentModel = new Commentaire();
        $count = $commentModel->countPending();

        echo json_encode(['success' => true, 'count' => $count]);
    }
}
