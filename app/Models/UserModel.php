<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user_manage'; // <- ganti sesuai nama tabel yang benar
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['nama', 'email', 'status']; // sesuaikan dengan field tabelmu
}
