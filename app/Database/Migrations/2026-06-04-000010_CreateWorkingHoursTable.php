<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateWorkingHoursTable extends Migration
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
            'day_of_week' => [
                'type'    => 'SMALLINT',
                'comment' => '0=Sunday, 1=Monday, etc.',
            ],
            'start_time' => [
                'type' => 'TIME',
            ],
            'end_time' => [
                'type' => 'TIME',
            ],
            'is_active' => [
                'type'    => 'SMALLINT',
                'default' => 1,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('staff_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('working_hours');
    }

    public function down()
    {
        $this->forge->dropTable('working_hours');
    }
}
