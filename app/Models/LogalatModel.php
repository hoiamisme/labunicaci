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
        'pesan',
        'tanggal_dipinjam',
        'tanggal_kembali',
        'status'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Validasi data
    protected $validationRules = [
        'id_regis' => 'required|integer',
        'id_alat' => 'required|integer',
        'status' => 'in_list[approve,not approve]'
    ];

    protected $validationMessages = [
        'id_regis' => [
            'required' => 'ID Registrasi harus diisi',
            'integer' => 'ID Registrasi harus berupa angka'
        ],
        'id_alat' => [
            'required' => 'ID Alat harus diisi',
            'integer' => 'ID Alat harus berupa angka'
        ],
        'status' => [
            'in_list' => 'Status harus approve atau not approve'
        ]
    ];

    /**
     * Ambil data logalat dengan join ke tabel terkait
     */
    public function getLogalatWithDetails($id = null)
    {
        $builder = $this->db->table('logalat')
            ->select('
                logalat.*,
                registrasi.nama_lengkap,
                registrasi.email,
                alat.nama_alat,
                alat.lokasi as lokasi_alat
            ')
            ->join('registrasi', 'registrasi.id_regis = logalat.id_regis', 'left')
            ->join('alat', 'alat.id_alat = logalat.id_alat', 'left');

        if ($id) {
            return $builder->where('logalat.id_logalat', $id)->get()->getRowArray();
        }

        return $builder->orderBy('logalat.tanggal_dipinjam', 'DESC')->get()->getResultArray();
    }

    /**
     * Update status approval
     */
    public function updateStatus($id, $status, $pesan = null)
    {
        $data = ['status' => $status];
        if ($pesan) {
            $data['pesan'] = $pesan;
        }
        
        return $this->update($id, $data);
    }

    /**
     * Ambil statistik logalat
     */
    public function getStatistik()
    {
        return [
            'total' => $this->countAll(),
            'approve' => $this->where('status', 'approve')->countAllResults(false),
            'pending' => $this->where('status', 'not approve')->countAllResults(false),
            'hari_ini' => $this->where('DATE(tanggal_dipinjam)', date('Y-m-d'))->countAllResults(false)
        ];
    }
}
