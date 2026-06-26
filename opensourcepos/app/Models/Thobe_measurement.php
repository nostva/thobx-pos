<?php

namespace App\Models;

use CodeIgniter\Model;

class Thobe_measurement extends Model
{
    protected $table = 'thobe_measurement_definitions';
    protected $primaryKey = 'measurement_id';
    protected $allowedFields = ['label', 'value_type', 'sort_order', 'deleted'];
    protected $returnType = 'array';

    public function get_all()
    {
        return $this->where('deleted', 0)->orderBy('sort_order', 'ASC')->findAll();
    }

    public function get_info(int $measurement_id)
    {
        if ($measurement_id === -1) {
            return (object) ['measurement_id' => -1, 'label' => '', 'value_type' => 'string', 'sort_order' => 0];
        }
        $measurement = $this->find($measurement_id);
        return $measurement ? (object) $measurement : (object) ['measurement_id' => -1, 'label' => '', 'value_type' => 'string', 'sort_order' => 0];
    }
    
    public function save_value(array $data, int $id = -1): bool
    {
        if ($id === -1) {
            return $this->insert($data) !== false;
        }
        return $this->update($id, $data);
    }
    
    public function delete_value(int $id): bool
    {
        return $this->update($id, ['deleted' => 1]);
    }
}
