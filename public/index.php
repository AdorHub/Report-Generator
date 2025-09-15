<?php

require_once __DIR__ . '../../vendor/autoload.php';

use App\Controllers\IndexController;
use App\Helpers\Logger;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$logger = new Logger(__DIR__ . '/../logs/app.log');
$logger->registerErrorHandler();
$logger->registerFatalErrorHandler();

try {
	$controller = new IndexController;
	$type = $_GET['type'] ?? '';
	$action = $_GET['action'] ?? '';
	$controller->handleRequest($type, $action);
} catch (Exception $e) {
	$logger->error($e->getMessage());
	http_response_code(500);
	echo "<h1>Error:</h1><br>" . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
	die;
}

?>