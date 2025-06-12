<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilesModel extends Model
{
    protected $table      = 'registrasi';
    protected $primaryKey = 'id_regis';

    protected $allowedFields = [
        'nama_lengkap', 'email', 'cohort', 'prodi', 'password', 'foto_profil'
    ];
}
