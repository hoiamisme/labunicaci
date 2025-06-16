<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nama_lengkap' => 'admin',
            'email'        => 'admin@gmail.com',
            'cohort'       => 'default',
            'prodi'        => 'kimia',
            'password'     => password_hash('Admin123!', PASSWORD_DEFAULT),
            'role'         => 'admin',
        ];

        $this->db->table('registrasi')->insert($data);
    }
}
