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
                // Vérifier si l'utilisateur est banni
                if (!empty($user['banned_until']) && strtotime($user['banned_until']) > time()) {
                    $jours = ceil((strtotime($user['banned_until']) - time()) / 86400);
                    $errors['global'] = "Votre compte est banni pour encore {$jours} jour(s) (jusqu'au " . date('d/m/Y', strtotime($user['banned_until'])) . ").";
                } else {
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_nom']  = $user['nom'];
                    $_SESSION['user_role'] = $user['role'];

                    // Remember Me — cookie valable 30 jours
                    if (!empty($_POST['remember'])) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_email', $email,  time() + (30 * 24 * 3600), '/');
                        setcookie('remember_token', $token,  time() + (30 * 24 * 3600), '/');
                    } else {
                        setcookie('remember_email', '', time() - 3600, '/');
                        setcookie('remember_token', '', time() - 3600, '/');
                    }

                    if ($user['role'] === 'admin' && !isset($_POST['modal'])) {
                        header('Location: index.php?page=backoffice');
                    } else {
                        header('Location: index.php?page=frontoffice');
                    }
                    exit;
                }
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
            // Gestion avatar à l'inscription
            $avatar = null;
            if (!empty($_FILES['avatar']['name'])) {
                $ext     = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($ext, $allowed) && $_FILES['avatar']['size'] <= 2 * 1024 * 1024) {
                    $filename = 'avatar_' . time() . '_' . rand(1000,9999) . '.' . $ext;
                    $dest     = __DIR__ . '/../uploads/avatars/' . $filename;
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $dest)) {
                        $avatar = $filename;
                    }
                }
            }

            $this->userModel->create($nom, $prenom, $email, $password, 'user', $avatar);

            // Envoyer email de bienvenue
            require_once __DIR__ . '/../config/mailer.php';
            sendWelcomeEmail($email, $prenom . ' ' . $nom);

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

    public function resetPasswordAjax(): void {
        header('Content-Type: application/json');
        $email = trim($_POST['email'] ?? '');

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Format d\'email invalide.']);
            exit;
        }

        if (!$this->userModel->emailExists($email)) {
            echo json_encode(['success' => false, 'message' => 'Aucun compte trouvé avec cet email.']);
            exit;
        }

        $newPwd = 'Eco' . rand(1000, 9999) . '!';
        $this->userModel->updatePassword($email, $newPwd);
        echo json_encode(['success' => true, 'pwd' => $newPwd]);
        exit;
    }

    // ── Mot de passe oublié ───────────────────────────────────────────────
    public function showForgotPassword(): void {
        $errors  = [];
        $success = '';
        $newPwd  = '';
        require_once __DIR__ . '/../View/auth/forgot-password.php';
    }

    public function resetPassword(): void {
        $errors  = [];
        $success = '';
        $newPwd  = '';

        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $errors['email'] = "L'adresse email est obligatoire.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format d'email invalide.";
        } elseif (!$this->userModel->emailExists($email)) {
            $errors['email'] = "Aucun compte trouvé avec cet email.";
        }

        if (empty($errors)) {
            // Générer un mot de passe temporaire
            $newPwd = 'Eco' . rand(1000, 9999) . '!';
            $this->userModel->updatePassword($email, $newPwd);
            $success = "Votre nouveau mot de passe temporaire est :";
        }

        require_once __DIR__ . '/../View/auth/forgot-password.php';
    }

    // ── Déconnexion ───────────────────────────────────────────────────────
    public function logout(): void {
        $role = $_SESSION['user_role'] ?? 'user';
        session_destroy();
        if ($role === 'admin') {
            header('Location: index.php?page=login');
        } else {
            header('Location: index.php?page=frontoffice');
        }
        exit;
    }
}
