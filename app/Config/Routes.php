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
$routes->post('pemakaian/add', 'Pemakaian::prosesAdd');
$routes->get('pemakaian/view2', 'Pemakaian::view2');
$routes->post('pemakaian/submit', 'Pemakaian::prosesSubmit');

// LOGBOOK ROUTES - HAPUS FILTER DULU UNTUK TEST
$routes->get('logbook', 'Logbook::index');
$routes->get('logbook/export', 'Logbook::export');
$routes->get('logbook/statistik', 'Logbook::statistik');
$routes->get('logbook/detail/(:alpha)/(:num)', 'Logbook::detail/$1/$2');
$routes->post('logbook/update-status', 'Logbook::updateStatus');

// User Management  
$routes->get('user', 'User::index');
$routes->post('user/updateStatus/(:num)', 'User::updateStatus/$1');

// Api
$routes->get('/api/nama-by-jenis', 'Api::namaByJenis');
$routes->get('/api/detail-item', 'Api::detailItem');

