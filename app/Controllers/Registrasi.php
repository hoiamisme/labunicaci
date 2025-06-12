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
    'nama_lengkap'     => 'required|alpha_space|max_length[50]',
    'email'            => 'required|valid_email|is_unique[registrasi.email]',
    'cohort'           => 'required|numeric|max_length[10]',
    'prodi'            => 'required|in_list[Kimia]',
    'password'         => 'required|min_length[6]|max_length[255]',
    'password_confirm' => 'required|matches[password]'
];


        if (!$this->validate($rules)) {
            return view('registrasi_form', [
                'validation' => $validation
            ]);
        }

        $model = new RegistrasiModel();
        $model->insert([
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email'        => $this->request->getPost('email'),
            'cohort'       => $this->request->getPost('cohort'),
            'prodi'        => $this->request->getPost('prodi'),
            'password'     => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        return redirect()->to('/registrasi')->with('success', 'Registrasi berhasil.');
    }
}
