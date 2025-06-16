<?php

namespace App\Models;

use CodeIgniter\Model;

class LogbahanModel extends Model
{
    protected $table = 'logbahan';
    protected $primaryKey = 'id_logbahan';
    protected $allowedFields = [
        'id_regis',
        'id_bahan',
        'penambahan',
        'pengurangan',
        'tujuan_pemakaian',
        'tanggal',
        'status'
    ];
    protected $useTimestamps = false;
}
