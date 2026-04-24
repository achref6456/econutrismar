<?php

declare(strict_types=1);

class BlogController
{
    private Blog $model;

    public function __construct()
    {
        $this->model = new Blog();
    }

    public function index(): void
    {
        // Auto-publier les articles programmés dont la date est dépassée
        $this->model->publishScheduled();
        $articles = $this->model->findAllPublished();
        $pageTitle = 'Blog — EcoNutri';
        $uploadBase = '../../';
        require dirname(__DIR__) . '/View/frontoffice/blog_list.php';
    }

    public function show(): void
    {
        // Auto-publier les articles programmés dont la date est dépassée
        $this->model->publishScheduled();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            http_response_code(404);
            $pageTitle = 'Article introuvable';
            $error = 'Article introuvable.';
            $uploadBase = '../../';
            require dirname(__DIR__) . '/View/frontoffice/blog_error.php';
            return;
        }
        $article = $this->model->findById($id);
        // Bloquer l'accès aux articles non publiés
        if ($article === null || ($article['statut'] ?? 'publie') !== 'publie') {
            http_response_code(404);
            $pageTitle = 'Article introuvable';
            $error = 'Article introuvable.';
            $uploadBase = '../../';
            require dirname(__DIR__) . '/View/frontoffice/blog_error.php';
            return;
        }
        $hasLiked = $this->model->hasLiked($id, $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $pageTitle = htmlspecialchars($article['titre']) . ' — Blog';
        $uploadBase = '../../';
        require dirname(__DIR__) . '/View/frontoffice/blog_show.php';
    }

    public function search(): void
    {
        $q = isset($_GET['q']) ? (string) $_GET['q'] : '';
        $errors = [];
        $q = trim($q);
        if ($q !== '' && mb_strlen($q) < 2) {
            $errors[] = 'La recherche doit contenir au moins 2 caractères.';
        }
        $articles = $errors === [] && $q !== '' ? $this->model->search($q) : [];
        $pageTitle = 'Recherche — Blog EcoNutri';
        $uploadBase = '../../';
        require dirname(__DIR__) . '/View/frontoffice/blog_search.php';
    }
}
