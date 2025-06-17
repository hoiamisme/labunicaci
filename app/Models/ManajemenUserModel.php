<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenUserModel extends Model
{
    protected $table = 'registrasi';
    protected $primaryKey = 'id_regis';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nama_lengkap',
        'email',
        'cohort',
        'prodi',
        'password',
        'foto_profil',
        'role'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';

    // Validation
    protected $validationRules = [
        'nama_lengkap' => 'required|min_length[3]|max_length[50]',
        'email'        => 'required|valid_email|max_length[50]|is_unique[registrasi.email,id_regis,{id_regis}]',
        'cohort'       => 'required|max_length[10]',
        'prodi'        => 'required|max_length[50]',
        'password'     => 'required|min_length[6]',
        'role'         => 'required|in_list[user,admin]'
    ];

    protected $validationMessages = [
        'nama_lengkap' => [
            'required'   => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
            'max_length' => 'Nama lengkap maksimal 50 karakter'
        ],
        'email' => [
            'required'    => 'Email harus diisi',
            'valid_email' => 'Format email tidak valid',
            'is_unique'   => 'Email sudah terdaftar'
        ],
        'role' => [
            'required' => 'Role harus dipilih',
            'in_list'  => 'Role hanya boleh user atau admin'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Ambil semua user dengan pagination dan filter
     */
    public function getAllUsers($limit = 10, $offset = 0, $search = '', $role = '')
    {
        $builder = $this->builder();
        
        // Filter pencarian
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('email', $search)
                    ->orLike('cohort', $search)
                    ->orLike('prodi', $search)
                    ->groupEnd();
        }
        
        // Filter role
        if (!empty($role) && in_array($role, ['user', 'admin'])) {
            $builder->where('role', $role);
        }
        
        // Urutkan berdasarkan nama
        $builder->orderBy('nama_lengkap', 'ASC');
        $builder->limit($limit, $offset);
        
        return $builder->get()->getResultArray();
    }

    /**
     * Hitung total user dengan filter
     */
    public function countUsers($search = '', $role = '')
    {
        $builder = $this->builder();
        
        if (!empty($search)) {
            $builder->groupStart()
                    ->like('nama_lengkap', $search)
                    ->orLike('email', $search)
                    ->orLike('cohort', $search)
                    ->orLike('prodi', $search)
                    ->groupEnd();
        }
        
        if (!empty($role) && in_array($role, ['user', 'admin'])) {
            $builder->where('role', $role);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Update role user
     */
    public function updateRole($id, $role)
    {
        if (!in_array($role, ['user', 'admin'])) {
            return false;
        }
        
        return $this->update($id, ['role' => $role]);
    }

    /**
     * Statistik user
     */
    public function getUserStats()
    {
        $total = $this->countAllResults();
        $admin = $this->where('role', 'admin')->countAllResults();
        $user = $this->where('role', 'user')->countAllResults();
        
        return [
            'total_user' => $total,
            'total_admin' => $admin,
            'total_regular_user' => $user,
        ];
    }

    /**
     * Ambil daftar program studi yang ada
     */
    public function getProdiList()
    {
        return $this->select('prodi')
                    ->distinct()
                    ->where('prodi !=', '')
                    ->orderBy('prodi', 'ASC')
                    ->findColumn('prodi');
    }

    /**
     * Ambil daftar cohort yang ada
     */
    public function getCohortList()
    {
        return $this->select('cohort')
                    ->distinct()
                    ->where('cohort !=', '')
                    ->orderBy('cohort', 'ASC')
                    ->findColumn('cohort');
    }

    /**
     * Hapus user dengan proteksi admin terakhir
     */
    public function deleteUser($id)
    {
        // Pastikan tidak menghapus admin terakhir
        $adminCount = $this->where('role', 'admin')->countAllResults();
        $user = $this->find($id);
        
        if ($user && $user['role'] === 'admin' && $adminCount <= 1) {
            return false; // Tidak boleh hapus admin terakhir
        }
        
        return $this->delete($id);
    }

    /**
     * Update password dengan hash
     */
    public function updatePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
}
