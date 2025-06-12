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
