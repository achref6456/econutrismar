<?php
require_once __DIR__ . '/../Model/User.php';

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    // ── Afficher le formulaire de connexion ───────────────────────────────
    public function showLogin(): void {
        $errors = [];
        require_once __DIR__ . '/../View/auth/login.php';
    }

    // ── Traiter la connexion ──────────────────────────────────────────────
    public function login(): void {
        $errors = [];

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password']  ?? '';

        // Validation côté serveur — pas de HTML5
        if ($email === '') {
            $errors['email'] = "L'adresse email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        }

        if ($password === '') {
            $errors['password'] = "Le mot de passe est obligatoire.";
        }

        if (empty($errors)) {
            $user = $this->userModel->verifyCredentials($email, $password);
            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_nom']  = $user['nom'];
                $_SESSION['user_role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header('Location: index.php?page=backoffice');
                } else {
                    header('Location: index.php?page=frontoffice');
                }
                exit;
            } else {
                $errors['global'] = "Email ou mot de passe incorrect.";
            }
        }

        // Si soumis depuis la modal du frontoffice, repasser par la vue frontoffice
        if (isset($_POST['modal'])) {
            $loginErrors = $errors;
            $loginPost   = $_POST;
            require_once __DIR__ . '/../View/frontoffice/index.php';
        } else {
            require_once __DIR__ . '/../View/auth/login.php';
        }
    }

    // ── Afficher le formulaire d'inscription ──────────────────────────────
    public function showRegister(): void {
        $errors = [];
        require_once __DIR__ . '/../View/auth/register.php';
    }

    // ── Traiter l'inscription ─────────────────────────────────────────────
    public function register(): void {
        $errors = [];

        $nom      = trim($_POST['nom']              ?? '');
        $prenom   = trim($_POST['prenom']           ?? '');
        $email    = trim($_POST['email']            ?? '');
        $password = $_POST['password']              ?? '';
        $confirm  = $_POST['confirm_password']      ?? '';

        if ($nom === '') {
            $errors['nom'] = "Le nom est obligatoire.";
        } elseif (strlen($nom) < 2) {
            $errors['nom'] = "Le nom doit contenir au moins 2 caractères.";
        }

        if ($prenom === '') {
            $errors['prenom'] = "Le prénom est obligatoire.";
        } elseif (strlen($prenom) < 2) {
            $errors['prenom'] = "Le prénom doit contenir au moins 2 caractères.";
        }

        if ($email === '') {
            $errors['email'] = "L'adresse email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        } elseif ($this->userModel->emailExists($email)) {
            $errors['email'] = "Cet email est déjà utilisé.";
        }

        if ($password === '') {
            $errors['password'] = "Le mot de passe est obligatoire.";
        } elseif (strlen($password) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères.";
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins une majuscule.";
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = "Le mot de passe doit contenir au moins un chiffre.";
        }

        if ($confirm === '') {
            $errors['confirm_password'] = "Veuillez confirmer votre mot de passe.";
        } elseif ($password !== $confirm) {
            $errors['confirm_password'] = "Les mots de passe ne correspondent pas.";
        }

        if (empty($errors)) {
            $this->userModel->create($nom, $prenom, $email, $password);
            if (isset($_POST['modal'])) {
                $_SESSION['success'] = "Compte créé ! Vous pouvez maintenant vous connecter.";
                header('Location: index.php?page=frontoffice');
            } else {
                $_SESSION['success'] = "Compte créé avec succès ! Vous pouvez vous connecter.";
                header('Location: index.php?page=login');
            }
            exit;
        }

        // Si soumis depuis la modal du frontoffice, repasser par la vue frontoffice
        if (isset($_POST['modal'])) {
            $registerErrors = $errors;
            $registerPost   = $_POST;
            require_once __DIR__ . '/../View/frontoffice/index.php';
        } else {
            require_once __DIR__ . '/../View/auth/register.php';
        }
    }

    // ── Déconnexion ───────────────────────────────────────────────────────
    public function logout(): void {
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
