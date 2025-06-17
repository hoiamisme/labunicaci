<?php

namespace App\Controllers;

use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\InstrumenModel;
use App\Models\LogalatModel;
use App\Models\LogbahanModel;
use App\Models\LoginModel;

class Dashboard extends BaseController
{
    protected $alatModel;
    protected $bahanModel;
    protected $instrumenModel;
    protected $logalatModel;
    protected $logbahanModel;
    protected $loginModel;

    public function __construct()
    {
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->instrumenModel = new InstrumenModel();
        $this->logalatModel = new LogalatModel();
        $this->logbahanModel = new LogbahanModel();
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        try {
            $data = [
                'user_info' => [
                    'nama' => session()->get('nama_lengkap'),
                    'role' => session()->get('role')
                ],
                'stats_inventory' => $this->getStatsInventory(),
                'stats_aktivitas' => $this->getStatsAktivitas(),
                'stats_user' => $this->getStatsUser(),
                'alerts' => $this->getAlerts(),
                'top_data' => $this->getTopData(),
                'chart_data' => $this->getChartData()
            ];

        } catch (\Exception $e) {
            log_message('error', 'Dashboard error: ' . $e->getMessage());
            $data['error_message'] = 'Terjadi kesalahan saat memuat data dashboard';
            
            // Set default values to prevent view errors
            $data = array_merge($data ?? [], [
                'stats_inventory' => [],
                'stats_aktivitas' => [],
                'stats_user' => [],
                'alerts' => [],
                'top_data' => [],
                'chart_data' => []
            ]);
        }

        return view('Dashboard_form', $data);
    }

