<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientProfilesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'BIGINT',
            ],
            'internal_notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('user_id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('client_profiles');
    }

    public function down()
    {
        $this->forge->dropTable('client_profiles');
    }
}
