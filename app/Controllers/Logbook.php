<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\LogbookModel;

class Pemakaian extends BaseController
{
    public function index()
    {
        $alatModel = new AlatModel();
        $bahanModel = new BahanModel();

        $data['alat'] = $alatModel->findAll();
        $data['bahan'] = $bahanModel->findAll();
        $data['lokasi'] = array_unique(array_merge(
            array_column($data['alat'], 'lokasi'),
            array_column($data['bahan'], 'lokasi')
        ));

        return view('pemakaian_form', $data);
    }

    public function prosesAdd()
    {
        // Validasi dan proses simpan data view 1
        $jenis = $this->request->getPost('jenis');
        $nama = $this->request->getPost('nama');
        $jumlah = $this->request->getPost('jumlah');
        $satuan = $this->request->getPost('satuan');
        $lokasi = $this->request->getPost('lokasi');
        $tanggal = $this->request->getPost('tanggal');

        // Validasi sederhana
        if (!in_array($jenis, ['alat', 'bahan']) || !is_numeric($jumlah) || !in_array($satuan, ['gram', 'mililiter'])) {
            return redirect()->back()->with('error', 'Input tidak valid.');
        }

        // Simpan ke session/temp (atau database sesuai kebutuhan)
        $pemakaian = session()->get('pemakaian') ?? [];
        $pemakaian[] = compact('jenis', 'nama', 'jumlah', 'satuan', 'lokasi', 'tanggal');
        session()->set('pemakaian', $pemakaian);

        return redirect()->to('/pemakaian/view2');
    }

    public function view2()
    {
        $data['pemakaian'] = session()->get('pemakaian') ?? [];
        return view('pemakaian_view2', $data);
    }

    public function prosesSubmit()
    {
        // Proses simpan data view 2 (nama, alat, bahan, tujuan, keterangan, pesan)
        $nama = $this->request->getPost('nama');
        $alat = $this->request->getPost('alat');
        $bahan = $this->request->getPost('bahan');
        $tujuan = $this->request->getPost('tujuan');
        $keterangan = $this->request->getPost('keterangan');
        $pesan = $this->request->getPost('pesan');

        // Simpan ke database/log sesuai kebutuhan Anda
        // ...

        session()->remove('pemakaian');
        return redirect()->to('/pemakaian')->with('success', 'Data pemakaian berhasil disubmit!');
    }
}

class Logbook extends BaseController
{
    public function index()
    {
        $tipe = $this->request->getGet('tipe');
        $model = new LogbookModel();
        $data['logbook'] = $model->getLogbook($tipe);
        $data['tipe'] = $tipe;
        return view('Logbook_form', $data);
    }
}
