<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        $model = new UserModel();
        $data['users'] = $model->findAll();
        return view('user_manage', $data);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $model = new UserModel();
        $model->update($id, ['status' => $status]);

        return redirect()->to('/user');
    }
}
