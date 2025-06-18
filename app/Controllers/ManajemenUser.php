<?php
// filepath: c:\xamppfadhil\htdocs\labunicaci\app\Controllers\ManajemenUser.php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ManajemenUserModel;
use CodeIgniter\HTTP\ResponseInterface;

class ManajemenUser extends BaseController
{
    protected $manajemenUserModel;

    public function __construct()
    {
        $this->manajemenUserModel = new ManajemenUserModel();
    }

    /**
     * Halaman utama manajemen user
     */
    public function index()
    {
        // Cek login dan role admin
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cek apakah user adalah admin
        if (session()->get('role') !== 'admin') {
            session()->setFlashdata('error', 'Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');
            return redirect()->to('/dashboard');
        }

        // Ambil parameter filter dan pagination
        $search = $this->request->getGet('search') ?? '';
        $role = $this->request->getGet('role') ?? '';
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = 10;

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        try {
            // Ambil data users
            $users = $this->manajemenUserModel->getAllUsers($perPage, $offset, $search, $role);
            $totalUsers = $this->manajemenUserModel->countUsers($search, $role);

            // Statistik
            $stats = $this->manajemenUserModel->getUserStats();
            $prodiList = $this->manajemenUserModel->getProdiList();
            $cohortList = $this->manajemenUserModel->getCohortList();

            // Pagination
            $totalPages = ceil($totalUsers / $perPage);

            $data = [
                'users' => $users,
                'stats' => $stats,
                'prodiList' => $prodiList,
                'cohortList' => $cohortList,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'totalUsers' => $totalUsers,
                'search' => $search,
                'role' => $role,
                'perPage' => $perPage
            ];

            return view('ManajemenUser_form', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser index error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
            return view('ManajemenUser_form', [
                'users' => [],
                'stats' => ['total_user' => 0, 'total_admin' => 0, 'total_regular_user' => 0],
                'prodiList' => [],
                'cohortList' => [],
                'currentPage' => 1,
                'totalPages' => 1,
                'totalUsers' => 0,
                'search' => '',
                'role' => '',
                'perPage' => 10
            ]);
        }
    }

    /**
     * Detail user - PERBAIKAN: Tambahkan header JSON dan debug
     */
    public function detail($id)
    {
        // PERBAIKAN: Set header JSON terlebih dahulu
        $this->response->setContentType('application/json');

        try {
            // Cek login dan role admin
            if (!session()->get('logged_in')) {
                return $this->response->setJSON(['success' => false, 'message' => 'Not logged in']);
            }

            if (session()->get('role') !== 'admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized - Admin only']);
            }

            // Validasi ID
            if (!$id || !is_numeric($id)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid user ID']);
            }

            // Cari user
            $user = $this->manajemenUserModel->find($id);

            if (!$user) {
                return $this->response->setJSON(['success' => false, 'message' => 'User tidak ditemukan']);
            }

            // Hilangkan password dari response
            unset($user['password']);

            return $this->response->setJSON([
                'success' => true,
                'data' => $user
            ]);

        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser detail error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Update role user - PERBAIKAN: Improve validation
     */
    public function updateRole()
    {
        $this->response->setContentType('application/json');

        try {
            // Cek login dan role admin
            if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $userId = $this->request->getPost('user_id');
            $newRole = $this->request->getPost('role');

            // Validasi input
            if (!$userId || !in_array($newRole, ['user', 'admin'])) {
                return $this->response->setJSON(['success' => false, 'message' => 'Invalid data']);
            }

            // Cek jika mengubah admin terakhir menjadi user
            if ($newRole === 'user') {
                $adminCount = $this->manajemenUserModel->where('role', 'admin')->countAllResults();
                $currentUser = $this->manajemenUserModel->find($userId);
                
                if ($currentUser && $currentUser['role'] === 'admin' && $adminCount <= 1) {
                    return $this->response->setJSON([
                        'success' => false, 
                        'message' => 'Tidak dapat mengubah admin terakhir menjadi user!'
                    ]);
                }
            }

            // Update role
            $result = $this->manajemenUserModel->updateRole($userId, $newRole);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => "Role berhasil diubah menjadi $newRole"
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Gagal mengubah role'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser updateRole error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus user - PERBAIKAN: Better validation
     */
    public function deleteUser()
    {
        $this->response->setContentType('application/json');

        try {
            // Cek login dan role admin
            if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $userId = $this->request->getPost('user_id');

            if (!$userId || !is_numeric($userId)) {
                return $this->response->setJSON(['success' => false, 'message' => 'User ID tidak valid']);
            }

            // Cek apakah user mencoba menghapus dirinya sendiri
            if ($userId == session()->get('id_regis')) {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Tidak dapat menghapus akun sendiri!'
                ]);
            }

            // Hapus user dengan proteksi admin terakhir
            $result = $this->manajemenUserModel->deleteUser($userId);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true, 
                    'message' => 'User berhasil dihapus'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false, 
                    'message' => 'Gagal menghapus user atau ini adalah admin terakhir'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser deleteUser error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset password user - PERBAIKAN: Better validation
     */
    public function resetPassword()
    {
        $this->response->setContentType('application/json');

        try {
            // Cek login dan role admin
            if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
                return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
            }

            $userId = $this->request->getPost('user_id');
            $newPassword = $this->request->getPost('new_password');

            if (!$userId || !$newPassword || strlen($newPassword) < 6) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Password minimal 6 karakter'
                ]);
            }

            $result = $this->manajemenUserModel->updatePassword($userId, $newPassword);

            if ($result) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Password berhasil direset'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Gagal reset password'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser resetPassword error: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export data user ke CSV
     */
    public function export()
    {
        // Cek login dan role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        try {
            $users = $this->manajemenUserModel->findAll();

            $filename = 'users_' . date('Y-m-d_H-i-s') . '.csv';
            
            // Set headers untuk download
            $this->response->setHeader('Content-Type', 'text/csv; charset=UTF-8');
            $this->response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM untuk UTF-8
            
            // Header CSV
            fputcsv($output, [
                'ID', 'Nama Lengkap', 'Email', 'Cohort', 'Program Studi', 'Role'
            ]);
            
            // Data users
            foreach ($users as $user) {
                fputcsv($output, [
                    $user['id_regis'],
                    $user['nama_lengkap'],
                    $user['email'],
                    $user['cohort'],
                    $user['prodi'],
                    $user['role']
                ]);
            }
            
            fclose($output);
            exit();
            
        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser export error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal export data: ' . $e->getMessage());
            return redirect()->to('/manajemen-user');
        }
    }

    /**
     * Statistik detail
     */
    public function statistik()
    {
        // Cek login dan role admin
        if (!session()->get('logged_in') || session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard');
        }

        try {
            $stats = $this->manajemenUserModel->getUserStats();
            $prodiStats = [];
            $cohortStats = [];

            // Statistik per prodi
            $prodiList = $this->manajemenUserModel->getProdiList();
            foreach ($prodiList as $prodi) {
                $count = $this->manajemenUserModel->where('prodi', $prodi)->countAllResults();
                $prodiStats[$prodi] = $count;
            }

            // Statistik per cohort
            $cohortList = $this->manajemenUserModel->getCohortList();
            foreach ($cohortList as $cohort) {
                $count = $this->manajemenUserModel->where('cohort', $cohort)->countAllResults();
                $cohortStats[$cohort] = $count;
            }

            $data = [
                'stats' => $stats,
                'prodiStats' => $prodiStats,
                'cohortStats' => $cohortStats
            ];

            return view('ManajemenUser_statistik', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'ManajemenUser statistik error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal memuat statistik: ' . $e->getMessage());
            return redirect()->to('/manajemen-user');
        }
    }
}
