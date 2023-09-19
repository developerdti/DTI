<?php
// phpinfo();
// die;
declare(strict_types = 1);

# Time zone and locale information.
date_default_timezone_set('America/Mexico_City');

# it allows to use session global variables
session_start();

# determines how messages deploys
ini_set('display_errors',1);

require_once '../vendor/autoload.php';

require_once '../libs/core/constants.php';
use libs\Router;

// print_r($_SERVER);
// print_r($_SERVER['HTTP_X_FORWARDED_FOR']);
// print_r($_SERVER['REMOTE_ADDR']);
// echo DEPENDENCE_PATH;
$router = new Router();

