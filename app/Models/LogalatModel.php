<?php

namespace App\Models;

use CodeIgniter\Model;

class LogalatModel extends Model
{
    protected $table = 'logalat';
    protected $primaryKey = 'id_logalat';
    protected $allowedFields = [
        'id_regis',
        'id_alat',
        'penambahan',
        'pengurangan',
        'tujuan_pemakaian',
        'tanggal_dipinjam',
        'status'
    ];
    protected $useTimestamps = false;
}
