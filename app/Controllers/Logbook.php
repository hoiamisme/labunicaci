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
     * Halaman utama logbook - GABUNGAN DATA ALAT & BAHAN
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

            // Gabungkan data dengan format yang konsisten
            $logbook = [];
            
            // Format data ALAT
            foreach ($dataAlat as $alat) {
                $logbook[] = [
                    'id' => $alat['id_logalat'],
                    'jenis' => 'alat',
                    'nama_pengguna' => $alat['nama_lengkap'] ?? '-',
                    'email' => $alat['email'] ?? '-',
                    'nama_item' => $alat['nama_alat'] ?? '-',
                    'penambahan' => $alat['penambahan'] ?? '0',
                    'pengurangan' => $alat['pengurangan'] ?? '0',
                    'tanggal' => $alat['tanggal_dipinjam'] ?? $alat['tanggal_kembali'] ?? '-',
                    'tujuan_pemakaian' => $alat['tujuan_pemakaian'] ?? '-',
                    'status' => $alat['status'] ?? '-',
                    'pesan' => $alat['pesan'] ?? '-',
                    'lokasi' => $alat['lokasi_alat'] ?? '-',
                    'satuan' => '', // Alat tidak ada satuan
                    'tipe_aktivitas' => 'Peminjaman Alat'
                ];
            }

            // Format data BAHAN
            foreach ($dataBahan as $bahan) {
                $logbook[] = [
                    'id' => $bahan['id_logbahan'],
                    'jenis' => 'bahan',
                    'nama_pengguna' => $bahan['nama_lengkap'] ?? '-',
                    'email' => $bahan['email'] ?? '-',
                    'nama_item' => $bahan['nama_bahan'] ?? '-',
                    'penambahan' => $bahan['penambahan'] ?? '0',
                    'pengurangan' => $bahan['pengurangan'] ?? '0',
                    'tanggal' => $bahan['tanggal'] ?? '-',
                    'tujuan_pemakaian' => $bahan['tujuan_pemakaian'] ?? '-',
                    'status' => $bahan['status'] ?? '-',
                    'pesan' => $bahan['pesan'] ?? '-',
                    'lokasi' => $bahan['lokasi_bahan'] ?? '-',
                    'satuan' => $bahan['satuan_bahan'] ?? '-',
                    'tipe_aktivitas' => 'Pemakaian Bahan'
                ];
            }

            // Urutkan berdasarkan tanggal terbaru
            usort($logbook, function($a, $b) {
                $timeA = strtotime($a['tanggal']) ?: 0;
                $timeB = strtotime($b['tanggal']) ?: 0;
                return $timeB - $timeA; // Terbaru dulu
            });

            // Hitung statistik
            $totalAlat = count($dataAlat);
            $totalBahan = count($dataBahan);
            $totalApprove = 0;
            $totalPending = 0;
            $aktivitasHariIni = 0;

            foreach ($logbook as $item) {
                if ($item['status'] === 'approve') {
                    $totalApprove++;
                } elseif ($item['status'] === 'not approve' || $item['status'] === 'pending') {
                    $totalPending++;
                }

                // Hitung aktivitas hari ini
                if ($item['tanggal'] && $item['tanggal'] !== '-') {
                    $tanggalItem = date('Y-m-d', strtotime($item['tanggal']));
                    $hariIni = date('Y-m-d');
                    if ($tanggalItem === $hariIni) {
                        $aktivitasHariIni++;
                    }
                }
            }

            $data = [
                'logbook' => $logbook,
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
                'logbook' => [],
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
            
            // Header CSV
            fputcsv($output, [
                'Nama Pengguna', 'Email', 'Nama Item', 'Jenis', 'Penambahan', 'Pengurangan',
                'Tanggal', 'Tujuan Pemakaian', 'Status', 'Pesan', 'Lokasi', 'Satuan', 'Tipe Aktivitas'
            ]);
            
            // Data Alat
            foreach ($dataAlat as $alat) {
                fputcsv($output, [
                    $alat['nama_lengkap'] ?? '',
                    $alat['email'] ?? '',
                    $alat['nama_alat'] ?? '',
                    'Alat',
                    $alat['penambahan'] ?? '0',
                    $alat['pengurangan'] ?? '0',
                    $alat['tanggal_dipinjam'] ?? $alat['tanggal_kembali'] ?? '',
                    $alat['tujuan_pemakaian'] ?? '',
                    $alat['status'] ?? '',
                    $alat['pesan'] ?? '',
                    $alat['lokasi_alat'] ?? '',
                    '',
                    'Peminjaman Alat'
                ]);
            }
            
            // Data Bahan
            foreach ($dataBahan as $bahan) {
                fputcsv($output, [
                    $bahan['nama_lengkap'] ?? '',
                    $bahan['email'] ?? '',
                    $bahan['nama_bahan'] ?? '',
                    'Bahan',
                    $bahan['penambahan'] ?? '0',
                    $bahan['pengurangan'] ?? '0',
                    $bahan['tanggal'] ?? '',
                    $bahan['tujuan_pemakaian'] ?? '',
                    $bahan['status'] ?? '',
                    $bahan['pesan'] ?? '',
                    $bahan['lokasi_bahan'] ?? '',
                    $bahan['satuan_bahan'] ?? '',
                    'Pemakaian Bahan'
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
