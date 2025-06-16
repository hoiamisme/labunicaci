<?php

namespace App\Controllers;

use App\Models\RegistrasiModel;
use CodeIgniter\Controller;

class Registrasi extends Controller
{
    public function index()
    {
        return view('registrasi_form');
    }

    public function simpan()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_lengkap' => 'required|alpha_space|max_length[50]',
            'email'        => 'required|valid_email',
            'cohort'       => 'required|numeric|max_length[10]',
            'prodi'        => 'required|in_list[Kimia]',
            'password'     => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]|max_length[255]|regex_match[/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/]',
                'errors' => [
                    'regex_match' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.'
                ]
            ],
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return view('registrasi_form', [
                'validation' => $validation
            ]);
        }

        $model = new RegistrasiModel();
        $email = $this->request->getPost('email');

        // Cek apakah email sudah terdaftar
        if ($model->where('email', $email)->first()) {
            return view('registrasi_form', [
                'validation' => $validation,
                'emailError' => 'Email sudah terdaftar, silakan gunakan email lain.'
            ]);
        }

        // Simpan data dengan role default "user"
        $model->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $email,
            'cohort'       => $this->request->getPost('cohort'),
            'prodi'        => $this->request->getPost('prodi'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'         => 'user'
        ]);

        return redirect()->to('/registrasi')->with('success', 'Registrasi berhasil.');
    }
}
