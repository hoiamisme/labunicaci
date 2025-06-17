<?php

namespace App\Models;

use CodeIgniter\Model;

class PemberitahuanModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getLogAlatNotApproved()
    {
        return $this->db->table('logalat')
            ->select('logalat.id_logalat, registrasi.nama_lengkap, alat.nama_alat, logalat.pengurangan, logalat.tujuan_pemakaian, logalat.pesan, logalat.tanggal_dipinjam, logalat.tanggal_kembali, logalat.status')
            ->join('alat', 'alat.id_alat = logalat.id_alat')
            ->join('registrasi', 'registrasi.id_regis = logalat.id_regis')
            ->where('logalat.status', 'not approve')
            ->get()
            ->getResult();
    }

    public function getLogBahanNotApproved()
    {
        return $this->db->table('logbahan')
            ->select('logbahan.id_logbahan, registrasi.nama_lengkap, bahan.nama_bahan, logbahan.pengurangan, logbahan.tujuan_pemakaian, logbahan.pesan, logbahan.tanggal, logbahan.status')
            ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan')
            ->join('registrasi', 'registrasi.id_regis = logbahan.id_regis')
            ->where('logbahan.status', 'not approve')
            ->get()
            ->getResult();
    }

    public function getLogAlatSedangDipinjam()
{
    return $this->db->table('logalat')
        ->select('logalat.id_logalat, registrasi.nama_lengkap, alat.nama_alat, logalat.pengurangan, logalat.tujuan_pemakaian, logalat.pesan, logalat.tanggal_dipinjam, logalat.tanggal_kembali, logalat.status')
        ->join('alat', 'alat.id_alat = logalat.id_alat')
        ->join('registrasi', 'registrasi.id_regis = logalat.id_regis')
        ->where('logalat.status', 'rent approve')
        ->get()
        ->getResult();
}

}
