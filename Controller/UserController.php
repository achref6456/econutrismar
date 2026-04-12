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
        $users = $this->userModel->findAll();
        require_once __DIR__ . '/../View/backoffice/users/index.php';
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

        if (empty($errors)) {
            $this->userModel->update($id, $nom, $prenom, $email, $role);
            $_SESSION['success'] = "Utilisateur modifié avec succès.";
            header('Location: index.php?page=users');
            exit;
        }

        $user = $this->userModel->findById($id);
        require_once __DIR__ . '/../View/backoffice/users/form.php';
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
