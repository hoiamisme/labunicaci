<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemberitahuanModel;
use App\Models\AlatModel;
use App\Models\BahanModel;

class Pemberitahuan extends BaseController
{
    protected $db;
    protected $alatModel;
    protected $bahanModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
    }

    public function index()
    {
        $model = new PemberitahuanModel();

        $data['alat'] = $model->getLogAlatNotApproved();
        $data['alatDipinjam'] = $model->getLogAlatSedangDipinjam();
        $data['bahan'] = $model->getLogBahanNotApproved();

        return view('Pemberitahuan_form', $data);
    }

    public function approveAlat()
    {
        $id = $this->request->getPost('id_logalat');

        $log = $this->db->table('logalat')->where('id_logalat', $id)->get()->getRowArray();

        if ($log) {
            $alat = $this->alatModel->find($log['id_alat']);

            if ($alat) {
                if ($alat['jumlah_alat'] < $log['pengurangan']) {
                    return redirect()->back()->with('error', 'Stok alat tidak mencukupi.');
                }

                $this->alatModel->update($alat['id_alat'], [
                    'jumlah_alat' => $alat['jumlah_alat'] - $log['pengurangan']
                ]);

                $this->db->table('logalat')->where('id_logalat', $id)->update(['status' => 'rent approve']);

                return redirect()->back()->with('message', 'Alat disetujui dan stok telah diperbarui.');
            }
        }

        return redirect()->back()->with('error', 'Data alat tidak ditemukan.');
    }

    public function approveBahan()
    {
        $id = $this->request->getPost('id_logbahan');

        $log = $this->db->table('logbahan')->where('id_logbahan', $id)->get()->getRowArray();

        if ($log) {
            $bahan = $this->bahanModel->find($log['id_bahan']);

            if ($bahan) {
                if ($bahan['jumlah_bahan'] < $log['pengurangan']) {
                    return redirect()->back()->with('error', 'Stok bahan tidak mencukupi.');
                }

                $this->bahanModel->update($bahan['id_bahan'], [
                    'jumlah_bahan' => $bahan['jumlah_bahan'] - $log['pengurangan']
                ]);

                $this->db->table('logbahan')->where('id_logbahan', $id)->update(['status' => 'approve']);

                return redirect()->back()->with('message', 'Bahan disetujui dan stok telah diperbarui.');
            }
        }

        return redirect()->back()->with('error', 'Data bahan tidak ditemukan.');
    }

    public function declineAlat()
    {
        $id = $this->request->getPost('id_logalat');
        $this->db->table('logalat')->where('id_logalat', $id)->delete();
        return redirect()->back()->with('message', 'Permintaan peminjaman alat telah ditolak dan dihapus.');
    }

    public function declineBahan()
    {
        $id = $this->request->getPost('id_logbahan');
        $this->db->table('logbahan')->where('id_logbahan', $id)->delete();
        return redirect()->back()->with('message', 'Permintaan pengambilan bahan telah ditolak dan dihapus.');
    }

    public function returnAlat()
{
    $id = $this->request->getPost('id_logalat');
    $this->db->table('logalat')->where('id_logalat', $id)->update(['status' => 'return approve']);
    return redirect()->back()->with('message', 'Peminjaman alat telah dikonfirmasi kembali.');
}

}
