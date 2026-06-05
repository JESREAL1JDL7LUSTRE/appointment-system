<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointmentLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'auto_increment' => true,
            ],
            'appointment_id' => [
                'type' => 'BIGINT',
            ],
            'changed_by' => [
                'type' => 'BIGINT',
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'previous_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'new_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('appointment_id', 'appointments', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('changed_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('appointment_logs');
    }

    public function down()
    {
        $this->forge->dropTable('appointment_logs');
    }
}
