<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;

class Manajemen extends BaseController
{
    public function index()
    {
        $alatModel = new AlatModel();
        $bahanModel = new BahanModel();

        $data['alat'] = $alatModel->findAll();
        $data['bahan'] = $bahanModel->findAll();

        // Tambahkan data lokasi di sini
        $data['lokasi'] = ['Lemari 1', 'Rak 2', 'Gudang', 'Laboratorium'];

        return view('Manajemen_form', $data);
    }
}
