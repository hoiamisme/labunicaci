<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstrumenTable extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id_instrumen' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_instrumen' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlah_instrumen' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_instrumen', true);
        $this->forge->createTable('instrumen');
    }

    public function down()
    {
        $this->forge->dropTable('instrumen', true);
    }
}