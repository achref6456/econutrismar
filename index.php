<?php
session_start();

require_once __DIR__ . '/Controller/AuthController.php';

$page   = $_GET['page']   ?? 'frontoffice';
$action = $_GET['action'] ?? 'index';

switch ($page) {

    // ── Pages publiques ───────────────────────────────────────────────────
    case 'login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'forgot-password':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->resetPassword();
        } else {
            $controller->showForgotPassword();
        }
        break;

    case 'forgot-ajax':
        $controller = new AuthController();
        $controller->resetPasswordAjax();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    // ── Back office (admin requis) ────────────────────────────────────────
    case 'backoffice':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/View/backoffice/index.html';
        break;

    // ── CRUD Utilisateurs (admin requis) ──────────────────────────────────
    case 'users':
        require_once __DIR__ . '/Controller/UserController.php';
        $controller = new UserController();
        switch ($action) {
            case 'create': $controller->create(); break;
            case 'store':  $controller->store();  break;
            case 'edit':   $controller->edit();   break;
            case 'update': $controller->update(); break;
            case 'delete': $controller->delete(); break;
            case 'stats':  $controller->stats();  break;
            case 'ban':    $controller->ban();    break;
            case 'unban':  $controller->unban();  break;
            default:       $controller->index();  break;
        }
        break;

    // ── Front office ──────────────────────────────────────────────────────
    case 'frontoffice':
    default:
        $loginErrors    = [];
        $loginPost      = [];
        $registerErrors = [];
        $registerPost   = [];
        // Date d'inscription de l'utilisateur connecté
        $user_joined = '';
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/Model/User.php';
            $userModel   = new User();
            $currentUser = $userModel->findById((int)$_SESSION['user_id']);
            if ($currentUser) $user_joined = $currentUser['created_at'];
        }
        require_once __DIR__ . '/View/frontoffice/index.php';
        break;

    case 'profil':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once __DIR__ . '/Controller/ProfilController.php';
        $controller = new ProfilController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->update();
        } else {
            $controller->show();
        }
        break;
}
