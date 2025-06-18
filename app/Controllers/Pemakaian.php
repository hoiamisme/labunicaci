<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\LogalatModel;
use App\Models\LogbahanModel;
use App\Libraries\EmailService;
use CodeIgniter\I18n\Time;

class Pemakaian extends BaseController
{
    protected $alatModel;
    protected $bahanModel;
    protected $logalatModel;
    protected $logbahanModel;
    protected $emailService;

    public function __construct()
    {
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->logalatModel = new LogalatModel();
        $this->logbahanModel = new LogbahanModel();
        $this->emailService = new EmailService();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        return view('Pemakaian_form');
    }

    public function submitReview()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $reviewData = json_decode($this->request->getPost('review_data'), true);
        $tujuan = $this->request->getPost('tujuan');
        $pesan = $this->request->getPost('pesan');

        if (empty($reviewData)) {
            return redirect()->back()->with('error', 'Tidak ada data untuk disubmit');
        }

        $userId = session('id_regis');
        $successCount = 0;
        $insertedItems = [];

        try {
            log_message('info', '=== SUBMIT REVIEW START ===');
            log_message('info', 'User ID: ' . $userId);
            log_message('info', 'Review data: ' . json_encode($reviewData));

            foreach ($reviewData as $item) {
                if ($item['jenis'] === 'alat') {
                    $alat = $this->alatModel->where('nama_alat', $item['nama'])
                                            ->where('lokasi', $item['lokasi'])
                                            ->first();
                    if ($alat) {
                        $insertId = $this->logalatModel->insert([
                            'id_regis' => $userId,
                            'id_alat' => $alat['id_alat'],
                            'pengurangan' => $item['jumlah'],
                            'tujuan_pemakaian' => $tujuan,
                            'tanggal_dipinjam' => Time::now(),
                            'status' => 'not approve',
                            'pesan' => $pesan
                        ]);
                        
                        if ($insertId) {
                            $insertedItems[] = [
                                'type' => 'alat',
                                'name' => $item['nama'],
                                'quantity' => $item['jumlah'],
                                'location' => $item['lokasi'],
                                'id' => $insertId,
                                'unit' => 'unit'
                            ];
                            $successCount++;
                            log_message('info', 'Alat inserted: ' . $item['nama']);
                        }
                    }
                } elseif ($item['jenis'] === 'bahan') {
                    $bahan = $this->bahanModel->where('nama_bahan', $item['nama'])
                                              ->where('lokasi', $item['lokasi'])
                                              ->first();
                    if ($bahan) {
                        $insertId = $this->logbahanModel->insert([
                            'id_regis' => $userId,
                            'id_bahan' => $bahan['id_bahan'],
                            'pengurangan' => $item['jumlah'],
                            'tujuan_pemakaian' => $tujuan,
                            'tanggal' => Time::now(),
                            'status' => 'not approve',
                            'pesan' => $pesan
                        ]);
                        
                        if ($insertId) {
                            $insertedItems[] = [
                                'type' => 'bahan',
                                'name' => $item['nama'],
                                'quantity' => $item['jumlah'],
                                'location' => $item['lokasi'],
                                'id' => $insertId,
                                'unit' => $bahan['satuan_bahan'] ?? ''
                            ];
                            $successCount++;
                            log_message('info', 'Bahan inserted: ' . $item['nama']);
                        }
                    }
                }
            }

            if ($successCount > 0) {
                log_message('info', 'SUCCESS: ' . $successCount . ' items inserted');
                log_message('info', 'Sending email notification...');
                
                // Kirim email notifikasi ke admin
                $emailSent = $this->emailService->sendPermintaanNotification(
                    $userId, 
                    $insertedItems,
                    $tujuan, 
                    $pesan
                );
                
                $message = "✅ {$successCount} permintaan pemakaian berhasil dikirim untuk persetujuan";
                if ($emailSent) {
                    $message .= ". 📧 Notifikasi email telah dikirim ke admin.";
                    log_message('info', 'Email notification sent successfully');
                } else {
                    $message .= ". ⚠️ Namun notifikasi email gagal dikirim.";
                    log_message('warning', 'Email notification failed');
                }
                
                return redirect()->back()->with('success', $message);
            } else {
                log_message('error', 'No items were inserted');
                return redirect()->back()->with('error', 'Gagal menyimpan permintaan pemakaian');
            }

        } catch (\Exception $e) {
            log_message('error', 'Submit review error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
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
                'satuan_bahan' => $item['satuan_bahan'],
                'lokasi' => $item['lokasi'],
                'jumlah_bahan' => $item['jumlah_bahan']
            ]);
        } else {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Jenis tidak dikenali']);
        }
    }
}
