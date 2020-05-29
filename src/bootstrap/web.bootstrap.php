<?php

// Make sure they're running PHP 7+
if (PHP_VERSION_ID < 70000) {
    exit('This API requires PHP 7.0 or later.');
}

$appType = 'web';
return require __DIR__ . '/../bootstrap/bootstrap.main.php';