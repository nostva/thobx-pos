<?php

namespace App\Models;

use CodeIgniter\Model;

class Thobe_detail extends Model
{
    protected $table = 'thobe_details';
    protected $primaryKey = 'thobe_detail_id';
    protected $allowedFields = ['sale_id', 'customer_id', 'fabric_item_number', 'fabric_quantity', 'notes'];
    protected $returnType = 'array';

    public function save_thobe_detail(int $sale_id, int $customer_id, array $data): bool
    {
        $this->db->transStart();
        
        // Check if exists
        $existing = $this->where('sale_id', $sale_id)->first();
        $thobe_data = [
            'sale_id' => $sale_id,
            'customer_id' => $customer_id,
            'fabric_item_number' => $data['fabric_item_number'] ?? null,
            'fabric_quantity' => $data['fabric_quantity'] ?? null,
            'notes' => $data['notes'] ?? null,
        ];

        if ($existing) {
            $this->update($existing['thobe_detail_id'], $thobe_data);
            $detail_id = $existing['thobe_detail_id'];
            // Clear existing values
            $this->db->table('thobe_detail_values')->where('thobe_detail_id', $detail_id)->delete();
        } else {
            $this->insert($thobe_data);
            $detail_id = $this->getInsertID();
        }

        // Insert measurements
        if (!empty($data['measurements'])) {
            $val_data = [];
            foreach ($data['measurements'] as $field_id => $value) {
                $val_data[] = [
                    'thobe_detail_id' => $detail_id,
                    'field_type' => 'measurement',
                    'field_id' => $field_id,
                    'value' => $value
                ];
            }
            if(!empty($val_data)) $this->db->table('thobe_detail_values')->insertBatch($val_data);
        }

        // Insert options
        if (!empty($data['options'])) {
            $val_data = [];
            foreach ($data['options'] as $group_id => $value_id) {
                $val_data[] = [
                    'thobe_detail_id' => $detail_id,
                    'field_type' => 'option',
                    'field_id' => $group_id,
                    'value' => $value_id
                ];
            }
            if(!empty($val_data)) $this->db->table('thobe_detail_values')->insertBatch($val_data);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function get_thobe_detail(int $sale_id): ?array
    {
        $detail = $this->where('sale_id', $sale_id)->first();
        if (!$detail) return null;

        $values = $this->db->table('thobe_detail_values')->where('thobe_detail_id', $detail['thobe_detail_id'])->get()->getResultArray();
        $detail['measurements'] = [];
        $detail['options'] = [];
        
        foreach ($values as $v) {
            if ($v['field_type'] == 'measurement') $detail['measurements'][$v['field_id']] = $v['value'];
            if ($v['field_type'] == 'option') $detail['options'][$v['field_id']] = $v['value'];
        }
        return $detail;
    }
    
    public function delete_by_sale(int $sale_id): bool
    {
        return $this->where('sale_id', $sale_id)->delete();
    }
}
