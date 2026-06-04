<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStaffServicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'staff_id' => [
                'type'       => 'BIGINT',
                'constraint' => 20,
                'unsigned'   => true,
            ],
            'service_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
        ]);
        $this->forge->addKey(['staff_id', 'service_id'], true);
        $this->forge->addForeignKey('staff_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('service_id', 'services', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('staff_services');
    }

    public function down()
    {
        $this->forge->dropTable('staff_services');
    }
}
