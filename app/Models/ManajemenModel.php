<?php

namespace App\Models;

use CodeIgniter\Model;

class ManajemenModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = ''; // dinamis berdasarkan jenis
    protected $primaryKey       = '';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [];

    public function setTableByJenis($jenis)
    {
        if ($jenis === 'alat') {
            $this->table = 'alat';
            $this->primaryKey = 'id_alat';
            $this->allowedFields = ['nama_alat', 'jumlah_alat', 'lokasi'];
        } elseif ($jenis === 'bahan') {
            $this->table = 'bahan';
            $this->primaryKey = 'id_bahan';
            $this->allowedFields = ['nama_bahan', 'jumlah_bahan', 'satuan_bahan', 'lokasi'];
        }
    }

    public function findByName($jenis, $nama)
    {
        $this->setTableByJenis($jenis);
        if ($jenis === 'alat') {
            return $this->where('nama_alat', $nama)->first();
        } elseif ($jenis === 'bahan') {
            return $this->where('nama_bahan', $nama)->first();
        }
    }
} 
