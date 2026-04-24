<?php

declare(strict_types=1);

class AdminBlogController
{
    private Blog $model;

    public function __construct()
    {
        $this->model = new Blog();
    }

    public function requireAuth(): void
    {
        if (empty($_SESSION['admin_id'])) {
            header('Location: login.php');
            exit;
        }
    }

    public function index(): void
    {
        $this->requireAuth();
        // Auto-publier les articles programmés dont la date est dépassée
        $this->model->publishScheduled();
        $articles = $this->model->findAllForAdmin();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $pageTitle = 'Gestion du blog';
        $assetBase = '../';
        require dirname(__DIR__) . '/View/backoffice/blog_admin_list.php';
    }

    public function stats(): void
    {
        $this->requireAuth();
        $pageTitle = 'Statistiques du blog';
        $assetBase = '../';
        require dirname(__DIR__) . '/View/backoffice/blog_admin_stats.php';
    }

    public function commentaires(): void
    {
        $this->requireAuth();
        $commentModel = new Commentaire();
        $pendingCount = $commentModel->countPending();
        $pageTitle = 'Modération des commentaires';
        $assetBase = '../';
        require dirname(__DIR__) . '/View/backoffice/blog_admin_commentaires.php';
    }

    public function createForm(): void
    {
        $this->requireAuth();
        $article = null;
        $errors = $_SESSION['form_errors'] ?? [];
        $old = $_SESSION['form_old'] ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);
        if ($old !== []) {
            $article = [
                'titre'            => $old['titre'] ?? '',
                'contenu'          => $old['contenu'] ?? '',
                'date_publication' => $old['date_publication'] ?? '',
                'image'            => $old['image'] ?? '',
                'statut'           => $old['statut'] ?? 'publie',
            ];
        }
        $pageTitle = 'Nouvel article';
        $assetBase = '../';
        require dirname(__DIR__) . '/View/backoffice/blog_admin_form.php';
    }

    public function store(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: create.php');
            exit;
        }
        $data = $this->sanitizeInput();
        $errors = $this->validateArticle($data);
        $imagePath = $this->handleUpload($errors);

        if ($errors !== []) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = $_POST;
            header('Location: create.php');
            exit;
        }

        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        }

        $data['user_id'] = (int) $_SESSION['admin_id'];
        $this->model->create($data);

        $flashMsgs = [
            'publie'    => 'Article publié avec succès.',
            'brouillon' => 'Article enregistré comme brouillon.',
            'programme' => 'Article programmé pour publication le ' . $data['date_publication'] . '.',
        ];
        $_SESSION['flash'] = $flashMsgs[$data['statut']] ?? 'Article enregistré.';
        header('Location: index.php');
        exit;
    }

    public function editForm(): void
    {
        $this->requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            header('Location: index.php');
            exit;
        }
        $article = $this->model->findById($id);
        if ($article === null) {
            $_SESSION['flash'] = 'Article introuvable.';
            header('Location: index.php');
            exit;
        }
        $errors = $_SESSION['form_errors'] ?? [];
        $old = $_SESSION['form_old'] ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_old']);
        if ($old !== []) {
            $article = array_merge($article, [
                'titre'            => $old['titre'] ?? $article['titre'],
                'contenu'          => $old['contenu'] ?? $article['contenu'],
                'date_publication' => $old['date_publication'] ?? $article['date_publication'],
                'image'            => $old['image'] ?? $article['image'],
                'statut'           => $old['statut'] ?? $article['statut'] ?? 'publie',
            ]);
        }
        $pageTitle = 'Modifier l’article';
        $assetBase = '../';
        require dirname(__DIR__) . '/View/backoffice/blog_admin_form.php';
    }

    public function update(): void
    {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }
        $id = isset($_POST['id_article']) ? (int) $_POST['id_article'] : 0;
        if ($id < 1) {
            header('Location: index.php');
            exit;
        }
        $existing = $this->model->findById($id);
        if ($existing === null) {
            $_SESSION['flash'] = 'Article introuvable.';
            header('Location: index.php');
            exit;
        }

        $data = $this->sanitizeInput();
        $errors = $this->validateArticle($data);
        $imagePath = $this->handleUpload($errors);
        if ($imagePath !== null) {
            $data['image'] = $imagePath;
        } else {
            $data['image'] = (string) ($existing['image'] ?? '');
        }

        if ($errors !== []) {
            $_SESSION['form_errors'] = $errors;
            $_SESSION['form_old'] = $_POST;
            header('Location: edit.php?id=' . $id);
            exit;
        }

        $this->model->update($id, $data);
        $_SESSION['flash'] = 'Article mis à jour.';
        header('Location: index.php');
        exit;
    }

    public function delete(): void
    {
        $this->requireAuth();
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id < 1) {
            header('Location: index.php');
            exit;
        }
        $this->model->delete($id);
        $_SESSION['flash'] = 'Article supprimé.';
        header('Location: index.php');
        exit;
    }

    /** @return array{titre:string,contenu:string,date_publication:string,image:string,statut:string} */
    private function sanitizeInput(): array
    {
        return [
            'titre'            => trim((string) ($_POST['titre'] ?? '')),
            'contenu'          => trim((string) ($_POST['contenu'] ?? '')),
            'date_publication' => trim((string) ($_POST['date_publication'] ?? '')),
            'image'            => trim((string) ($_POST['image'] ?? '')),
            'statut'           => trim((string) ($_POST['statut'] ?? 'publie')),
        ];
    }

    /** @param array{titre:string,contenu:string,date_publication:string,image:string,statut:string} $data */
    /** @return list<string> */
    private function validateArticle(array $data): array
    {
        $errors = [];
        if ($data['titre'] === '' || mb_strlen($data['titre']) < 3) {
            $errors[] = 'Le titre doit contenir au moins 3 caractères.';
        }
        if ($data['contenu'] === '' || mb_strlen($data['contenu']) < 10) {
            $errors[] = 'Le contenu doit contenir au moins 10 caractères.';
        }
        if ($data['date_publication'] === '') {
            $errors[] = 'La date de publication est obligatoire.';
        } else {
            // Accepter le format datetime-local (Y-m-d\TH:i) ou datetime complet (Y-m-d H:i:s)
            $d = DateTime::createFromFormat('Y-m-d\TH:i', $data['date_publication'])
              ?: DateTime::createFromFormat('Y-m-d H:i:s', $data['date_publication'])
              ?: DateTime::createFromFormat('Y-m-d', $data['date_publication']);
            if (!$d) {
                $errors[] = 'Date de publication invalide.';
            }
        }
        // Valider le statut
        $statutsValides = ['brouillon', 'programme', 'publie'];
        if (!in_array($data['statut'], $statutsValides, true)) {
            $errors[] = 'Statut invalide.';
        }
        // Si programmé : la date doit être dans le futur (tolérance de 5 min)
        if ($data['statut'] === 'programme' && isset($d) && $d instanceof DateTime) {
            $now = new DateTime('now');
            $now->modify('-5 minutes');
            if ($d <= $now) {
                $errors[] = 'La date de publication programmée doit être dans le futur.';
            }
        }
        return $errors;
    }

    /** @param list<string> $errors */
    private function handleUpload(array &$errors): ?string
    {
        if (!isset($_FILES['image_file']) || $_FILES['image_file']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        $f = $_FILES['image_file'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Erreur lors du téléversement de l’image.';
            return null;
        }
        $cfg = require dirname(__DIR__) . '/Model/config.php';
        $dir = $cfg['app']['upload_dir'];
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $info = @getimagesize($f['tmp_name']);
        $mime = $info['mime'] ?? '';
        if (!isset($allowed[$mime])) {
            $errors[] = 'Image : formats acceptés JPG, PNG, WebP.';
            return null;
        }
        $ext = $allowed[$mime];
        $name = 'blog_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $dest = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $name;
        if (!move_uploaded_file($f['tmp_name'], $dest)) {
            $errors[] = 'Impossible d’enregistrer l’image.';
            return null;
        }
        return 'uploads/blog/' . $name;
    }
}
