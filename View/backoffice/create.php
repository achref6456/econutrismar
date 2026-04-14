<?php

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/Model/bootstrap.php';

$controller = new AdminBlogController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->store();
} else {
    $controller->createForm();
}
