<?php
/**
 * Script cron — Publication automatique des articles programmés
 * À exécuter toutes les minutes via le Task Scheduler Windows
 * Commande : php C:\xampp\htdocs\econutrismar\cron_publish.php
 */

require_once __DIR__ . '/Model/bootstrap.php';
require_once __DIR__ . '/Model/Blog.php';

$blog = new Blog();
$count = $blog->publishScheduled();

if ($count > 0) {
    echo date('Y-m-d H:i:s') . " — {$count} article(s) publié(s) automatiquement.\n";
} else {
    echo date('Y-m-d H:i:s') . " — Aucun article à publier.\n";
}
