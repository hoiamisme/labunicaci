<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLogalat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_logalat' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_regis' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Boleh null jika belum ada data
            ],
            'id_alat' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Boleh null jika belum ada data
            ],
            'jumlah_pinjam_alat' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true, // Boleh null jika belum ada data
            ],
            'tujuan_pemakaian' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true, // Boleh null jika belum ada data
            ],
            'pesan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'tanggal_dipinjam' => [
                'type'       => 'DATE',
                'null'       => true, // Boleh null jika belum ada data
            ],
            'tanggal_kembali' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['sudah kembali', 'belum kembali'],
                'default'    => 'belum kembali',
                'null'       => true, // Boleh null jika belum ada data
            ],
        ]);
        $this->forge->addKey('id_logalat', true);
        $this->forge->addForeignKey('id_regis', 'registrasi', 'id_regis', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_alat', 'alat', 'id_alat', 'CASCADE', 'CASCADE');
        $this->forge->createTable('logalat');
    }

    public function down()
    {
        $this->forge->dropTable('logalat', true);
    }
}