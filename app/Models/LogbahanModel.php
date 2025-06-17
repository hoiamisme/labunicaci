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
        'pesan',
        'tanggal',
        'status'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';

    // Validasi data
    protected $validationRules = [
        'id_regis' => 'required|integer',
        'id_bahan' => 'required|integer',
        'status' => 'in_list[approve,not approve]'
    ];

    protected $validationMessages = [
        'id_regis' => [
            'required' => 'ID Registrasi harus diisi',
            'integer' => 'ID Registrasi harus berupa angka'
        ],
        'id_bahan' => [
            'required' => 'ID Bahan harus diisi',
            'integer' => 'ID Bahan harus berupa angka'
        ],
        'status' => [
            'in_list' => 'Status harus approve atau not approve'
        ]
    ];

    /**
     * Ambil data logbahan dengan join ke tabel terkait
     */
    public function getLogbahanWithDetails($id = null)
    {
        $builder = $this->db->table('logbahan')
            ->select('
                logbahan.*,
                registrasi.nama_lengkap,
                registrasi.email,
                bahan.nama_bahan,
                bahan.satuan_bahan,
                bahan.lokasi as lokasi_bahan
            ')
            ->join('registrasi', 'registrasi.id_regis = logbahan.id_regis', 'left')
            ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan', 'left');

        if ($id) {
            return $builder->where('logbahan.id_logbahan', $id)->get()->getRowArray();
        }

        return $builder->orderBy('logbahan.tanggal', 'DESC')->get()->getResultArray();
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
     * Ambil statistik logbahan
     */
    public function getStatistik()
    {
        return [
            'total' => $this->countAll(),
            'approve' => $this->where('status', 'approve')->countAllResults(false),
            'pending' => $this->where('status', 'not approve')->countAllResults(false),
            'hari_ini' => $this->where('DATE(tanggal)', date('Y-m-d'))->countAllResults(false)
        ];
    }
}
