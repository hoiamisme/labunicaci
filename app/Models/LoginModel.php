<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table            = 'registrasi';
    protected $primaryKey       = 'id_login';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_regis', 'email', 'password'];

    public function getLoginWithRegistrasi($id_login = null)
{
    $this->select('login.*, registrasi.nama_lengkap, registrasi.no_induk, registrasi.jabatan')
         ->join('registrasi', 'registrasi.id_regis = login.id_regis');

    if ($id_login !== null) {
        return $this->where('login.id_login', $id_login)->first();
    }

    return $this->findAll();
}
}

//     protected bool $allowEmptyInserts = false;
//     protected bool $updateOnlyChanged = true;

//     protected array $casts = [];
//     protected array $castHandlers = [];

//     // Dates
//     protected $useTimestamps = false;
//     protected $dateFormat    = 'datetime';
//     protected $createdField  = 'created_at';
//     protected $updatedField  = 'updated_at';
//     protected $deletedField  = 'deleted_at';

//     // Validation
//     protected $validationRules      = [];
//     protected $validationMessages   = [];
//     protected $skipValidation       = false;
//     protected $cleanValidationRules = true;

//     // Callbacks
//     protected $allowCallbacks = true;
//     protected $beforeInsert   = [];
//     protected $afterInsert    = [];
//     protected $beforeUpdate   = [];
//     protected $afterUpdate    = [];
//     protected $beforeFind     = [];
//     protected $afterFind      = [];
//     protected $beforeDelete   = [];
//     protected $afterDelete    = [];
// }
