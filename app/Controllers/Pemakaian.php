<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\LogalatModel;
use App\Models\LogbahanModel;
use CodeIgniter\I18n\Time;

class Pemakaian extends BaseController
{
    protected $alatModel;
    protected $bahanModel;
    protected $logalatModel;
    protected $logbahanModel;

    public function __construct()
    {
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->logalatModel = new LogalatModel();
        $this->logbahanModel = new LogbahanModel();
    }

    public function index()
    {
        return view('Pemakaian_form'); // Pastikan nama file view-nya sesuai
    }

    public function submitReview()
    {
        $reviewData = json_decode($this->request->getPost('review_data'), true);
        $tujuan = $this->request->getPost('tujuan');
        $keterangan = $this->request->getPost('keterangan');
        $pesan = $this->request->getPost('pesan');

        foreach ($reviewData as $item) {
            if ($item['jenis'] === 'alat') {
                $alat = $this->alatModel->where('nama_alat', $item['nama'])
                                        ->where('lokasi', $item['lokasi'])
                                        ->first();
                if ($alat) {
                    // Tidak mengurangi stok alat, hanya mencatat log
                    $this->logalatModel->insert([
                        'id_regis' => session('id_regis'),
                        'id_alat' => $alat['id_alat'],
                        'pengurangan' => $item['jumlah'],
                        'tujuan_pemakaian' => $tujuan,
                        'tanggal_dipinjam' => Time::now(),
                        'status' => 'not approve',
                        'keterangan' => $keterangan,
                        'pesan' => $pesan
                    ]);
                }
            } elseif ($item['jenis'] === 'bahan') {
                $bahan = $this->bahanModel->where('nama_bahan', $item['nama'])
                                          ->where('lokasi', $item['lokasi'])
                                          ->first();
                if ($bahan) {
                    // Tidak mengurangi stok bahan, hanya mencatat log
                    $this->logbahanModel->insert([
                        'id_regis' => session('id_regis'),
                        'id_bahan' => $bahan['id_bahan'],
                        'pengurangan' => $item['jumlah'],
                        'tujuan_pemakaian' => $tujuan,
                        'tanggal' => Time::now(),
                        'status' => 'not approve',
                        'keterangan' => $keterangan,
                        'pesan' => $pesan
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Permintaan pemakaian berhasil dikirim untuk persetujuan');
    }

    public function getNamaByJenis()
    {
        $jenis = $this->request->getGet('jenis');
        if (!$jenis) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis tidak boleh kosong']);
        }

        $model = match ($jenis) {
            'alat' => $this->alatModel,
            'bahan' => $this->bahanModel,
            default => null,
        };

        if (!$model) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Jenis tidak dikenali']);
        }

        $result = $model->select('nama_' . $jenis)->findAll();
        $namaList = array_map(fn($r) => $r['nama_' . $jenis], $result);

        return $this->response->setJSON($namaList);
    }

    public function getDetailItem()
    {
        $jenis = $this->request->getGet('jenis');
        $nama = $this->request->getGet('nama');

        if (!$jenis || !$nama) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Jenis dan nama diperlukan']);
        }

        if ($jenis === 'alat') {
            $item = $this->alatModel->where('nama_alat', $nama)->first();
            if (!$item) return $this->response->setStatusCode(404)->setJSON(['error' => 'Alat tidak ditemukan']);

            return $this->response->setJSON([
                'satuan_bahan' => '-',
                'lokasi' => $item['lokasi'],
                'jumlah_alat' => $item['jumlah_alat']
            ]);
        } elseif ($jenis === 'bahan') {
            $item = $this->bahanModel->where('nama_bahan', $nama)->first();
            if (!$item) return $this->response->setStatusCode(404)->setJSON(['error' => 'Bahan tidak ditemukan']);

            return $this->response->setJSON([
                'satuan_bahan' => $item['satuan'],
                'lokasi' => $item['lokasi'],
                'jumlah_bahan' => $item['jumlah_bahan']
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Jenis tidak dikenali']);
        }
    }
}
