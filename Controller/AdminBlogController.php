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
        $tri = isset($_GET['tri']) ? trim((string) $_GET['tri']) : 'date_desc';
        $articles = $this->model->findAllForAdmin(null, $tri);
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        $pageTitle = 'Gestion du blog';
        $assetBase = '../';
        $sortCurrent = $tri;
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
        $this->model->ensureQuizTokensForAll();
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
        $quizForm = $this->buildQuizFormStateFromRequestOrArticle($old, $article);
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
        $quizParsed = $this->parseQuizFromRequest();
        $errors = array_merge($errors, $quizParsed['errors']);
        $data['quiz_json'] = $quizParsed['json'];
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
        $this->model->ensureQuizTokensForAll();
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
        $quizForm = $this->buildQuizFormStateFromRequestOrArticle($old, $article);
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
        $quizParsed = $this->parseQuizFromRequest();
        $errors = array_merge($errors, $quizParsed['errors']);
        $data['quiz_json'] = $quizParsed['json'];
        $data['quiz_token'] = trim((string) ($existing['quiz_token'] ?? ''));
        if ($data['quiz_token'] === '') {
            $data['quiz_token'] = bin2hex(random_bytes(16));
        }
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

    /**
     * @param array<string,mixed> $old
     * @param array<string,mixed>|null $article
     *
     * @return array<int, array{text:string,c0:string,c1:string,c2:string,correct:int}>
     */
    private function buildQuizFormStateFromRequestOrArticle(array $old, ?array $article): array
    {
        $blank = static fn(): array => ['text' => '', 'c0' => '', 'c1' => '', 'c2' => '', 'correct' => 0];
        $state = [1 => $blank(), 2 => $blank(), 3 => $blank()];
        if ($old !== []) {
            for ($i = 1; $i <= 3; $i++) {
                $state[$i]['text'] = trim((string) ($old['quiz_q' . $i . '_text'] ?? ''));
                $state[$i]['c0'] = trim((string) ($old['quiz_q' . $i . '_c0'] ?? ''));
                $state[$i]['c1'] = trim((string) ($old['quiz_q' . $i . '_c1'] ?? ''));
                $state[$i]['c2'] = trim((string) ($old['quiz_q' . $i . '_c2'] ?? ''));
                $state[$i]['correct'] = (int) ($old['quiz_q' . $i . '_correct'] ?? 0);
            }

            return $state;
        }
        if ($article !== null) {
            $raw = (string) ($article['quiz_json'] ?? '');
            $j = json_decode($raw, true);
            if (is_array($j) && count($j) === 3) {
                foreach ([1, 2, 3] as $idx => $num) {
                    $qi = $j[$idx];
                    if (!is_array($qi)) {
                        continue;
                    }
                    $ch = $qi['choices'] ?? [];
                    $state[$num]['text'] = (string) ($qi['question'] ?? '');
                    $state[$num]['c0'] = (string) ($ch[0] ?? '');
                    $state[$num]['c1'] = (string) ($ch[1] ?? '');
                    $state[$num]['c2'] = (string) ($ch[2] ?? '');
                    $state[$num]['correct'] = (int) ($qi['correct'] ?? 0);
                }
            }
        }

        return $state;
    }

    /** @return array{errors: list<string>, json: ?string} */
    private function parseQuizFromRequest(): array
    {
        $blocks = [];
        for ($i = 1; $i <= 3; $i++) {
            $text = trim((string) ($_POST['quiz_q' . $i . '_text'] ?? ''));
            $c0 = trim((string) ($_POST['quiz_q' . $i . '_c0'] ?? ''));
            $c1 = trim((string) ($_POST['quiz_q' . $i . '_c1'] ?? ''));
            $c2 = trim((string) ($_POST['quiz_q' . $i . '_c2'] ?? ''));
            $corr = (int) ($_POST['quiz_q' . $i . '_correct'] ?? 0);
            $blocks[$i] = ['text' => $text, 'c0' => $c0, 'c1' => $c1, 'c2' => $c2, 'corr' => $corr];
        }
        $started = 0;
        foreach ($blocks as $i => $b) {
            $any = $b['text'] !== '' || $b['c0'] !== '' || $b['c1'] !== '' || $b['c2'] !== '';
            if (!$any) {
                continue;
            }
            $started++;
            if ($b['text'] === '' || $b['c0'] === '' || $b['c1'] === '' || $b['c2'] === '') {
                return [
                    'errors' => ['Quiz : la question ' . $i . ' est incomplète (énoncé et 3 réponses obligatoires).'],
                    'json'   => null,
                ];
            }
            if ($b['corr'] < 0 || $b['corr'] > 2) {
                return [
                    'errors' => ['Quiz : bonne réponse invalide pour la question ' . $i . '.'],
                    'json'   => null,
                ];
            }
        }
        if ($started === 0) {
            return ['errors' => [], 'json' => null];
        }
        if ($started !== 3) {
            return [
                'errors' => ['Quiz : renseignez les 3 questions ou laissez tout le bloc quiz vide.'],
                'json'   => null,
            ];
        }
        $questions = [];
        for ($i = 1; $i <= 3; $i++) {
            $b = $blocks[$i];
            $questions[] = [
                'question' => $b['text'],
                'choices'  => [$b['c0'], $b['c1'], $b['c2']],
                'correct'  => (int) $b['corr'],
            ];
        }
        $json = json_encode($questions, JSON_UNESCAPED_UNICODE);

        return ['errors' => [], 'json' => $json !== false ? $json : null];
    }
}
