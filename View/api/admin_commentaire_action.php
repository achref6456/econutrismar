<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/Model/bootstrap.php';

$controller = new ApiBlogController();

// Déterminer l'action depuis le query string ou la méthode HTTP
$action = isset($_GET['action']) ? (string) $_GET['action'] : '';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($method === 'DELETE' || $action === 'supprimer') {
    $controller->adminDelete();
} elseif ($action === 'approuver') {
    $controller->adminApprove();
} elseif ($action === 'refuser') {
    $controller->adminRefuse();
} else {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Action inconnue']);
}
