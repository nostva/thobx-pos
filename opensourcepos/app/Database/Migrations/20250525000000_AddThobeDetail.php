<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddThobeDetail extends Migration
{
    public function up(): void
    {
        error_log('Migrating: Adding Thobe Detail Tables');
        $forge = Database::forge();

        // 1. ospos_thobe_measurement_definitions
        $forge->addField([
            'measurement_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'label' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'value_type' => ['type' => 'ENUM', 'constraint' => ['string', 'number', 'boolean'], 'default' => 'string'],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'deleted' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $forge->addKey('measurement_id', true);
        $forge->createTable('thobe_measurement_definitions', true);

        // 2. ospos_thobe_option_groups
        $forge->addField([
            'option_group_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'deleted' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $forge->addKey('option_group_id', true);
        $forge->createTable('thobe_option_groups', true);

        // 3. ospos_thobe_option_values
        $forge->addField([
            'option_value_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'option_group_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => false],
            'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'sort_order' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'deleted' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $forge->addKey('option_value_id', true);
        $forge->addForeignKey('option_group_id', 'thobe_option_groups', 'option_group_id', 'CASCADE', 'CASCADE');
        $forge->createTable('thobe_option_values', true);

        // 4. ospos_thobe_details
        $forge->addField([
            'thobe_detail_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'sale_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => false],
            'customer_id' => ['type' => 'INT', 'constraint' => 10, 'unsigned' => false],
            'fabric_item_number' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'fabric_quantity' => ['type' => 'DECIMAL', 'constraint' => '15,3', 'null' => true],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'TIMESTAMP', 'null' => true],
        ]);
        $forge->addKey('thobe_detail_id', true);
        $forge->addForeignKey('sale_id', 'sales', 'sale_id', 'CASCADE', 'CASCADE');
        $forge->addForeignKey('customer_id', 'people', 'person_id', 'CASCADE', 'CASCADE');
        $forge->createTable('thobe_details', true);
        $this->db->query("ALTER TABLE `" . $this->db->prefixTable('thobe_details') . "` MODIFY `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");


        // 5. ospos_thobe_detail_values
        $forge->addField([
            'detail_value_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false, 'auto_increment' => true],
            'thobe_detail_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'field_type' => ['type' => 'ENUM', 'constraint' => ['measurement', 'option'], 'null' => false],
            'field_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => false],
            'value' => ['type' => 'VARCHAR', 'constraint' => 500, 'null' => true],
        ]);
        $forge->addKey('detail_value_id', true);
        $forge->addForeignKey('thobe_detail_id', 'thobe_details', 'thobe_detail_id', 'CASCADE', 'CASCADE');
        $forge->createTable('thobe_detail_values', true);

        // Insert default app config settings
        $this->db->table('app_config')->insertBatch([
            ['key' => 'thobe_detail_enable', 'value' => '0'],
            ['key' => 'thobe_detail_print', 'value' => '1']
        ]);
    }

    public function down(): void
    {
        $forge = Database::forge();
        $forge->dropTable('thobe_detail_values', true);
        $forge->dropTable('thobe_details', true);
        $forge->dropTable('thobe_option_values', true);
        $forge->dropTable('thobe_option_groups', true);
        $forge->dropTable('thobe_measurement_definitions', true);

        $this->db->table('app_config')->whereIn('key', ['thobe_detail_enable', 'thobe_detail_print'])->delete();
    }
}
