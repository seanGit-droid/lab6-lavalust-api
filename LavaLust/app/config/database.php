<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

$database['default'] = array(
    'hostname' => getenv('DB_HOST') ?: 'localhost',
    'username' => getenv('DB_USERNAME') ?: (getenv('DB_USER') ?: 'root'),
    'password' => getenv('DB_PASSWORD') ?: '',
    'database' => getenv('DB_DATABASE') ?: (getenv('DB_NAME') ?: ''),
    'port'     => getenv('DB_PORT') ?: 3306,
    'driver'   => 'mysql',
    'charset'  => 'utf8mb4',
    'collate'  => 'utf8mb4_general_ci',
    'prefix'   => '',
    // Aiven MySQL: set DB_SSL=true if you get an "insecure transport" error.
    'ssl'      => filter_var(getenv('DB_SSL') ?: 'false', FILTER_VALIDATE_BOOLEAN),
    'ssl_ca'   => getenv('DB_SSL_CA') ?: '',
);

$database['main'] = &$database['default'];
