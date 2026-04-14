<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/Model/bootstrap.php';

$allow = (bool) (($config['app']['dev_blog_admin_bypass'] ?? false));
if (!$allow) {
    header('Location: login.php', true, 302);
    exit;
}

$user = new User();
$admin = $user->findFirstAdmin();
if ($admin === null) {
    header('Location: login.php', true, 302);
    exit;
}

$_SESSION['admin_id'] = (int) $admin['id_user'];
$_SESSION['admin_name'] = trim((string) (($admin['prenom'] ?? '') . ' ' . ($admin['nom'] ?? '')));

header('Location: index.php', true, 302);
exit;
