<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\InstrumenModel;
use App\Models\LogalatModel;
use App\Models\LogbahanModel;
use CodeIgniter\I18n\Time;

class Manajemen extends BaseController
{
    protected $alatModel;
    protected $bahanModel;
    protected $instrumenModel;
    protected $logalatModel;
    protected $logbahanModel;

    public function __construct()
    {
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->instrumenModel = new InstrumenModel();
        $this->logalatModel = new LogalatModel();
        $this->logbahanModel = new LogbahanModel();
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
            $alat = $this->alatModel->where('nama_alat', $nama)->first();
            if ($alat) {
                $this->logalatModel->insert([
                    'id_regis' => session('id_regis'),
                    'id_alat' => $alat['id_alat'],
                    'penambahan' => $jumlah,
                    'tujuan_pemakaian' => 'manajemen penambahan alat',
                    'tanggal_dipinjam' => Time::now(),
                    'status' => 'approve'
                ]);
            }
        } elseif ($jenis === 'bahan') {
            $this->bahanModel->save([
                'nama_bahan' => $nama,
                'jumlah_bahan' => (float) $jumlah,
                'satuan_bahan' => $satuan,
                'lokasi' => $lokasi
            ]);
            $bahan = $this->bahanModel->where('nama_bahan', $nama)->first();
            if ($bahan) {
                $this->logbahanModel->insert([
                    'id_regis' => session('id_regis'),
                    'id_bahan' => $bahan['id_bahan'],
                    'penambahan' => $jumlah,
                    'tujuan_pemakaian' => 'manajemen penambahan bahan',
                    'tanggal' => Time::now(),
                    'status' => 'approve'
                ]);
            }
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
                $this->logalatModel->insert([
                    'id_regis' => session('id_regis'),
                    'id_alat' => $alat['id_alat'],
                    'pengurangan' => $jumlah,
                    'tujuan_pemakaian' => 'manajemen pengurangan alat',
                    'tanggal_dipinjam' => Time::now(),
                    'status' => 'approve'
                ]);
            }
        } elseif ($jenis === 'bahan') {
            $bahan = $this->bahanModel->where('nama_bahan', $nama)->first();
            if ($bahan && $bahan['jumlah_bahan'] >= $jumlah) {
                $this->bahanModel->update($bahan['id_bahan'], [
                    'jumlah_bahan' => $bahan['jumlah_bahan'] - $jumlah
                ]);
                $this->logbahanModel->insert([
                    'id_regis' => session('id_regis'),
                    'id_bahan' => $bahan['id_bahan'],
                    'pengurangan' => $jumlah,
                    'tujuan_pemakaian' => 'manajemen pengurangan bahan',
                    'tanggal' => Time::now(),
                    'status' => 'approve'
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
