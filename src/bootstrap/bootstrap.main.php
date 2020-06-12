<?php
/**
 * API Bootstrap File
 */

// Normalize how PHP's string methods (strtoupper, etc) behave.
if (PHP_VERSION_ID < 70300) {
    setlocale(
        LC_CTYPE,
        'C.UTF-8', // libc >= 2.13
        'C.utf8', // different spelling
        'en_US.UTF-8', // fallback to lowest common denominator
        'en_US.utf8' // different spelling for fallback
    );
} else {
    // https://github.com/craftcms/cms/issues/4239
    setlocale(
        LC_CTYPE,
        'C.UTF-8', // libc >= 2.13
        'C.utf8' // different spelling
    );
}

// Set default timezone to UTC
date_default_timezone_set('EST');

// Validate the app type
if (!isset($appType) || ($appType !== 'web' && $appType !== 'console')) {
    throw new Exception('$appType must be set to "web" or "console".');
}

if ($appType === 'console') {
    $devMode = true;
} else if (getenv("ENVIRONMENT") == "dev") {
    $devMode = true;
} else {
    $devMode = false;
}

if ($devMode) {
    ini_set('display_errors', 1);
    defined('YII_DEBUG') || define('YII_DEBUG', true);
    defined('YII_ENV') || define('YII_ENV', 'dev');
} else {
    ini_set('display_errors', 0);
    defined('YII_DEBUG') || define('YII_DEBUG', false);
    defined('YII_ENV') || define('YII_ENV', 'prod');
}

require VENDOR_PATH . DIRECTORY_SEPARATOR .'yiisoft' . DIRECTORY_SEPARATOR . 'yii2' . DIRECTORY_SEPARATOR . 'Yii.php';
require BASE_API_SRC_PATH . 'BaseApi.php';
BaseApi::setAlias('@baseapi', BASE_API_SRC_PATH);
$config = require CONFIG_FILE;

$app = BaseApi::createObject($config);

return $app;