<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTimeOffsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'staff_id' => [
                'type' => 'BIGINT',
            ],
            'start_datetime' => [
                'type' => 'DATETIME',
            ],
            'end_datetime' => [
                'type' => 'DATETIME',
            ],
            'reason' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'pending',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('staff_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('time_offs');
    }

    public function down()
    {
        $this->forge->dropTable('time_offs');
    }
}
