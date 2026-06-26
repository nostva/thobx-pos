<?php

namespace App\Models;

use CodeIgniter\Model;

class Thobe_option extends Model
{
    protected $table = 'thobe_option_groups';
    protected $primaryKey = 'option_group_id';
    protected $allowedFields = ['name', 'sort_order', 'deleted'];
    protected $returnType = 'array';

    public function get_all_groups()
    {
        return $this->where('deleted', 0)->orderBy('sort_order', 'ASC')->findAll();
    }

    public function get_group_info(int $group_id)
    {
        if ($group_id === -1) {
            return (object) ['option_group_id' => -1, 'name' => '', 'sort_order' => 0];
        }
        $group = $this->find($group_id);
        return $group ? (object) $group : (object) ['option_group_id' => -1, 'name' => '', 'sort_order' => 0];
    }

    public function get_values_for_group(int $group_id)
    {
        return $this->db->table('thobe_option_values')
            ->where('option_group_id', $group_id)
            ->where('deleted', 0)
            ->orderBy('sort_order', 'ASC')
            ->get()->getResultArray();
    }

    public function get_all_groups_with_values()
    {
        $groups = $this->get_all_groups();
        foreach ($groups as &$group) {
            $group['values'] = $this->get_values_for_group($group['option_group_id']);
        }
        return $groups;
    }

    public function save_group(array $data, int $id = -1): int|bool
    {
        if ($id === -1) {
            $this->insert($data);
            return $this->getInsertID();
        }
        return $this->update($id, $data) ? $id : false;
    }

    public function save_value(array $data, int $id = -1): bool
    {
        $builder = $this->db->table('thobe_option_values');
        if ($id === -1) {
            return $builder->insert($data);
        }
        return $builder->where('option_value_id', $id)->update($data);
    }

    public function delete_group(int $id): bool
    {
        return $this->update($id, ['deleted' => 1]);
    }

    public function delete_value(int $id): bool
    {
        return $this->db->table('thobe_option_values')->where('option_value_id', $id)->update(['deleted' => 1]);
    }
}
