<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\InstrumenModel;

class Api extends BaseController
{
    protected $alatModel;
    protected $bahanModel;
    protected $instrumenModel;

    public function __construct()
    {
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->instrumenModel = new InstrumenModel();
    }

    public function namaByJenis()
    {
        $jenis = $this->request->getGet('jenis');
        $result = [];

        if ($jenis === 'alat') {
            $result = array_column($this->alatModel->findAll(), 'nama_alat');
        } elseif ($jenis === 'bahan') {
            $result = array_column($this->bahanModel->findAll(), 'nama_bahan');
        } elseif ($jenis === 'instrumen') {
            $result = array_column($this->instrumenModel->findAll(), 'nama_instrumen');
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis tidak valid']);
        }

        return $this->response->setJSON($result);
    }

    public function detailItem()
    {
        $jenis = $this->request->getGet('jenis');
        $nama = $this->request->getGet('nama');

        if (!$nama) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Nama tidak boleh kosong']);
        }

        $item = null;

        if ($jenis === 'alat') {
            $item = $this->alatModel->where('nama_alat', $nama)->first();
        } elseif ($jenis === 'bahan') {
            $item = $this->bahanModel->where('nama_bahan', $nama)->first();
        } elseif ($jenis === 'instrumen') {
            $item = $this->instrumenModel->where('nama_instrumen', $nama)->first();
        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis tidak valid']);
        }

        return $this->response->setJSON($item);
    }
}
