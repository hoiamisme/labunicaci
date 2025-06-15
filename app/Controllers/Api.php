<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;

class Api extends BaseController
{
    public function namaByJenis()
    {
        $jenis = $this->request->getGet('jenis');

        if ($jenis === 'alat') {
            $model = new AlatModel();
            $data = $model->findAll();
            $result = array_column($data, 'nama_alat');
        } elseif ($jenis === 'bahan') {
            $model = new BahanModel();
            $data = $model->findAll();
            $result = array_column($data, 'nama_bahan');
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis tidak valid']);
        }

        return $this->response->setJSON($result);
    }

    public function detailItem()
    {
        $jenis = $this->request->getGet('jenis');
        $nama = $this->request->getGet('nama');

        if ($jenis === 'alat') {
            $model = new AlatModel();
            $item = $model->where('nama_alat', $nama)->first();
        } elseif ($jenis === 'bahan') {
            $model = new BahanModel();
            $item = $model->where('nama_bahan', $nama)->first();
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis tidak valid']);
        }

        return $this->response->setJSON($item);
    }
}
