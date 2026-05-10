<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/Model/bootstrap.php';
require_once dirname(__DIR__, 2) . '/Controller/ApiBlogController.php';

$controller = new ApiBlogController();
$controller->stats();
