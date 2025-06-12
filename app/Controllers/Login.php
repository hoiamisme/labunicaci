<?php

namespace App\Controllers;

use App\Models\RegistrasiModel;
use CodeIgniter\Controller;

class Login extends Controller
{
    public function index()
    {
        return view('login_form');
    }

    public function auth()
    {
        $session = session();
        $model = new RegistrasiModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $model->where('email', $email)->first();

        if ($user) {
            $passValid = password_verify($password, $user['password']);
            if ($passValid) {
                $sessionData = [
                    'id_regis'      => $user['id_regis'],
                    'nama_lengkap'  => $user['nama_lengkap'],
                    'email'         => $user['email'],
                    'logged_in'     => true,
                ];
                $session->set($sessionData);
                return redirect()->to('/dashboard');
            } else {
                return redirect()->back()->with('error', 'Password salah.');
            }
        } else {
            return redirect()->back()->with('error', 'Email tidak ditemukan.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
