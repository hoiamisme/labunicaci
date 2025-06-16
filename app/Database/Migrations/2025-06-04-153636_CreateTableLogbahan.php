<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableLogbahan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_logbahan' => [
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
            'id_bahan' => [
                'type'       => 'INT',
                'constraint' => 5,
                'unsigned'   => true,
                'null'       => true, // Boleh null jika belum ada data
            ],
            'jumlah_pinjam_bahan' => [
                'type'       => 'FLOAT',
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
            'tanggal' => [
                'type'       => 'DATETIME',
                'null'       => true, // Boleh null jika belum ada data
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['setuju', 'tidak setuju'],
                'default'    => 'tidak setuju',
                'null'       => true, // Boleh null jika belum ada data
            ],
        ]);
        $this->forge->addKey('id_logbahan', true);
        $this->forge->addForeignKey('id_regis', 'registrasi', 'id_regis', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_bahan', 'bahan', 'id_bahan', 'CASCADE', 'CASCADE');
        $this->forge->createTable('logbahan');
    }

    public function down()
    {
        $this->forge->dropTable('logbahan', true);
    }
}