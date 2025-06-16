<?php

namespace App\Models;

use CodeIgniter\Model;

class InstrumenModel extends Model
{
    protected $table = 'instrumen';
    protected $primaryKey = 'id_instrumen';
    protected $allowedFields = ['nama_instrumen', 'jumlah_instrumen', 'lokasi'];
}
