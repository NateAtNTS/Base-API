<?php
/**
 * API Console Bootstrap File
 */

// Set path constants
define('API_BASE_PATH', __DIR__ . "/../");
define('API_VENDOR_PATH', API_BASE_PATH.'/vendor');

// Load Composer's autoloader
require_once API_VENDOR_PATH.'/autoload.php';


// Load dotenv?
if (class_exists('Dotenv\Dotenv') && file_exists(API_BASE_PATH . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(API_BASE_PATH . '/');
    $dotenv->load();
} else {
    exit("Either the .env file does not exist, or the Dotenv class file does not exist.  Exiting.");
}

$app = require __DIR__ . "/../src/bootstrap/web.bootstrap.php";
$exitCode = $app->run();
exit($exitCode);