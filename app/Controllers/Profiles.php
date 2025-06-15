<?php

namespace App\Controllers;

use App\Models\ProfilesModel;

class Profiles extends BaseController
{
    public function index()
    {
        $model = new ProfilesModel();
        $userId = session()->get('id_regis'); // pastikan user login
        $data['user'] = $model->find($userId);
        return view('Profiles_form', $data);
    }

    public function update()
    {
        $model = new ProfilesModel();
        $userId = session()->get('id_regis');

        $password   = $this->request->getPost('password');
        $repassword = $this->request->getPost('repassword');
        $foto       = $this->request->getFile('foto_profil');

        if ($password !== $repassword) {
            return redirect()->back()->with('error', 'Password tidak cocok');
        }

        $data = [];

        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaBaru = $foto->getRandomName();
            $foto->move('uploads/', $namaBaru);
            $data['foto_profil'] = $namaBaru;
        }

        $model->update($userId, $data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui');
    }
}
