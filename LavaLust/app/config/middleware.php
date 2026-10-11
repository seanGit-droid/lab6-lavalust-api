<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

require_once APP_DIR . 'middlewares/AuthMiddleware.php';
require_once APP_DIR . 'middlewares/StudentMiddleware.php';

/*
| Register middleware classes here. The key is the name you use in
| routes: ->middleware('AuthMiddleware')
*/
$config['middlewares'] = [
    'AuthMiddleware'    => new AuthMiddleware(),
    'StudentMiddleware' => new StudentMiddleware(),
];