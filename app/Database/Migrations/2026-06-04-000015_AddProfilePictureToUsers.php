<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddProfilePictureToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'profile_picture' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'default'    => null,
                'after'      => 'phone',
            ],
        ];
        
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'profile_picture');
    }
}
