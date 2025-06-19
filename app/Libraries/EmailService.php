<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;
use App\Models\LoginModel;

class EmailService
{
    protected $email;
    protected $loginModel;

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->loginModel = new LoginModel();
    }

    /**
     * Kirim notifikasi ke admin ketika ada permintaan baru
     */
    public function sendPermintaanNotification($userId, $itemsData, $tujuan, $pesan = '')
    {
        try {
            log_message('info', '=== STARTING EMAIL NOTIFICATION ===');
            log_message('info', 'User ID: ' . $userId);
            log_message('info', 'Items count: ' . count($itemsData));
            
            // Ambil data user yang submit
            $user = $this->loginModel->find($userId);
            if (!$user) {
                log_message('error', 'User not found: ' . $userId);
                return false;
            }
            log_message('info', 'User found: ' . $user['nama_lengkap']);

            // Ambil semua admin email
            $admins = $this->loginModel->where('role', 'admin')->findAll();
            if (empty($admins)) {
                log_message('error', 'No admin found');
                return false;
            }

            $adminEmails = array_column($admins, 'email');
            log_message('info', 'Admin emails: ' . implode(', ', $adminEmails));

            // Generate email content
            $subject = '🔔 PERMINTAAN PEMAKAIAN BARU - Labunica';
            $message = $this->generatePermintaanEmailContent($user, $itemsData, $tujuan, $pesan);
            
            if (empty($message)) {
                log_message('error', 'Email message is empty');
                return false;
            }

            // Setup email
            $this->email->clear();
            $this->email->setFrom('faliefwaluyo@gmail.com', 'Labunica System');
            $this->email->setTo($adminEmails);
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            log_message('info', 'Attempting to send email...');

            // Kirim email
            if ($this->email->send()) {
                log_message('info', '✅ Email sent successfully');
                return true;
            } else {
                $debugger = $this->email->printDebugger(['headers']);
                log_message('error', '❌ Email failed: ' . $debugger);
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', 'Email exception: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate email content yang sederhana dan mudah dibaca
     */
    private function generatePermintaanEmailContent($user, $itemsData, $tujuan, $pesan)
    {
        try {
            $itemList = '';
            foreach ($itemsData as $item) {
                $typeIcon = ($item['type'] === 'alat') ? '🔧' : '🧪';
                $unit = ($item['type'] === 'alat') ? 'unit' : ($item['unit'] ?? '');
                
                $itemList .= "
                    <tr>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$typeIcon}</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>" . ucfirst($item['type']) . "</td>
                        <td style='padding: 8px; border: 1px solid #ddd; font-weight: bold;'>{$item['name']}</td>
                        <td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>{$item['quantity']} {$unit}</td>
                        <td style='padding: 8px; border: 1px solid #ddd;'>{$item['location']}</td>
                    </tr>";
            }

            $currentTime = date('d/m/Y H:i:s');
            $approvalUrl = base_url('pemberitahuan');

            return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <title>Permintaan Pemakaian Baru</title>
            </head>
            <body style='font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5;'>
                <div style='max-width: 700px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                    
                    <!-- Header -->
                    <div style='background: linear-gradient(135deg, #dc3545, #c82333); color: white; padding: 30px; text-align: center;'>
                        <h1 style='margin: 0; font-size: 24px;'>🚨 PERMINTAAN PEMAKAIAN BARU</h1>
                        <p style='margin: 10px 0 0 0; font-size: 16px;'>Labunica System</p>
                    </div>
                    
                    <!-- Content -->
                    <div style='padding: 30px;'>
                        
                        <!-- Alert -->
                        <div style='background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 15px; margin-bottom: 25px; text-align: center;'>
                            <strong style='color: #856404; font-size: 16px;'>⚠️ PERLU PERSETUJUAN SEGERA!</strong><br>
                            <small style='color: #856404;'>Permintaan baru menunggu persetujuan Anda</small>
                        </div>

                        <!-- Info Pemohon -->
                        <div style='background: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;'>
                            <h3 style='margin: 0 0 15px 0; color: #0056b3;'>👤 Informasi Pemohon</h3>
                            <p style='margin: 5px 0;'><strong>Nama:</strong> {$user['nama_lengkap']}</p>
                            <p style='margin: 5px 0;'><strong>Email:</strong> {$user['email']}</p>
                            <p style='margin: 5px 0;'><strong>Cohort:</strong> {$user['cohort']}</p>
                            <p style='margin: 5px 0;'><strong>Program Studi:</strong> {$user['prodi']}</p>
                            <p style='margin: 5px 0;'><strong>Waktu Pengajuan:</strong> {$currentTime}</p>
                        </div>

                        <!-- Detail Permintaan -->
                        <h3 style='color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px;'>📦 Detail Permintaan (" . count($itemsData) . " Item)</h3>
                        <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                            <thead>
                                <tr style='background: #007bff; color: white;'>
                                    <th style='padding: 12px 8px; text-align: center;'>Icon</th>
                                    <th style='padding: 12px 8px;'>Jenis</th>
                                    <th style='padding: 12px 8px;'>Nama Item</th>
                                    <th style='padding: 12px 8px; text-align: center;'>Jumlah</th>
                                    <th style='padding: 12px 8px; text-align: center;'>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {$itemList}
                            </tbody>
                        </table>

                        <!-- Detail Penggunaan -->
                        <div style='background: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0; border-radius: 0 6px 6px 0;'>
                            <h3 style='margin: 0 0 15px 0; color: #0056b3;'>🎯 Detail Penggunaan</h3>
                            <p style='margin: 5px 0;'><strong>Tujuan Pemakaian:</strong> {$tujuan}</p>
                            " . (!empty($pesan) ? "<p style='margin: 5px 0;'><strong>Pesan Tambahan:</strong> {$pesan}</p>" : "") . "
                        </div>

                        <!-- Action Button -->
                        <div style='background: #f8f9fa; border-radius: 6px; padding: 25px; text-align: center; margin: 25px 0;'>
                            <h3 style='color: #dc3545; margin: 0 0 15px 0;'>🔴 TINDAKAN DIPERLUKAN</h3>
                            <p style='margin: 0 0 20px 0;'>Silakan buka sistem untuk mereview dan menyetujui/menolak permintaan ini:</p>
                            <a href='{$approvalUrl}' style='display: inline-block; background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 6px; font-weight: bold;'>
                                🔍 BUKA SISTEM APPROVAL
                            </a>
                        </div>

                    </div>
                    
                    <!-- Footer -->
                    <div style='background: #6c757d; color: white; padding: 20px; text-align: center;'>
                        <p style='margin: 0; font-weight: bold;'>© 2024 Labunica</p>
                        <p style='margin: 5px 0 0 0;'>Sistem Manajemen Laboratorium Terintegrasi</p>
                        <small>📧 Email ini dikirim secara otomatis, mohon jangan membalas langsung.</small>
                    </div>
                    
                </div>
            </body>
            </html>";
            
        } catch (\Exception $e) {
            log_message('error', 'Error generating email content: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Kirim notifikasi ke user ketika permintaan disetujui/ditolak
     */
    public function sendStatusUpdateNotification($userEmail, $userName, $status, $itemType, $itemName, $pesan = '')
    {
        try {
            $statusText = $status === 'approve' ? 'DISETUJUI' : 'DITOLAK';
            $statusIcon = $status === 'approve' ? '✅' : '❌';
            
            $subject = "🔔 Permintaan {$statusText} - Lab UNICACI";
            $message = $this->generateStatusUpdateEmailContent($userName, $status, $itemType, $itemName, $pesan);

            $this->email->clear();
            $this->email->setFrom('faliefwaluyo@gmail.com', 'Labunica System');
            $this->email->setTo($userEmail);
            $this->email->setSubject($subject);
            $this->email->setMessage($message);

            if ($this->email->send()) {
                log_message('info', 'Status update email sent to: ' . $userEmail);
                return true;
            } else {
                log_message('error', 'Failed to send status update email');
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', 'Status update email error: ' . $e->getMessage());
            return false;
        }
    }

    private function generateStatusUpdateEmailContent($userName, $status, $itemType, $itemName, $pesan)
    {
        $statusText = $status === 'approve' ? 'DISETUJUI' : 'DITOLAK';
        $statusIcon = $status === 'approve' ? '✅' : '❌';
        $statusColor = $status === 'approve' ? '#28a745' : '#dc3545';
        $itemTypeText = $itemType === 'alat' ? 'Alat' : 'Bahan';
        $itemIcon = $itemType === 'alat' ? '🔧' : '🧪';

        return "
        <!DOCTYPE html>
        <html>
        <head><meta charset='UTF-8'></head>
        <body style='font-family: Arial, sans-serif; margin: 0; padding: 20px; background: #f5f5f5;'>
            <div style='max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                
                <div style='background: {$statusColor}; color: white; padding: 30px; text-align: center;'>
                    <h1 style='margin: 0;'>{$statusIcon} PERMINTAAN {$statusText}</h1>
                    <p style='margin: 10px 0 0 0;'>Labunica System</p>
                </div>
                
                <div style='padding: 30px;'>
                    <p>Halo <strong>{$userName}</strong>,</p>
                    
                    <div style='background: {$statusColor}; color: white; padding: 20px; text-align: center; border-radius: 6px; margin: 20px 0;'>
                        <h2 style='margin: 0 0 10px 0;'>{$statusIcon} Permintaan Anda Telah {$statusText}</h2>
                        <p style='margin: 0; font-size: 16px;'>{$itemIcon} {$itemTypeText}: <strong>{$itemName}</strong></p>
                    </div>

                    <div style='background: #e7f3ff; border-left: 4px solid #007bff; padding: 20px; margin: 20px 0;'>
                        <p style='margin: 5px 0;'><strong>⏰ Waktu Update:</strong> " . date('d/m/Y H:i:s') . "</p>
                        " . (!empty($pesan) ? "<p style='margin: 5px 0;'><strong>💬 Pesan dari Admin:</strong> {$pesan}</p>" : "") . "
                    </div>

                    " . ($status === 'approve' 
                        ? "<div style='background: #d4edda; border: 1px solid #c3e6cb; border-radius: 6px; padding: 20px; margin: 20px 0;'>
                            <h3 style='color: #155724; margin: 0 0 10px 0;'>🎉 Selamat!</h3>
                            <p style='color: #155724; margin: 0;'>Permintaan Anda telah disetujui. Silakan datang ke laboratorium untuk mengambil {$itemTypeText} yang Anda perlukan.</p>
                           </div>"
                        : "<div style='background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 20px; margin: 20px 0;'>
                            <h3 style='color: #721c24; margin: 0 0 10px 0;'>😔 Maaf</h3>
                            <p style='color: #721c24; margin: 0;'>Permintaan Anda ditolak. Silakan hubungi admin untuk informasi lebih lanjut atau ajukan permintaan baru.</p>
                           </div>"
                    ) . "

                    <div style='text-align: center; margin: 25px 0;'>
                        <a href='" . base_url('logbook') . "' style='display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px;'>📚 Lihat Logbook</a>
                        <a href='" . base_url('pemakaian') . "' style='display: inline-block; background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 6px; margin: 5px;'>📦 Buat Permintaan Baru</a>
                    </div>
                </div>
                
                <div style='background: #6c757d; color: white; padding: 20px; text-align: center;'>
                    <p style='margin: 0;'><strong>© 2024 Labunica</strong></p>
                    <small>📧 Email ini dikirim secara otomatis, mohon jangan membalas langsung.</small>
                </div>
            </div>
        </body>
        </html>";
    }
}