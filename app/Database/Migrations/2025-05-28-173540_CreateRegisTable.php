<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegisTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_regis' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // Maksimal 50 karakter
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // Maksimal 50 karakter
                'unique'     => true, // Email harus unik
            ],
            'cohort' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],
            'prodi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => 15, // Maksimal 15 karakter
            ],
        ]);
        $this->forge->addKey('id_regis', true); // Primary key
        $this->forge->createTable('registrasi');
    }

    public function down()
    {
        $this->forge->dropTable('registrasi', true); // Drops the table if it exists
    }
}