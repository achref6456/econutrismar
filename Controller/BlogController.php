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
        $articles = $this->model->findAllPublished();
        $pageTitle = 'Blog — EcoNutri';
        $uploadBase = '../../';
        require dirname(__DIR__) . '/View/frontoffice/blog_list.php';
    }

    public function show(): void
    {
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
        if ($article === null) {
            http_response_code(404);
            $pageTitle = 'Article introuvable';
            $error = 'Article introuvable.';
            $uploadBase = '../../';
            require dirname(__DIR__) . '/View/frontoffice/blog_error.php';
            return;
        }
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
