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

    case 'face-login':
        require_once __DIR__ . '/View/auth/face-login.php';
        break;

    case 'face-login-process':
        $userId = (int)($_POST['user_id'] ?? 0);
        if ($userId > 0) {
            require_once __DIR__ . '/Model/User.php';
            $userModel = new User();
            $user = $userModel->findById($userId);
            if ($user && $user['role'] === 'admin') {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_nom']  = $user['nom'];
                $_SESSION['user_role'] = $user['role'];
                header('Location: index.php?page=backoffice');
                exit;
            }
        }
        header('Location: index.php?page=login');
        exit;
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
        require_once __DIR__ . '/View/backoffice/index.php';
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

    case 'carte':
        require_once __DIR__ . '/View/frontoffice/carte.php';
        break;

    // ── Blog (module Mohamed) ─────────────────────────────────────────────
    case 'blog':
        require_once __DIR__ . '/Model/bootstrap.php';
        require_once __DIR__ . '/Controller/BlogController.php';
        $controller = new BlogController();
        switch ($action) {
            case 'show':    $controller->show();    break;
            case 'search':  $controller->search();  break;
            case 'like':    $controller->like();    break;
            case 'comment': $controller->comment(); break;
            default:        $controller->index();   break;
        }
        break;

    // ── Blog Admin / Backoffice Blog ──────────────────────────────────────
    case 'blog_admin':
    case 'admin_blog':
        require_once __DIR__ . '/Model/bootstrap.php';
        require_once __DIR__ . '/Controller/AdminBlogController.php';
        $controller = new AdminBlogController();
        // Bypass dev : connecter automatiquement le premier admin en base
        $cfg = require __DIR__ . '/Model/config.php';
        if (!empty($cfg['app']['dev_blog_admin_bypass']) && empty($_SESSION['admin_id'])) {
            require_once __DIR__ . '/Model/Blog.php';
            $blogModel = new Blog();
            $_SESSION['admin_id'] = $blogModel->getDefaultAdminUserId();
        }
        switch ($action) {
            case 'create':       $controller->createForm(); break;
            case 'store':        $controller->store();      break;
            case 'edit':         $controller->editForm();   break;
            case 'update':       $controller->update();     break;
            case 'delete':       $controller->delete();     break;
            case 'stats':        $controller->stats();      break;
            case 'commentaires': $controller->commentaires(); break;
            default:             $controller->index();      break;
        }
        break;

    // ── Quiz (module Mohamed) ─────────────────────────────────────────────
    case 'quiz':
        require_once __DIR__ . '/Model/bootstrap.php';
        require_once __DIR__ . '/Controller/QuizController.php';
        $controller = new QuizController();
        $controller->show();
        break;

    // ── API Blog (AJAX) ───────────────────────────────────────────────────
    case 'api_blog':
        require_once __DIR__ . '/Model/bootstrap.php';
        require_once __DIR__ . '/Controller/ApiBlogController.php';
        $controller = new ApiBlogController();
        switch ($action) {
            case 'vue':                   $controller->vue();                break;
            case 'like':                  $controller->like();               break;
            case 'stats':                 $controller->stats();              break;
            case 'comment':               $controller->submitComment();      break;
            case 'comment_update':        $controller->updateComment();      break;
            case 'comments':              $controller->getComments();        break;
            case 'admin_comments':        $controller->adminGetComments();   break;
            case 'admin_approve':         $controller->adminApprove();       break;
            case 'admin_refuse':          $controller->adminRefuse();        break;
            case 'admin_delete':          $controller->adminDelete();        break;
            case 'admin_pending_count':   $controller->adminPendingCount();  break;
        }
        break;
}
