<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanModel extends Model
{
    protected $table = 'bahan';
    protected $primaryKey = 'id_bahan';
    protected $allowedFields = ['nama_bahan', 'jumlah_bahan', 'satuan_bahan', 'lokasi'];
}
