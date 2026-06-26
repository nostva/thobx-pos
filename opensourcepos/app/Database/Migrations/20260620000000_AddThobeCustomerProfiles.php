<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddThobeCustomerProfiles extends Migration
{
    public function up(): void
    {
        error_log('Migrating: Adding Thobe Customer Profile Tables');
        $forge = Database::forge();

        // 1. ospos_thobe_customer_profiles
        $forge->addField([
            'profile_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'customer_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
            'updated_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $forge->addKey('profile_id', true);
        $forge->addForeignKey('customer_id', 'people', 'person_id', 'CASCADE', 'CASCADE');
        $forge->createTable('thobe_customer_profiles', true);
        
        $this->db->query("ALTER TABLE `" . $this->db->prefixTable('thobe_customer_profiles') . "` MODIFY `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `" . $this->db->prefixTable('thobe_customer_profiles') . "` MODIFY `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");

        // 2. ospos_thobe_customer_profile_values
        $forge->addField([
            'profile_value_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'profile_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'measurement_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'value' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $forge->addKey('profile_value_id', true);
        $forge->addForeignKey('profile_id', 'thobe_customer_profiles', 'profile_id', 'CASCADE', 'CASCADE');
        $forge->addForeignKey('measurement_id', 'thobe_measurement_definitions', 'measurement_id', 'CASCADE', 'CASCADE');
        $forge->createTable('thobe_customer_profile_values', true);
    }

    public function down(): void
    {
        $forge = Database::forge();
        $forge->dropTable('thobe_customer_profile_values', true);
        $forge->dropTable('thobe_customer_profiles', true);
    }
}
