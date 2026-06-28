<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use Config\Database;

class AddThobeDetail extends Migration
{
    public function up(): void
    {
        error_log('Migrating: Adding QZ settings to app config');
        $this->db->table('app_config')->insertBatch([
            ['key' => 'qz_enable', 'value' => '1'],
            ['key' => 'qz_printer_name', 'value' => 'Microsoft Print to PDF']
        ]);
    }

    public function down(): void
    {
        $this->db->table('app_config')->whereIn('key', ['qz_enable', 'qz_printer_name'])->delete();
    }
}
