<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProfilesTable extends Migration
{
    public function up()
    {
       $this->forge->addField([
    'id' => [
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
        'auto_increment' => true,
    ],
    'user_id' => [
        'type'           => 'INT',
        'constraint'     => 11,
        'unsigned'       => true,
    ],
    'nama_lengkap' => [
        'type'       => 'VARCHAR',
        'constraint' => '100',
    ],
    'nomor_induk' => [ // Bisa untuk NISN atau NUPTK
        'type'       => 'VARCHAR',
        'constraint' => '30',
        'null'       => true,
    ],
    'alamat' => [
        'type' => 'TEXT',
        'null' => true,
    ],
    'created_at' => ['type' => 'DATETIME', 'null' => true],
    'updated_at' => ['type' => 'DATETIME', 'null' => true],
]);

$this->forge->addKey('id', true);
$this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
$this->forge->createTable('profiles');
    }

    public function down()
    {
        //
    }
}
