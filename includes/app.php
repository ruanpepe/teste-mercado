<?php

require __DIR__ . '/../vendor/autoload.php';

use \App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \App\Config\DBConnection;

Environment::load(__DIR__ . '/../');

define('DB_CONNECTION', DBConnection::getConnection(getenv('DB_DRIVER'), getenv('DB_HOST'), getenv('DB_PORT'), getenv('DB_USER'), getenv('DB_PASSWORD'), getenv('DB_NAME')));
define('URL', getenv('URL'));

// Variáveis globais
View::init([
    'URL' => URL
]);
