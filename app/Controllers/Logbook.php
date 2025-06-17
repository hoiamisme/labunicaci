<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LogalatModel;
use App\Models\LogbahanModel;

class Logbook extends BaseController
{
    protected $logalatModel;
    protected $logbahanModel;

    public function __construct()
    {
        $this->logalatModel = new LogalatModel();
        $this->logbahanModel = new LogbahanModel();
    }

    /**
     * Halaman utama logbook - TABEL TERPISAH
     */
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            // Ambil data dari kedua model dengan JOIN
            $dataAlat = $this->logalatModel->getLogalatWithDetails();
            $dataBahan = $this->logbahanModel->getLogbahanWithDetails();

            // Hitung statistik
            $totalAlat = count($dataAlat);
            $totalBahan = count($dataBahan);
            $totalApprove = 0;
            $totalPending = 0;
            $aktivitasHariIni = 0;

            // Hitung statistik alat
            foreach ($dataAlat as $alat) {
                if ($alat['status'] === 'approve') {
                    $totalApprove++;
                } elseif ($alat['status'] === 'not approve') {
                    $totalPending++;
                }

                // Aktivitas hari ini berdasarkan tanggal dipinjam
                if (!empty($alat['tanggal_dipinjam'])) {
                    $tanggalItem = date('Y-m-d', strtotime($alat['tanggal_dipinjam']));
                    $hariIni = date('Y-m-d');
                    if ($tanggalItem === $hariIni) {
                        $aktivitasHariIni++;
                    }
                }
            }

            // Hitung statistik bahan
            foreach ($dataBahan as $bahan) {
                if ($bahan['status'] === 'approve') {
                    $totalApprove++;
                } elseif ($bahan['status'] === 'not approve') {
                    $totalPending++;
                }

                // Aktivitas hari ini berdasarkan tanggal
                if (!empty($bahan['tanggal'])) {
                    $tanggalItem = date('Y-m-d', strtotime($bahan['tanggal']));
                    $hariIni = date('Y-m-d');
                    if ($tanggalItem === $hariIni) {
                        $aktivitasHariIni++;
                    }
                }
            }

            $data = [
                'dataAlat' => $dataAlat,
                'dataBahan' => $dataBahan,
                'statistik' => [
                    'total_alat' => $totalAlat,
                    'total_bahan' => $totalBahan,
                    'total_semua' => $totalAlat + $totalBahan,
                    'total_approve' => $totalApprove,
                    'total_pending' => $totalPending,
                    'aktivitas_hari_ini' => $aktivitasHariIni
                ]
            ];

            return view('Logbook_form', $data);

        } catch (\Exception $e) {
            // Jika error, tampilkan dengan data kosong
            $data = [
                'dataAlat' => [],
                'dataBahan' => [],
                'statistik' => [
                    'total_alat' => 0,
                    'total_bahan' => 0,
                    'total_semua' => 0,
                    'total_approve' => 0,
                    'total_pending' => 0,
                    'aktivitas_hari_ini' => 0
                ],
                'error_message' => 'Error: ' . $e->getMessage()
            ];

            return view('Logbook_form', $data);
        }
    }

    public function export()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            $dataAlat = $this->logalatModel->getLogalatWithDetails();
            $dataBahan = $this->logbahanModel->getLogbahanWithDetails();

            $filename = 'logbook_' . date('Y-m-d_H-i-s') . '.csv';
            
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM untuk UTF-8
            
            // Header CSV untuk ALAT
            fputcsv($output, ['=== LOGBOOK ALAT ===']);
            fputcsv($output, [
                'Nama Pengguna', 'Email', 'Nama Alat', 'Penambahan', 'Pengurangan',
                'Tanggal Dipinjam', 'Tanggal Kembali', 'Tujuan Pemakaian', 'Status', 'Pesan', 'Lokasi'
            ]);
            
            // Data Alat
            foreach ($dataAlat as $alat) {
                fputcsv($output, [
                    $alat['nama_lengkap'] ?? '',
                    $alat['email'] ?? '',
                    $alat['nama_alat'] ?? '',
                    $alat['penambahan'] ?? '0',
                    $alat['pengurangan'] ?? '0',
                    $alat['tanggal_dipinjam'] ?? '',
                    $alat['tanggal_kembali'] ?? '',
                    $alat['tujuan_pemakaian'] ?? '',
                    $alat['status'] ?? '',
                    $alat['pesan'] ?? '',
                    $alat['lokasi_alat'] ?? ''
                ]);
            }
            
            // Pemisah
            fputcsv($output, ['']);
            fputcsv($output, ['=== LOGBOOK BAHAN ===']);
            
            // Header CSV untuk BAHAN
            fputcsv($output, [
                'Nama Pengguna', 'Email', 'Nama Bahan', 'Penambahan', 'Pengurangan',
                'Tanggal', 'Tujuan Pemakaian', 'Status', 'Pesan', 'Lokasi', 'Satuan'
            ]);
            
            // Data Bahan
            foreach ($dataBahan as $bahan) {
                fputcsv($output, [
                    $bahan['nama_lengkap'] ?? '',
                    $bahan['email'] ?? '',
                    $bahan['nama_bahan'] ?? '',
                    $bahan['penambahan'] ?? '0',
                    $bahan['pengurangan'] ?? '0',
                    $bahan['tanggal'] ?? '',
                    $bahan['tujuan_pemakaian'] ?? '',
                    $bahan['status'] ?? '',
                    $bahan['pesan'] ?? '',
                    $bahan['lokasi_bahan'] ?? '',
                    $bahan['satuan_bahan'] ?? ''
                ]);
            }
            
            fclose($output);
            exit();

        } catch (\Exception $e) {
            echo "Error export: " . $e->getMessage();
        }
    }

    public function detail($jenis, $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid request']);
        }

        try {
            $detail = null;
            
            if ($jenis === 'alat') {
                $detail = $this->logalatModel->getLogalatWithDetails($id);
            } elseif ($jenis === 'bahan') {
                $detail = $this->logbahanModel->getLogbahanWithDetails($id);
            }
            
            if (!$detail) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Data tidak ditemukan']);
            }

            return $this->response->setJSON([
                'success' => true,
                'data' => $detail,
                'jenis' => $jenis
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function statistik()
    {
        echo "<h1>📊 Statistik Detail</h1>";
        echo "<p>Halaman statistik detail (coming soon)</p>";
        echo "<p><a href='/logbook'>← Kembali ke Logbook</a></p>";
    }
}
