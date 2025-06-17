<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemberitahuanModel;

class Pemberitahuan extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $model = new PemberitahuanModel();

        $data['alat'] = $model->getLogAlatNotApproved();
        $data['bahan'] = $model->getLogBahanNotApproved();

        return view('pemberitahuan_form', $data);
    }

    public function approveAlat()
    {
        $id = $this->request->getPost('id_logalat');
        $this->db->table('logalat')->where('id_logalat', $id)->update(['status' => 'approve']);
        return redirect()->back()->with('message', 'Status alat berhasil diubah.');
    }

    public function approveBahan()
    {
        $id = $this->request->getPost('id_logbahan');
        $this->db->table('logbahan')->where('id_logbahan', $id)->update(['status' => 'approve']);
        return redirect()->back()->with('message', 'Status bahan berhasil diubah.');
    }
}
