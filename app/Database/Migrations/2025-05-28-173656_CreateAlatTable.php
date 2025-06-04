<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAlatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_alat' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_alat' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'jumlah_alat' => [
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

        $this->forge->addKey('id_alat', true);
        $this->forge->createTable('alat');
    }

    public function down()
    {
        $this->forge->dropTable('alat', true);
    }
}
