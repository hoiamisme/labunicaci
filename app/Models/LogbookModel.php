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
    
    public function index()
    {
        $alatModel = new \App\Models\AlatModel();
        $bahanModel = new \App\Models\BahanModel();

        $data['alat'] = $alatModel->findAll();
        $data['bahan'] = $bahanModel->findAll();

        // Contoh data lokasi, bisa juga dari database
        $data['lokasi'] = ['Lemari 1', 'Rak 2', 'Gudang', 'Laboratorium'];

        return view('Manajemen_form', $data);
    }
}

class AlatModel extends Model
{
    protected $table = 'alat';
    protected $primaryKey = 'id_alat';
    protected $allowedFields = ['id_alat', 'nama_alat', 'jumlah_alat', 'lokasi'];
}

class BahanModel extends Model
{
    protected $table = 'bahan';
    protected $primaryKey = 'id_bahan';
    protected $allowedFields = ['id_bahan', 'nama_bahan', 'jumlah_bahan', 'satuan_bahan', 'lokasi'];
}

class LogbookModel extends Model
{
    public function getLogbook($tipe = null)
    {
        $db = \Config\Database::connect();

        // Query logalat (peminjaman alat)
        $queryAlat = $db->table('logalat')
            ->select('registrasi.nama_lengkap as nama_pengguna, alat.nama_alat as alat, NULL as bahan, logalat.tanggal_dipinjam as waktu, logalat.tujuan_pemakaian as keterangan')
            ->join('registrasi', 'registrasi.id_regis = logalat.id_regis', 'left')
            ->join('alat', 'alat.id_alat = logalat.id_alat', 'left');

        // Query logbahan (peminjaman bahan)
        $queryBahan = $db->table('logbahan')
            ->select('registrasi.nama_lengkap as nama_pengguna, NULL as alat, bahan.nama_bahan as bahan, logbahan.tanggal as waktu, logbahan.tujuan_pemakaian as keterangan')
            ->join('registrasi', 'registrasi.id_regis = logbahan.id_regis', 'left')
            ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan', 'left');

        if ($tipe == 'peminjaman') {
            return $queryAlat->get()->getResultArray();
        } elseif ($tipe == 'pemakaian') {
            return $queryBahan->get()->getResultArray();
        } else {
            // Gabungkan keduanya
            $sql = $queryAlat->getCompiledSelect() . " UNION ALL " . $queryBahan->getCompiledSelect();
            return $db->query($sql)->getResultArray();
        }
    }
}
