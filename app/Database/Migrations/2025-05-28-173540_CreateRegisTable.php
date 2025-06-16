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
                'constraint' => 50,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'unique'     => true,
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
                'constraint' => 255,
            ],
            'foto_profil' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'role' => [
                'type'       => 'VARCHAR',
                'constraint' => 5,
                'default'    => 'user',
                'comment'    => 'Hanya boleh "user" atau "admin"',
            ],
        ]);
        $this->forge->addKey('id_regis', true);
        $this->forge->createTable('registrasi');
    }

    public function down()
    {
        $this->forge->dropTable('registrasi', true);
    }
}
