<?php

namespace App\Models;

use CodeIgniter\Model;

class AlatModel extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    protected $allowedFields = ['nama_alat', 'jumlah_alat', 'lokasi'];
}
