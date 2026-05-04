<?php
require_once __DIR__ . '/../Model/User.php';

class UserController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
        $this->requireAdmin();
    }

    private function requireAdmin(): void {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index(): void {
        // Récupérer les paramètres de recherche et tri
        $keyword = trim($_GET['search'] ?? '');
        $tri     = $_GET['tri']         ?? 'id';
        $ordre   = $_GET['ordre']       ?? 'ASC';

        // Recherche ou liste complète
        if ($keyword !== '') {
            $users = $this->userModel->search($keyword, $tri, $ordre);
        } else {
            $users = $this->userModel->search('', $tri, $ordre);
        }

        // Statistiques
        $stats = $this->userModel->getStats();

        require_once __DIR__ . '/../View/backoffice/users/index.php';
    }

    public function stats(): void {
        $stats = $this->userModel->getStats();
        require_once __DIR__ . '/../View/backoffice/users/stats.php';
    }

    public function create(): void {
        $errors = [];
        require_once __DIR__ . '/../View/backoffice/users/form.php';
    }

    public function store(): void {
        $errors = [];

        $nom      = trim($_POST['nom']      ?? '');
        $prenom   = trim($_POST['prenom']   ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $role     = $_POST['role']          ?? 'user';

        if ($nom === '')    $errors['nom']    = "Le nom est obligatoire.";
        if ($prenom === '') $errors['prenom'] = "Le prénom est obligatoire.";

        if ($email === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = "Cet email est déjà utilisé.";
        }

        if ($password === '' || strlen($password) < 8) {
            $errors['password'] = "Mot de passe requis (min. 8 caractères).";
        }

        if (!in_array($role, ['user', 'admin'])) {
            $errors['role'] = "Rôle invalide.";
        }

        if (empty($errors)) {
            $this->userModel->create($nom, $prenom, $email, $password, $role);
            $_SESSION['success'] = "Utilisateur ajouté avec succès.";
            header('Location: index.php?page=users');
            exit;
        }

        require_once __DIR__ . '/../View/backoffice/users/form.php';
    }

    public function edit(): void {
        $id   = (int)($_GET['id'] ?? 0);
        $user = $this->userModel->findById($id);
        if (!$user) {
            header('Location: index.php?page=users');
            exit;
        }
        $errors = [];
        require_once __DIR__ . '/../View/backoffice/users/form.php';
    }

    public function update(): void {
        $id     = (int)($_POST['id'] ?? 0);
        $errors = [];

        $nom    = trim($_POST['nom']    ?? '');
        $prenom = trim($_POST['prenom'] ?? '');
        $email  = trim($_POST['email']  ?? '');
        $role   = $_POST['role']        ?? 'user';

        if ($nom === '')    $errors['nom']    = "Le nom est obligatoire.";
        if ($prenom === '') $errors['prenom'] = "Le prénom est obligatoire.";

        if ($email === '') {
            $errors['email'] = "L'email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        } else {
            $existing = $this->userModel->findByEmail($email);
            if ($existing && (int)$existing['id'] !== $id) {
                $errors['email'] = "Cet email est déjà utilisé.";
            }
        }

        if (!in_array($role, ['user', 'admin'])) {
            $errors['role'] = "Rôle invalide.";
        }

        // Gestion avatar
        $avatar = null;
        if (!empty($_POST['remove_avatar'])) {
            // Supprimer la photo
            $currentUser = $this->userModel->findById($id);
            if (!empty($currentUser['avatar'])) {
                $oldFile = __DIR__ . '/../uploads/avatars/' . $currentUser['avatar'];
                if (file_exists($oldFile)) unlink($oldFile);
            }
            $avatar = '';  // vide = supprimer en BDD
        } elseif (!empty($_FILES['avatar']['name'])) {
            $ext      = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ext, $allowed)) {
                $errors['avatar'] = "Format non autorisé (jpg, png, gif, webp).";
            } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                $errors['avatar'] = "Image trop grande (max 2 Mo).";
            } else {
                $filename = 'avatar_' . $id . '_' . time() . '.' . $ext;
                $dest     = __DIR__ . '/../uploads/avatars/' . $filename;
                if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                    $avatar = $filename;
                }
            }
        }

        if (empty($errors)) {
            $this->userModel->update($id, $nom, $prenom, $email, $role, $avatar);
            $_SESSION['success'] = "Utilisateur modifié avec succès.";
            header('Location: index.php?page=users');
            exit;
        }

        $user = $this->userModel->findById($id);
        require_once __DIR__ . '/../View/backoffice/users/form.php';
    }

    public function ban(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas vous bannir vous-même.";
            header('Location: index.php?page=users');
            exit;
        }
        $this->userModel->ban($id, 3);
        $_SESSION['success'] = "Utilisateur banni pour 3 jours.";
        header('Location: index.php?page=users');
        exit;
    }

    public function unban(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->userModel->unban($id);
        $_SESSION['success'] = "Utilisateur débanni avec succès.";
        header('Location: index.php?page=users');
        exit;
    }

    public function delete(): void {
        $id = (int)($_GET['id'] ?? 0);

        if ($id === (int)$_SESSION['user_id']) {
            $_SESSION['error'] = "Vous ne pouvez pas supprimer votre propre compte.";
            header('Location: index.php?page=users');
            exit;
        }

        $this->userModel->delete($id);
        $_SESSION['success'] = "Utilisateur supprimé.";
        header('Location: index.php?page=users');
        exit;
    }
}
