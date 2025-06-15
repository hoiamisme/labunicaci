<?php

use Config\Services;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
echo ">>> Routes file loaded\n"; // debug

$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Login::index'); // <== redirect root ke login

// Routes Registrasi
$routes->get('registrasi', 'Registrasi::index');
$routes->post('registrasi/simpan', 'Registrasi::simpan');

//Routes Login
$routes->get('/login', 'Login::index');
$routes->post('/login/auth', 'Login::auth');
$routes->get('/logout', 'Login::logout');

//Routes Login to Dashboard
$routes->get('/dashboard', 'Dashboard::index');

//routes profiles
$routes->get('profiles', 'Profiles::index');
$routes->post('profiles/update', 'Profiles::update');

//routes manajemen
$routes->get('/manajemen', 'Manajemen::index');
$routes->post('/manajemen/tambah', 'Manajemen::tambah');
$routes->post('/manajemen/kurang', 'Manajemen::kurang');

//routes pemakaian
$routes->get('pemakaian', 'Pemakaian::index');
$routes->post('pemakaian/add', 'Pemakaian::prosesAdd');
$routes->get('pemakaian/view2', 'Pemakaian::view2');
$routes->post('pemakaian/submit', 'Pemakaian::prosesSubmit');
$routes->get('logbook', 'Logbook::index');

//routes user management
$routes->get('user', 'User::index');
$routes->post('user/updateStatus/(:num)', 'User::updateStatus/$1');
