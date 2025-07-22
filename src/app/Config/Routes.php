<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
// app/Config/Routes.php
$routes->get('/dbtest', 'Home::dbtest'); // เพิ่มบรรทัดนี้
//$routes->get('/dashboard', 'Dashboard::index');
//$routes->get('/dashboard', 'Dashboard::index', ['filter' => 'auth']);
// File: app/Config/Routes.php (เพิ่มโค้ดเหล่านี้เข้าไป)
// --------------------------------------------------------------------
// กำหนด URL สำหรับระบบ Login
// --------------------------------------------------------------------
$routes->get('login', 'AuthController::index');
$routes->get('logout', 'AuthController::logout');
$routes->post('login/attempt', 'AuthController::attemptLogin');
$routes->get('logout', 'AuthController::logout');
$routes->get('/reset-password-for-mana', 'AuthController::resetPassword');
// สร้างกลุ่มของ Route ที่ต้องผ่านการยืนยันตัวตนก่อน
// ทุก Route ที่อยู่ในกลุ่มนี้จะถูกป้องกันโดย 'auth' filter
$routes->group('dashboard', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});
// ตัวอย่างการเพิ่ม Route อื่นๆ ที่ต้อง Login
// $routes->group('patients', ['filter' => 'auth'], function($routes) {
//     $routes->get('/', 'Patients::index');
//     $routes->get('create', 'Patients::create');
// });
$routes->group('patients', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'PatientsController::index');
    $routes->get('create', 'PatientsController::create');
    $routes->post('store', 'PatientsController::store');
    $routes->get('show/(:num)', 'PatientsController::show/$1');
    $routes->get('edit/(:num)', 'PatientsController::edit/$1');
    $routes->post('update/(:num)', 'PatientsController::update/$1'); // ใช้ post สำหรับฟอร์ม
    $routes->get('delete/(:num)', 'PatientsController::delete/$1');
});

