<?php

declare(strict_types=1);

require_once dirname(__DIR__, 3) . '/Model/bootstrap.php';

$controller = new BlogController();
$controller->show();
