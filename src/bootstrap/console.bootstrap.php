<?php
/**
 * API Console Bootstrap File
 */

use yii\console\ExitCode;

// Make sure they're running PHP 7+
if (PHP_VERSION_ID < 70000) {
    echo "This API requires PHP 7.0 or later.\n";
    exit(ExitCode::UNSPECIFIED_ERROR);
}

$appType = 'console';

return require __DIR__ . '/../bootstrap/bootstrap.main.php';