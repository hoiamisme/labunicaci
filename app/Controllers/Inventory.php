<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AlatModel;
use App\Models\BahanModel;
use App\Models\InstrumenModel;
use CodeIgniter\HTTP\ResponseInterface;

class Inventory extends BaseController
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

    /**
     * Halaman utama inventory
     */
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Ambil parameter filter
        $category = $this->request->getGet('category') ?? 'all';
        $search = $this->request->getGet('search') ?? '';
        $location = $this->request->getGet('location') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        // Data untuk view
        $data = [
            'category' => $category,
            'search' => $search,
            'location' => $location,
            'currentPage' => $page,
            'perPage' => $perPage
        ];

        // Ambil data berdasarkan kategori
        switch ($category) {
            case 'alat':
                $data = array_merge($data, $this->getAlatData($search, $location, $page, $perPage));
                break;
            case 'bahan':
                $data = array_merge($data, $this->getBahanData($search, $location, $page, $perPage));
                break;
            case 'instrumen':
                $data = array_merge($data, $this->getInstrumenData($search, $location, $page, $perPage));
                break;
            default:
                $data = array_merge($data, $this->getAllInventoryData($search, $location, $page, $perPage));
                break;
        }

        // Statistik
        $data['statistics'] = $this->getStatistics();
        $data['locations'] = $this->getAllLocations();

        return view('Inventory_view', $data);
    }

    /**
     * Halaman daftar alat
     */
    public function daftar_alat()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search') ?? '';
        $location = $this->request->getGet('location') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data = array_merge([
            'search' => $search,
            'location' => $location,
            'currentPage' => $page,
            'perPage' => $perPage
        ], $this->getAlatData($search, $location, $page, $perPage));

        $data['locations'] = $this->getAllLocations();
        $data['statistics'] = $this->getStatistics();

        return view('daftar_alat_form', $data);
    }

    /**
     * Halaman daftar bahan
     */
    public function daftar_bahan()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search') ?? '';
        $location = $this->request->getGet('location') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data = array_merge([
            'search' => $search,
            'location' => $location,
            'currentPage' => $page,
            'perPage' => $perPage
        ], $this->getBahanData($search, $location, $page, $perPage));

        $data['locations'] = $this->getAllLocations();
        $data['statistics'] = $this->getStatistics();

        return view('daftar_bahan_form', $data);
    }

    /**
     * Halaman daftar instrumen
     */
    public function daftar_instrumen()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $search = $this->request->getGet('search') ?? '';
        $location = $this->request->getGet('location') ?? '';
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 20;

        $data = array_merge([
            'search' => $search,
            'location' => $location,
            'currentPage' => $page,
            'perPage' => $perPage
        ], $this->getInstrumenData($search, $location, $page, $perPage));

        $data['locations'] = $this->getAllLocations();
        $data['statistics'] = $this->getStatistics();

        return view('daftar_instrumen_form', $data);
    }

    /**
     * Ambil data alat
     */
    private function getAlatData($search, $location, $page, $perPage)
    {
        $builder = $this->alatModel->builder();
        
        if (!empty($search)) {
            $builder->like('nama_alat', $search);
        }
        
        if (!empty($location)) {
            $builder->like('lokasi', $location);
        }

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $items = $builder->orderBy('nama_alat', 'ASC')
                        ->limit($perPage, $offset)
                        ->get()
                        ->getResultArray();

        return [
            'items' => $items,
            'totalItems' => $total,
            'totalPages' => ceil($total / $perPage),
            'itemType' => 'alat'
        ];
    }

    /**
     * Ambil data bahan
     */
    private function getBahanData($search, $location, $page, $perPage)
    {
        $builder = $this->bahanModel->builder();
        
        if (!empty($search)) {
            $builder->like('nama_bahan', $search);
        }
        
        if (!empty($location)) {
            $builder->like('lokasi', $location);
        }

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $items = $builder->orderBy('nama_bahan', 'ASC')
                        ->limit($perPage, $offset)
                        ->get()
                        ->getResultArray();

        return [
            'items' => $items,
            'totalItems' => $total,
            'totalPages' => ceil($total / $perPage),
            'itemType' => 'bahan'
        ];
    }

    /**
     * Ambil data instrumen
     */
    private function getInstrumenData($search, $location, $page, $perPage)
    {
        $builder = $this->instrumenModel->builder();
        
        if (!empty($search)) {
            $builder->like('nama_instrumen', $search);
        }
        
        if (!empty($location)) {
            $builder->like('lokasi', $location);
        }

        $total = $builder->countAllResults(false);
        $offset = ($page - 1) * $perPage;
        
        $items = $builder->orderBy('nama_instrumen', 'ASC')
                        ->limit($perPage, $offset)
                        ->get()
                        ->getResultArray();

        return [
            'items' => $items,
            'totalItems' => $total,
            'totalPages' => ceil($total / $perPage),
            'itemType' => 'instrumen'
        ];
    }

    /**
     * Ambil semua data inventory (gabungan)
     */
    private function getAllInventoryData($search, $location, $page, $perPage)
    {
        $allItems = [];

        // Ambil data alat
        $alat = $this->alatModel->findAll();
        foreach ($alat as $item) {
            $allItems[] = [
                'id' => $item['id_alat'],
                'nama' => $item['nama_alat'],
                'jumlah' => $item['jumlah_alat'],
                'satuan' => 'unit',
                'lokasi' => $item['lokasi'],
                'type' => 'alat'
            ];
        }

        // Ambil data bahan
        $bahan = $this->bahanModel->findAll();
        foreach ($bahan as $item) {
            $allItems[] = [
                'id' => $item['id_bahan'],
                'nama' => $item['nama_bahan'],
                'jumlah' => $item['jumlah_bahan'],
                'satuan' => $item['satuan_bahan'],
                'lokasi' => $item['lokasi'],
                'type' => 'bahan'
            ];
        }

        // Ambil data instrumen
        $instrumen = $this->instrumenModel->findAll();
        foreach ($instrumen as $item) {
            $allItems[] = [
                'id' => $item['id_instrumen'],
                'nama' => $item['nama_instrumen'],
                'jumlah' => $item['jumlah_instrumen'],
                'satuan' => 'unit',
                'lokasi' => $item['lokasi'],
                'type' => 'instrumen'
            ];
        }

        // Filter berdasarkan search dan location
        if (!empty($search) || !empty($location)) {
            $allItems = array_filter($allItems, function($item) use ($search, $location) {
                $matchSearch = empty($search) || stripos($item['nama'], $search) !== false;
                $matchLocation = empty($location) || stripos($item['lokasi'], $location) !== false;
                return $matchSearch && $matchLocation;
            });
        }

        // Sort by name
        usort($allItems, function($a, $b) {
            return strcmp($a['nama'], $b['nama']);
        });

        // Pagination
        $total = count($allItems);
        $offset = ($page - 1) * $perPage;
        $items = array_slice($allItems, $offset, $perPage);

        return [
            'items' => $items,
            'totalItems' => $total,
            'totalPages' => ceil($total / $perPage),
            'itemType' => 'all'
        ];
    }

    /**
     * Ambil statistik inventory
     */
    private function getStatistics()
    {
        return [
            'total_alat' => $this->alatModel->countAll(),
            'total_bahan' => $this->bahanModel->countAll(),
            'total_instrumen' => $this->instrumenModel->countAll(),
            'low_stock_alat' => $this->alatModel->where('jumlah_alat <', 5)->countAllResults(),
            'low_stock_bahan' => $this->bahanModel->where('jumlah_bahan <', 10)->countAllResults(),
            'low_stock_instrumen' => $this->instrumenModel->where('jumlah_instrumen <', 3)->countAllResults(),
        ];
    }

    /**
     * Ambil semua lokasi
     */
    private function getAllLocations()
    {
        $locations = [];
        
        // Dari alat
        $alatLokasi = $this->alatModel->select('lokasi')->distinct()->where('lokasi IS NOT NULL')->findAll();
        foreach ($alatLokasi as $loc) {
            if (!empty($loc['lokasi'])) {
                $locations[] = $loc['lokasi'];
            }
        }
        
        // Dari bahan
        $bahanLokasi = $this->bahanModel->select('lokasi')->distinct()->where('lokasi IS NOT NULL')->findAll();
        foreach ($bahanLokasi as $loc) {
            if (!empty($loc['lokasi'])) {
                $locations[] = $loc['lokasi'];
            }
        }
        
        // Dari instrumen
        $instrumenLokasi = $this->instrumenModel->select('lokasi')->distinct()->where('lokasi IS NOT NULL')->findAll();
        foreach ($instrumenLokasi as $loc) {
            if (!empty($loc['lokasi'])) {
                $locations[] = $loc['lokasi'];
            }
        }

        return array_unique($locations);
    }

    /**
     * Detail item (AJAX)
     */
    public function detail($type, $id)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $item = null;
        switch ($type) {
            case 'alat':
                $item = $this->alatModel->find($id);
                break;
            case 'bahan':
                $item = $this->bahanModel->find($id);
                break;
            case 'instrumen':
                $item = $this->instrumenModel->find($id);
                break;
        }

        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item tidak ditemukan']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $item,
            'type' => $type
        ]);
    }

    /**
     * Hapus alat (Admin only)
     */
    public function hapus_alat($id)
    {
        // Cek login dan role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $alat = $this->alatModel->find($id);
            if (!$alat) {
                return $this->response->setJSON(['success' => false, 'message' => 'Alat tidak ditemukan']);
            }

            if ($this->alatModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Alat berhasil dihapus']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus alat']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error hapus alat: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
    }

    /**
     * Hapus bahan (Admin only)
     */
    public function hapus_bahan($id)
    {
        // Cek login dan role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $bahan = $this->bahanModel->find($id);
            if (!$bahan) {
                return $this->response->setJSON(['success' => false, 'message' => 'Bahan tidak ditemukan']);
            }

            if ($this->bahanModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Bahan berhasil dihapus']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus bahan']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error hapus bahan: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
    }

    /**
     * Hapus instrumen (Admin only)
     */
    public function hapus_instrumen($id)
    {
        // Cek login dan role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Access denied']);
        }

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        try {
            $instrumen = $this->instrumenModel->find($id);
            if (!$instrumen) {
                return $this->response->setJSON(['success' => false, 'message' => 'Instrumen tidak ditemukan']);
            }

            if ($this->instrumenModel->delete($id)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Instrumen berhasil dihapus']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus instrumen']);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error hapus instrumen: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan sistem']);
        }
    }
}