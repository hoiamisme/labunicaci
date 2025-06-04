<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBahanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bahan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_bahan' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlah_bahan' => [
                'type'       => 'FLOAT',
                'null'       => false,
            ],
            'satuan_bahan' => [
                'type'       => 'ENUM',
                'constraint' => ['liter', 'mililiter', 'gram', 'miligram'],
                'null'       => false,
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_bahan', true);
        $this->forge->createTable('bahan');
    }

    public function down()
    {
        $this->forge->dropTable('bahan', true);
    }
}