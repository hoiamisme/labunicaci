<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PemberitahuanModel;
use App\Models\AlatModel;
use App\Models\BahanModel;
use CodeIgniter\I18n\Time;
use App\Libraries\EmailService;

class Pemberitahuan extends BaseController
{
    protected $db;
    protected $alatModel;
    protected $bahanModel;
    protected $emailService;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->alatModel = new AlatModel();
        $this->bahanModel = new BahanModel();
        $this->emailService = new EmailService();
    }

    public function index()
{
    $model = new PemberitahuanModel();

    $id_regis = session()->get('id_regis');
    $role = session()->get('role');

    // Hanya alat yang sedang dipinjam berdasarkan user login
    $data['alatDipinjam'] = $model->getLogAlatSedangDipinjam($id_regis);

    // Jika role admin, ambil semua log alat dan bahan yang belum disetujui
    if ($role === 'admin') {
        $data['alat'] = $model->getAllLogAlatNotApproved();
        $data['bahan'] = $model->getLogBahanNotApproved();
    } else {
        $data['alat'] = [];
        $data['bahan'] = [];
    }

    return view('Pemberitahuan_form', $data);
}



    public function approveAlat()
    {
        $id = $this->request->getPost('id_logalat');
        
        // Ambil data lengkap untuk email
        $log = $this->db->table('logalat')
            ->select('logalat.*, registrasi.email, registrasi.nama_lengkap, alat.nama_alat')
            ->join('registrasi', 'registrasi.id_regis = logalat.id_regis')
            ->join('alat', 'alat.id_alat = logalat.id_alat')
            ->where('id_logalat', $id)
            ->get()
            ->getRowArray();

        if ($log) {
            $alat = $this->alatModel->find($log['id_alat']);

            if (!$alat) {
                return redirect()->back()->with('error', 'Data alat tidak ditemukan.');
            }

            if ($log['status'] === 'not approve') {
                if ($alat['jumlah_alat'] < $log['pengurangan']) {
                    return redirect()->back()->with('error', 'Stok alat tidak mencukupi.');
                }

                $this->alatModel->update($alat['id_alat'], [
                    'jumlah_alat' => $alat['jumlah_alat'] - $log['pengurangan']
                ]);

                $this->db->table('logalat')->where('id_logalat', $id)->update(['status' => 'rent approve']);

                // Kirim email notifikasi approve
                $this->emailService->sendStatusUpdateNotification(
                    $log['email'], 
                    $log['nama_lengkap'], 
                    'approve', 
                    'alat', 
                    $log['nama_alat'],
                    'Permintaan peminjaman disetujui. Silakan datang ke laboratorium.'
                );

                return redirect()->back()->with('message', '✅ Peminjaman disetujui dan email notifikasi telah dikirim.');

            } elseif ($log['status'] === 'return approve') {
                $this->alatModel->update($alat['id_alat'], [
                    'jumlah_alat' => $alat['jumlah_alat'] + $log['pengurangan']
                ]);

                $this->db->table('logalat')->where('id_logalat', $id)->update([
                    'status' => 'approve',
                    'tanggal_kembali' => Time::now()
                ]);

                // Kirim email notifikasi pengembalian
                $this->emailService->sendStatusUpdateNotification(
                    $log['email'], 
                    $log['nama_lengkap'], 
                    'approve', 
                    'alat', 
                    $log['nama_alat'],
                    'Pengembalian alat telah dikonfirmasi. Terima kasih.'
                );

                return redirect()->back()->with('message', '✅ Pengembalian dikonfirmasi dan email notifikasi telah dikirim.');
            }
        }

        return redirect()->back()->with('error', 'Data log tidak valid.');
    }

    public function approveBahan()
    {
        $id = $this->request->getPost('id_logbahan');
        
        // Ambil data lengkap untuk email
        $log = $this->db->table('logbahan')
            ->select('logbahan.*, registrasi.email, registrasi.nama_lengkap, bahan.nama_bahan')
            ->join('registrasi', 'registrasi.id_regis = logbahan.id_regis')
            ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan')
            ->where('id_logbahan', $id)
            ->get()
            ->getRowArray();

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

                // Kirim email notifikasi approve
                $this->emailService->sendStatusUpdateNotification(
                    $log['email'], 
                    $log['nama_lengkap'], 
                    'approve', 
                    'bahan', 
                    $log['nama_bahan'],
                    'Permintaan pengambilan bahan disetujui. Silakan datang ke laboratorium.'
                );

                return redirect()->back()->with('message', '✅ Bahan disetujui dan email notifikasi telah dikirim.');
            }
        }

        return redirect()->back()->with('error', 'Data bahan tidak ditemukan.');
    }

    public function declineAlat()
    {
        $id = $this->request->getPost('id_logalat');
        
        // Ambil data sebelum dihapus untuk email
        $log = $this->db->table('logalat')
            ->select('logalat.*, registrasi.email, registrasi.nama_lengkap, alat.nama_alat')
            ->join('registrasi', 'registrasi.id_regis = logalat.id_regis')
            ->join('alat', 'alat.id_alat = logalat.id_alat')
            ->where('id_logalat', $id)
            ->get()
            ->getRowArray();

        if ($log) {
            // Kirim email notifikasi penolakan
            $this->emailService->sendStatusUpdateNotification(
                $log['email'], 
                $log['nama_lengkap'], 
                'decline', 
                'alat', 
                $log['nama_alat'],
                'Permintaan ditolak. Silakan hubungi admin untuk informasi lebih lanjut.'
            );
        }

        $this->db->table('logalat')->where('id_logalat', $id)->delete();
        return redirect()->back()->with('message', '❌ Permintaan ditolak dan email notifikasi telah dikirim.');
    }

    public function declineBahan()
    {
        $id = $this->request->getPost('id_logbahan');
        
        // Ambil data sebelum dihapus untuk email
        $log = $this->db->table('logbahan')
            ->select('logbahan.*, registrasi.email, registrasi.nama_lengkap, bahan.nama_bahan')
            ->join('registrasi', 'registrasi.id_regis = logbahan.id_regis')
            ->join('bahan', 'bahan.id_bahan = logbahan.id_bahan')
            ->where('id_logbahan', $id)
            ->get()
            ->getRowArray();

        if ($log) {
            // Kirim email notifikasi penolakan
            $this->emailService->sendStatusUpdateNotification(
                $log['email'], 
                $log['nama_lengkap'], 
                'decline', 
                'bahan', 
                $log['nama_bahan'],
                'Permintaan ditolak. Silakan hubungi admin untuk informasi lebih lanjut.'
            );
        }

        $this->db->table('logbahan')->where('id_logbahan', $id)->delete();
        return redirect()->back()->with('message', '❌ Permintaan ditolak dan email notifikasi telah dikirim.');
    }

    public function returnAlat()
    {
        $id = $this->request->getPost('id_logalat');
        $this->db->table('logalat')->where('id_logalat', $id)->update(['status' => 'return approve']);
        return redirect()->back()->with('message', 'Peminjaman alat telah dikonfirmasi kembali.');
    }
}
