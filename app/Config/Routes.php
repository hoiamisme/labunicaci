<?php

use Config\Services;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// =================== Tidak Perlu Login ===================
$routes->get('/', 'Login::index');
$routes->get('registrasi', 'Registrasi::index');
$routes->post('registrasi/simpan', 'Registrasi::simpan');
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');

// =================== Hanya Admin ===================
$routes->group('', ['filter' => 'role:admin'], function($routes) {
    $routes->get('/manajemen', 'Manajemen::index');
    $routes->post('/manajemen/tambah', 'Manajemen::tambah');
    $routes->post('/manajemen/kurang', 'Manajemen::kurang');

    $routes->get('user', 'User::index');
    
    // ManajemenUser routes - PERBAIKAN: Tambahkan semua routes yang diperlukan
    $routes->get('manajemen-user', 'ManajemenUser::index');
    $routes->get('manajemen-user/export', 'ManajemenUser::export');
    $routes->get('manajemen-user/statistik', 'ManajemenUser::statistik');
    $routes->get('manajemen-user/detail/(:num)', 'ManajemenUser::detail/$1'); // PERBAIKAN: Tambahkan route detail
    $routes->post('manajemen-user/update-role', 'ManajemenUser::updateRole');
    $routes->post('manajemen-user/delete-user', 'ManajemenUser::deleteUser');
    $routes->post('manajemen-user/reset-password', 'ManajemenUser::resetPassword');

    $routes->post('inventory/hapus-alat/(:num)', 'Inventory::hapus_alat/$1');
    $routes->post('inventory/hapus-bahan/(:num)', 'Inventory::hapus_bahan/$1');
    $routes->post('inventory/hapus-instrumen/(:num)', 'Inventory::hapus_instrumen/$1');
});

// =================== Admin & User ===================
$routes->group('', ['filter' => 'role:admin,user'], function($routes) {
    $routes->get('/dashboard', 'Dashboard::index');

    $routes->get('profiles', 'Profiles::index');
    $routes->post('profiles/update', 'Profiles::update');

    $routes->get('logbook', 'Logbook::index');
    $routes->get('logbook/export', 'Logbook::export');
    $routes->get('logbook/statistik', 'Logbook::statistik');
    $routes->get('logbook/detail/(:alpha)/(:num)', 'Logbook::detail/$1/$2');
    $routes->post('logbook/update-status', 'Logbook::updateStatus');
    $routes->post('logbook/kembalikan-alat/(:num)', 'Logbook::kembalikanAlat/$1');
    $routes->get('logbook/alat-belum-kembali', 'Logbook::alatBelumKembali');

    $routes->get('inventory', 'Inventory::index');
    $routes->get('inventory/daftar-alat', 'Inventory::daftar_alat');
    $routes->get('inventory/daftar-bahan', 'Inventory::daftar_bahan');
    $routes->get('inventory/daftar-instrumen', 'Inventory::daftar_instrumen');
    $routes->get('inventory/detail/(:alpha)/(:num)', 'Inventory::detail/$1/$2');

    $routes->get('pemberitahuan', 'Pemberitahuan::index');
    $routes->post('pemberitahuan/approveAlat', 'Pemberitahuan::approveAlat');
    $routes->post('pemberitahuan/approveBahan', 'Pemberitahuan::approveBahan');
    $routes->post('pemberitahuan/declineAlat', 'Pemberitahuan::declineAlat');
    $routes->post('pemberitahuan/declineBahan', 'Pemberitahuan::declineBahan');
    $routes->post('/pemberitahuan/returnAlat', 'Pemberitahuan::returnAlat');
});

// =================== Khusus User ===================
$routes->group('', ['filter' => 'role:user'], function($routes) {
    $routes->get('pemakaian', 'Pemakaian::index');
    $routes->post('pemakaian/submitReview', 'Pemakaian::submitReview');
});

// Api Routes
$routes->get('/api/nama-by-jenis', 'Pemakaian::getNamaByJenis');
$routes->get('/api/detail-item', 'Pemakaian::getDetailItem');

// Debug routes (hapus setelah testing)
$routes->get('test-email', 'TestEmail::index');
$routes->get('test-basic-email', 'TestEmail::testBasicEmail');
$routes->get('debug-email', 'DebugEmail::inspectEmail');
$routes->get('debug-direct-email', 'DebugEmail::testDirectEmail');