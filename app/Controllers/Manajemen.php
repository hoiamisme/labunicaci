<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\InstrumenModel;

class Manajemen extends BaseController
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

    public function index()
    {
        $alat = $this->alatModel->findAll();
        $bahan = $this->bahanModel->findAll();
        $instrumen = $this->instrumenModel->findAll();

        $lokasi = array_unique(array_merge(
            array_column($alat, 'lokasi'),
            array_column($bahan, 'lokasi'),
            array_column($instrumen, 'lokasi')
        ));

        return view('Manajemen_form', [
            'alat' => $alat,
            'bahan' => $bahan,
            'instrumen' => $instrumen,
            'lokasi' => $lokasi
        ]);
    }

    public function tambah()
    {
        $jenis = $this->request->getPost('jenis');
        $nama = $this->request->getPost('nama');
        $jumlah = $this->request->getPost('jumlah');
        $satuan = $this->request->getPost('satuan');
        $lokasi = $this->request->getPost('lokasi');

        if ($jenis === 'alat') {
            $this->alatModel->save([
                'nama_alat' => $nama,
                'jumlah_alat' => (int) $jumlah,
                'lokasi' => $lokasi
            ]);
        } elseif ($jenis === 'bahan') {
            $this->bahanModel->save([
                'nama_bahan' => $nama,
                'jumlah_bahan' => (float) $jumlah,
                'satuan_bahan' => $satuan,
                'lokasi' => $lokasi
            ]);
        } elseif ($jenis === 'instrumen') {
            $this->instrumenModel->save([
                'nama_instrumen' => $nama,
                'jumlah_instrumen' => (int) $jumlah,
                'lokasi' => $lokasi
            ]);
        }

        return redirect()->to('/manajemen');
    }

    public function kurang()
    {
        $jenis = $this->request->getPost('jenis');
        $nama = $this->request->getPost('nama');
        $jumlah = $this->request->getPost('jumlah');

        if ($jenis === 'alat') {
            $alat = $this->alatModel->where('nama_alat', $nama)->first();
            if ($alat && $alat['jumlah_alat'] >= $jumlah) {
                $this->alatModel->update($alat['id_alat'], [
                    'jumlah_alat' => $alat['jumlah_alat'] - $jumlah
                ]);
            }
        } elseif ($jenis === 'bahan') {
            $bahan = $this->bahanModel->where('nama_bahan', $nama)->first();
            if ($bahan && $bahan['jumlah_bahan'] >= $jumlah) {
                $this->bahanModel->update($bahan['id_bahan'], [
                    'jumlah_bahan' => $bahan['jumlah_bahan'] - $jumlah
                ]);
            }
        } elseif ($jenis === 'instrumen') {
            $instrumen = $this->instrumenModel->where('nama_instrumen', $nama)->first();
            if ($instrumen && $instrumen['jumlah_instrumen'] >= $jumlah) {
                $this->instrumenModel->update($instrumen['id_instrumen'], [
                    'jumlah_instrumen' => $instrumen['jumlah_instrumen'] - $jumlah
                ]);
            }
        }

        return redirect()->to('/manajemen');
    }
}
