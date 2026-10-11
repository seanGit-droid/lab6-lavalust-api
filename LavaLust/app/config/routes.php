<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
/** @var object $router **/

//$router->get('/', 'UsersController::index');
//$router->get('/student', 'StudentController::index');
//$router->get('/student/login', 'StudentController::login');
//$router->get('/student/logout', 'StudentController::logout');

// Protected route applying StudentMiddleware
//$router->get('/student/profile', 'StudentController::profile')->middleware('StudentMiddleware');

//$router->get('/users', 'UsersController::index');




// Lab 5 Default Route -> Login
$router->get('/', 'Auth::login');

// Auth Routes
$router->get('/login', 'Auth::login');
$router->post('/authenticate', 'Auth::authenticate');
$router->get('/logout', 'Auth::logout');

// Products CRUD Routes
$router->get('/products', 'Products::index')->middleware('AuthMiddleware');
$router->get('/products/create', 'Products::create')->middleware('AuthMiddleware');
$router->post('/products/store', 'Products::store')->middleware('AuthMiddleware');
$router->get('/products/edit/{id}', 'Products::edit')->middleware('AuthMiddleware');
$router->post('/products/update/{id}', 'Products::update')->middleware('AuthMiddleware');
$router->get('/products/delete/{id}', 'Products::delete')->middleware('AuthMiddleware');

/*
|--------------------------------------------------------------------------
| LAB 6 - JSON API (React frontend)
|--------------------------------------------------------------------------
| Each route also accepts OPTIONS so browsers can complete the CORS
| pre-flight request (the Api library answers it automatically).
*/
$router->get('api/health', function () {
    header('Content-Type: application/json; charset=UTF-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode(['status' => 'ok', 'service' => 'LavaLust Product API']);
});

// Authentication
$router->match('api/auth/register', 'ApiAuthController::register', ['POST', 'OPTIONS']);
$router->match('api/auth/login',    'ApiAuthController::login',    ['POST', 'OPTIONS']);
$router->match('api/auth/refresh',  'ApiAuthController::refresh',  ['POST', 'OPTIONS']);
$router->match('api/auth/logout',   'ApiAuthController::logout',   ['POST', 'OPTIONS']);
$router->match('api/auth/me',       'ApiAuthController::me',       ['GET',  'OPTIONS']);

// Products CRUD (protected by JWT inside ApiProductController)
$router->match('api/products',        'ApiProductController::index', ['GET',  'OPTIONS']);
$router->post('api/products',         'ApiProductController::store');
$router->match('api/products/{id}',   'ApiProductController::show',  ['GET',  'OPTIONS']);
$router->put('api/products/{id}',     'ApiProductController::update');
$router->patch('api/products/{id}',   'ApiProductController::update');
$router->delete('api/products/{id}',  'ApiProductController::destroy');


/*
|--------------------------------------------------------------------------
| Migration Routes (Lab 6 / Migration class)
|--------------------------------------------------------------------------
| Only usable when MIGRATION_ENABLED=true (see MigrationController).
*/
$router->get('create-migration/{migration_class}', 'MigrationController::create_migration');
$router->get('migrate',      'MigrationController::migrate');
$router->get('rollback',     'MigrationController::rollback');
$router->get('rollback-all', 'MigrationController::rollback_all');
$router->get('refresh',      'MigrationController::refresh');
$router->get('status',       'MigrationController::status');