    private function getStatsInventory()
    {
        try {
            // Total inventory
            $totalAlat = $this->alatModel->countAllResults();
            $totalBahan = $this->bahanModel->countAllResults();
            $totalInstrumen = $this->instrumenModel->countAllResults();

            // Stok rendah (threshold: alat <= 5, bahan <= 10, instrumen <= 3)
            $alatStokRendah = $this->alatModel->where('jumlah_alat <=', 5)->countAllResults();
            $bahanStokRendah = $this->bahanModel->where('jumlah_bahan <=', 10)->countAllResults();
            $instrumenStokRendah = $this->instrumenModel->where('jumlah_instrumen <=', 3)->countAllResults();

            return [
                'total_alat' => $totalAlat,
                'total_bahan' => $totalBahan,
                'total_instrumen' => $totalInstrumen,
                'alat_stok_rendah' => $alatStokRendah,
                'bahan_stok_rendah' => $bahanStokRendah,
                'instrumen_stok_rendah' => $instrumenStokRendah
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error getStatsInventory: ' . $e->getMessage());
            return [
                'total_alat' => 0,
                'total_bahan' => 0,
                'total_instrumen' => 0,
                'alat_stok_rendah' => 0,
                'bahan_stok_rendah' => 0,
                'instrumen_stok_rendah' => 0
            ];
        }
    }

    private function getStatsAktivitas()
    {
        try {
            $today = date('Y-m-d');
            $weekStart = date('Y-m-d', strtotime('-7 days'));

            // Aktivitas hari ini
            $aktivitasHariIni = $this->logalatModel->where('DATE(tanggal_dipinjam)', $today)->countAllResults() +
                               $this->logbahanModel->where('DATE(tanggal)', $today)->countAllResults();

            // Aktivitas minggu ini
            $aktivitasMingguIni = $this->logalatModel->where('tanggal_dipinjam >=', $weekStart)->countAllResults() +
                                 $this->logbahanModel->where('tanggal >=', $weekStart)->countAllResults();

            // Pending approval
            $peminjamanPending = $this->logalatModel->where('status', 'not approve')->countAllResults();
            $pemakaianPending = $this->logbahanModel->where('status', 'not approve')->countAllResults();

            // Alat belum kembali (ada pengurangan tapi tanggal_kembali null)
            $alatBelumKembali = $this->logalatModel
                ->where('pengurangan >', 0)
                ->where('tanggal_kembali IS NULL')
                ->countAllResults();

            return [
                'aktivitas_hari_ini' => $aktivitasHariIni,
                'aktivitas_minggu_ini' => $aktivitasMingguIni,
                'peminjaman_pending' => $peminjamanPending,
                'pemakaian_pending' => $pemakaianPending,
                'alat_belum_kembali' => $alatBelumKembali
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error getStatsAktivitas: ' . $e->getMessage());
            return [
                'aktivitas_hari_ini' => 0,
                'aktivitas_minggu_ini' => 0,
                'peminjaman_pending' => 0,
                'pemakaian_pending' => 0,
                'alat_belum_kembali' => 0
            ];
        }
    }

    private function getStatsUser()
    {
        try {
            if (session()->get('role') !== 'admin') {
                return [];
            }

            $totalUser = $this->loginModel->countAllResults();
            $userAktif = $this->loginModel->where('status', 'approve')->countAllResults();
            $userPending = $this->loginModel->where('status', 'not approve')->countAllResults();
            $adminCount = $this->loginModel->where('role', 'admin')->countAllResults();

            return [
                'total_user' => $totalUser,
                'user_aktif' => $userAktif,
                'user_pending' => $userPending,
                'admin_count' => $adminCount
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error getStatsUser: ' . $e->getMessage());
            return [
                'total_user' => 0,
                'user_aktif' => 0,
                'user_pending' => 0,
                'admin_count' => 0
            ];
        }
    }

    private function getAlerts()
    {
        try {
            $alerts = [];

            // Alert stok rendah alat
            $alatStokRendah = $this->alatModel->where('jumlah_alat <=', 5)->findAll();
            if (!empty($alatStokRendah)) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => '⚠️',
                    'title' => 'Stok Alat Menipis',
                    'message' => count($alatStokRendah) . ' alat memiliki stok ≤ 5 unit',
                    'link' => '/inventory/daftar-alat?search=&location=&stok_rendah=1',
                    'action' => 'Lihat Detail'
                ];
            }

            // Alert stok rendah bahan
            $bahanStokRendah = $this->bahanModel->where('jumlah_bahan <=', 10)->findAll();
            if (!empty($bahanStokRendah)) {
                $alerts[] = [
                    'type' => 'warning',
                    'icon' => '⚠️',
                    'title' => 'Stok Bahan Menipis',
                    'message' => count($bahanStokRendah) . ' bahan memiliki stok ≤ 10',
                    'link' => '/inventory/daftar-bahan?search=&location=&stok_rendah=1',
                    'action' => 'Lihat Detail'
                ];
            }

            // Alert alat belum kembali
            $alatBelumKembali = $this->logalatModel
                ->where('pengurangan >', 0)
                ->where('tanggal_kembali IS NULL')
                ->countAllResults();
            
            if ($alatBelumKembali > 0) {
                $alerts[] = [
                    'type' => 'danger',
                    'icon' => '🔄',
                    'title' => 'Alat Belum Dikembalikan',
                    'message' => $alatBelumKembali . ' alat masih dipinjam dan belum dikembalikan',
                    'link' => '/logbook?filter=belum_kembali',
                    'action' => 'Cek Logbook'
                ];
            }

            // Alert pending approval (hanya admin)
            if (session()->get('role') === 'admin') {
                $totalPending = $this->logalatModel->where('status', 'not approve')->countAllResults() +
                               $this->logbahanModel->where('status', 'not approve')->countAllResults();
                
                if ($totalPending > 0) {
                    $alerts[] = [
                        'type' => 'info',
                        'icon' => '⏳',
                        'title' => 'Menunggu Approval',
                        'message' => $totalPending . ' permintaan menunggu persetujuan Anda',
                        'link' => '/logbook?filter=pending',
                        'action' => 'Review Sekarang'
                    ];
                }
            }

            return $alerts;

        } catch (\Exception $e) {
            log_message('error', 'Error getAlerts: ' . $e->getMessage());
            return [];
        }
    }

    private function getTopData()
    {
        try {
            // Top 5 alat sering dipinjam
            $topAlat = $this->db->table('logalat')
                ->select('alat.nama_alat, SUM(logalat.pengurangan) as total_dipinjam')
                ->join('alat', 'alat.id_alat = logalat.id_alat')
                ->where('logalat.pengurangan >', 0)
                ->groupBy('logalat.id_alat')
                ->orderBy('total_dipinjam', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Top 5 bahan sering digunakan
            $topBahan = $this->db->table('logbahan')
                ->select('bahan.nama_bahan, SUM(logbahan.pengurangan) as total_digunakan')
                ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan')
                ->where('logbahan.pengurangan >', 0)
                ->groupBy('logbahan.id_bahan')
                ->orderBy('total_digunakan', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Top 5 user paling aktif
            $topUser = $this->db->table('registrasi')
                ->select('registrasi.nama_lengkap, 
                         (SELECT COUNT(*) FROM logalat WHERE logalat.id_regis = registrasi.id_regis) + 
                         (SELECT COUNT(*) FROM logbahan WHERE logbahan.id_regis = registrasi.id_regis) as total_aktivitas')
                ->having('total_aktivitas >', 0)
                ->orderBy('total_aktivitas', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();

            // Top 5 lokasi terpopuler
            $lokasiAlat = $this->db->table('alat')
                ->select('lokasi, COUNT(*) as total')
                ->where('lokasi IS NOT NULL')
                ->where('lokasi !=', '')
                ->groupBy('lokasi')
                ->get()
                ->getResultArray();

            $lokasiBahan = $this->db->table('bahan')
                ->select('lokasi, COUNT(*) as total')
                ->where('lokasi IS NOT NULL')
                ->where('lokasi !=', '')
                ->groupBy('lokasi')
                ->get()
                ->getResultArray();

            $lokasiInstrumen = $this->db->table('instrumen')
                ->select('lokasi, COUNT(*) as total')
                ->where('lokasi IS NOT NULL')
                ->where('lokasi !=', '')
                ->groupBy('lokasi')
                ->get()
                ->getResultArray();

            // Gabungkan dan hitung total per lokasi
            $lokasiTotal = [];
            foreach (array_merge($lokasiAlat, $lokasiBahan, $lokasiInstrumen) as $lokasi) {
                if (isset($lokasiTotal[$lokasi['lokasi']])) {
                    $lokasiTotal[$lokasi['lokasi']] += $lokasi['total'];
                } else {
                    $lokasiTotal[$lokasi['lokasi']] = $lokasi['total'];
                }
            }
            arsort($lokasiTotal);
            $lokasiTotal = array_slice($lokasiTotal, 0, 5, true);

            return [
                'alat_sering_dipinjam' => $topAlat,
                'bahan_sering_dipakai' => $topBahan,
                'user_paling_aktif' => $topUser,
                'lokasi_terpopuler' => $lokasiTotal
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error getTopData: ' . $e->getMessage());
            return [
                'alat_sering_dipinjam' => [],
                'bahan_sering_dipakai' => [],
                'user_paling_aktif' => [],
                'lokasi_terpopuler' => []
            ];
        }
    }

    private function getChartData()
    {
        try {
            // Chart inventory
            $totalAlat = $this->alatModel->countAllResults();
            $totalBahan = $this->bahanModel->countAllResults();
            $totalInstrumen = $this->instrumenModel->countAllResults();

            $inventoryChart = [
                'labels' => ['Alat', 'Bahan', 'Instrumen'],
                'data' => [$totalAlat, $totalBahan, $totalInstrumen]
            ];

            // Chart aktivitas 7 hari terakhir
            $aktivitasMingguan = [
                'labels' => [],
                'data' => []
            ];

            for ($i = 6; $i >= 0; $i--) {
                $tanggal = date('Y-m-d', strtotime("-{$i} days"));
                $aktivitasMingguan['labels'][] = date('d/m', strtotime($tanggal));
                
                $totalHari = $this->logalatModel->where('DATE(tanggal_dipinjam)', $tanggal)->countAllResults() +
                            $this->logbahanModel->where('DATE(tanggal)', $tanggal)->countAllResults();
                
                $aktivitasMingguan['data'][] = $totalHari;
            }

            return [
                'inventory_chart' => $inventoryChart,
                'aktivitas_mingguan' => $aktivitasMingguan
            ];

        } catch (\Exception $e) {
            log_message('error', 'Error getChartData: ' . $e->getMessage());
            return [
                'inventory_chart' => [
                    'labels' => ['Alat', 'Bahan', 'Instrumen'],
                    'data' => [0, 0, 0]
                ],
                'aktivitas_mingguan' => [
                    'labels' => [],
                    'data' => []
                ]
            ];
        }
    }
}