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

$routes->get('/', 'Login::index');

// Registrasi
$routes->get('registrasi', 'Registrasi::index');
$routes->post('registrasi/simpan', 'Registrasi::simpan');

// Login
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');

// Dashboard
$routes->get('/dashboard', 'Dashboard::index');

// Profiles
$routes->get('profiles', 'Profiles::index');
$routes->post('profiles/update', 'Profiles::update');

// Manajemen
$routes->get('/manajemen', 'Manajemen::index');
$routes->post('/manajemen/tambah', 'Manajemen::tambah');
$routes->post('/manajemen/kurang', 'Manajemen::kurang');

// Pemakaian
$routes->get('pemakaian', 'Pemakaian::index');
$routes->post('pemakaian/submit-review', 'Pemakaian::submitReview');
$routes->post('pemakaian/submitReview', 'Pemakaian::submitReview');

// Logbook
$routes->get('logbook', 'Logbook::index');
$routes->get('logbook/export', 'Logbook::export');
$routes->get('logbook/statistik', 'Logbook::statistik');
$routes->get('logbook/detail/(:alpha)/(:num)', 'Logbook::detail/$1/$2');
$routes->post('logbook/update-status', 'Logbook::updateStatus');
$routes->post('logbook/kembalikan-alat/(:num)', 'Logbook::kembalikanAlat/$1');
$routes->get('logbook/alat-belum-kembali', 'Logbook::alatBelumKembali');

// User Management  
$routes->get('user', 'User::index');
$routes->post('user/updateStatus/(:num)', 'User::updateStatus/$1');

// MANAJEMEN USER - ROUTES BARU
$routes->get('manajemen-user', 'ManajemenUser::index');
$routes->get('manajemen-user/export', 'ManajemenUser::export');
$routes->get('manajemen-user/statistik', 'ManajemenUser::statistik');
$routes->get('manajemen-user/detail/(:num)', 'ManajemenUser::detail/$1');
$routes->post('manajemen-user/update-role', 'ManajemenUser::updateRole');
$routes->post('manajemen-user/delete-user', 'ManajemenUser::deleteUser');
$routes->post('manajemen-user/reset-password', 'ManajemenUser::resetPassword');

// Api
$routes->get('/api/nama-by-jenis', 'Api::namaByJenis');
$routes->get('/api/detail-item', 'Api::detailItem');

// INVENTORY ROUTES
$routes->get('inventory', 'Inventory::index');
$routes->get('inventory/daftar-alat', 'Inventory::daftar_alat');
$routes->get('inventory/daftar-bahan', 'Inventory::daftar_bahan');
$routes->get('inventory/daftar-instrumen', 'Inventory::daftar_instrumen');
$routes->get('inventory/detail/(:alpha)/(:num)', 'Inventory::detail/$1/$2');

// INVENTORY DELETE ROUTES (Admin only)
$routes->post('inventory/hapus-alat/(:num)', 'Inventory::hapus_alat/$1');
$routes->post('inventory/hapus-bahan/(:num)', 'Inventory::hapus_bahan/$1');
$routes->post('inventory/hapus-instrumen/(:num)', 'Inventory::hapus_instrumen/$1');

