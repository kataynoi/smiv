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
// ใน Routes.php
$routes->get('/login', 'AuthController::index');
$routes->get('logout', 'AuthController::logout');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/reset-password-for-mana', 'AuthController::resetPassword');
$routes->get('/products', 'ProductController::index');

// --- Public Routes for Authentication ---
$routes->get('/register', 'AuthController::register');
$routes->post('/register', 'AuthController::attemptRegister');
// (คุณอาจจะมี /login, /logout ที่นี่ด้วย)

// --- AJAX Routes ---
// สร้าง Group สำหรับ AJAX โดยเฉพาะเพื่อให้จัดการง่าย
$routes->group('ajax', function ($routes) {
    $routes->post('get-amphures', 'AuthController::getAmphures');
    // คุณสามารถย้าย get-tambons, get-villages มาไว้ที่นี่ได้ในอนาคต
});
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
// app/Config/Routes.php
$routes->group('patients', function ($routes) {
    // CRUD Routes
    $routes->get('/', 'PatientController::index');
    $routes->get('fetch', 'PatientController::fetchPatients');
    $routes->post('store', 'PatientController::store');
    $routes->get('fetch-one/(:num)', 'PatientController::fetchSinglePatient/$1');
    $routes->post('update', 'PatientController::update');
    $routes->post('delete/(:num)', 'PatientController::delete/$1');
    
    // Feature Routes
    $routes->post('update-risk-level', 'PatientController::updateRiskLevel');
    
    // AJAX Routes for Dropdowns
    $routes->post('get-amphures', 'PatientController::getAmphures');
    $routes->post('get-tambons', 'PatientController::getTambons');
    $routes->post('get-villages', 'PatientController::getVillages');
});


$routes->group('admin', ['filter' => 'auth'], function ($routes) {
    // หน้านี้จะเข้าได้เฉพาะ Admin อำเภอ
    $routes->get('user-approval', 'AdminController::userApproval');

    // AJAX routes for the approval page
    $routes->get('users/pending', 'AdminController::fetchPendingUsers');
    $routes->post('users/approve', 'AdminController::processApproval');
    $routes->post('users/reject/(:num)', 'AdminController::rejectUser/$1');
});
