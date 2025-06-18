<?php

namespace App\Controllers;

use App\Libraries\EmailService;
use App\Models\LoginModel;

class TestEmail extends BaseController
{
    public function index()
    {
        try {
            echo "<h1>Email System Debug</h1>";
            log_message('info', '=== STARTING EMAIL TEST ===');
            
            $loginModel = new LoginModel();
            
            // Test 1: Cek user dengan ID 1
            echo "<h2>1. Checking User ID 1:</h2>";
            $user = $loginModel->find(1);
            if ($user) {
                echo "✅ User found: " . $user['nama_lengkap'] . " (" . $user['email'] . ")<br>";
                echo "Role: " . $user['role'] . "<br>";
            } else {
                echo "❌ User with ID 1 not found!<br>";
                echo "<strong>Creating test user...</strong><br>";
                
                // Insert test user
                $loginModel->insert([
                    'nama_lengkap' => 'Test User',
                    'email' => 'testuser@gmail.com',
                    'cohort' => '2024',
                    'prodi' => 'Kimia',
                    'password' => password_hash('123456', PASSWORD_DEFAULT),
                    'role' => 'mahasiswa'
                ]);
                
                $user = $loginModel->find(1);
                if ($user) {
                    echo "✅ Test user created successfully<br>";
                } else {
                    echo "❌ Failed to create test user<br>";
                    return;
                }
            }
            
            // Test 2: Cek admin emails
            echo "<h2>2. Checking Admin Emails:</h2>";
            $admins = $loginModel->where('role', 'admin')->findAll();
            if (!empty($admins)) {
                echo "✅ Admin emails found:<br>";
                foreach ($admins as $admin) {
                    echo "- " . $admin['nama_lengkap'] . " (" . $admin['email'] . ")<br>";
                }
            } else {
                echo "❌ No admin found! Creating test admin...<br>";
                
                // Insert test admin
                $loginModel->insert([
                    'nama_lengkap' => 'Admin Test',
                    'email' => 'faliefwaluyo@gmail.com',
                    'cohort' => '2024',
                    'prodi' => 'Admin',
                    'password' => password_hash('admin123', PASSWORD_DEFAULT),
                    'role' => 'admin'
                ]);
                
                echo "✅ Test admin created<br>";
            }
            
            // Test 3: Test email service
            echo "<h2>3. Testing EmailService:</h2>";
            
            $emailService = new EmailService();
            
            // Simple test data
            $testItemsData = [
                [
                    'type' => 'alat',
                    'name' => 'Test Alat',
                    'quantity' => 1,
                    'location' => 'Lab Test',
                    'id' => 999,
                    'unit' => 'unit'
                ]
            ];
            
            echo "Sending test email...<br>";
            
            $result = $emailService->sendPermintaanNotification(
                1, // user ID
                $testItemsData,
                'Test Email System',
                'Ini adalah test email'
            );
            
            if ($result) {
                echo "<p style='color: green; font-size: 18px;'>✅ Email berhasil dikirim!</p>";
            } else {
                echo "<p style='color: red; font-size: 18px;'>❌ Email gagal dikirim!</p>";
            }
            
            // Test 4: Show recent logs
            echo "<h2>4. Recent Log Entries:</h2>";
            $this->showRecentLogs();
            
        } catch (\Exception $e) {
            echo "<p style='color: red;'>Exception: " . $e->getMessage() . "</p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }
    
    private function showRecentLogs()
    {
        $logPath = WRITEPATH . 'logs/log-' . date('Y-m-d') . '.php';
        if (file_exists($logPath)) {
            $logs = file_get_contents($logPath);
            $lines = explode("\n", $logs);
            $recentLines = array_slice($lines, -30); // Last 30 lines
            
            echo "<div style='background: #f4f4f4; padding: 10px; border-radius: 5px; overflow: auto; max-height: 300px; font-family: monospace; font-size: 12px;'>";
            foreach ($recentLines as $line) {
                if (strpos($line, 'ERROR') !== false) {
                    echo "<span style='color: red;'>" . htmlspecialchars($line) . "</span><br>";
                } elseif (strpos($line, 'INFO') !== false) {
                    echo "<span style='color: blue;'>" . htmlspecialchars($line) . "</span><br>";
                } else {
                    echo htmlspecialchars($line) . "<br>";
                }
            }
            echo "</div>";
        } else {
            echo "No log file found for today.";
        }
    }
    
    public function testBasicEmail()
    {
        $email = \Config\Services::email();
        
        try {
            $email->setFrom('faliefwaluyo@gmail.com', 'Test Lab UNICACI');
            $email->setTo('faliefwaluyo@gmail.com'); // Send to yourself first
            $email->setSubject('Test Basic Email');
            $email->setMessage('<h1>Test Email</h1><p>This is a test email from Lab UNICACI system.</p>');
            
            if ($email->send()) {
                echo "✅ Basic email sent successfully!";
            } else {
                echo "❌ Basic email failed!<br>";
                echo $email->printDebugger(['headers', 'subject', 'body']);
            }
        } catch (\Exception $e) {
            echo "❌ Exception: " . $e->getMessage();
        }
    }
}
