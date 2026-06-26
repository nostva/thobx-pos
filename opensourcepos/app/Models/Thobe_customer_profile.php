<?php

namespace App\Models;

use CodeIgniter\Model;

class Thobe_customer_profile extends Model
{
    protected $table = 'thobe_customer_profiles';
    protected $primaryKey = 'profile_id';
    protected $allowedFields = ['customer_id', 'name', 'notes'];
    protected $returnType = 'array';

    public function get_profiles(int $customer_id): array
    {
        $profiles = $this->where('customer_id', $customer_id)->orderBy('profile_id', 'ASC')->findAll();
        foreach ($profiles as &$profile) {
            $profile['values'] = $this->get_profile_values($profile['profile_id']);
        }
        return $profiles;
    }

    public function get_profile_values(int $profile_id): array
    {
        $rows = $this->db->table('thobe_customer_profile_values')
            ->where('profile_id', $profile_id)
            ->get()
            ->getResultArray();

        $values = [];
        foreach ($rows as $row) {
            $values[$row['measurement_id']] = $row['value'];
        }
        return $values;
    }

    public function save_profile(
        int $customer_id,
        string $name,
        ?string $notes,
        array $measurements,
        ?int $profile_id = null
    ): array {
        $this->db->transStart();

        $profile_data = [
            'customer_id' => $customer_id,
            'name' => $name,
            'notes' => $notes,
        ];

        if ($profile_id) {
            $this->update($profile_id, $profile_data);

            $this->db->table('thobe_customer_profile_values')
                ->where('profile_id', $profile_id)
                ->delete();
        } else {
            $this->insert($profile_data);
            $profile_id = (int) $this->getInsertID();
        }

        if (!empty($measurements)) {
            $val_data = [];

            foreach ($measurements as $measurement_id => $value) {
                if ($value === null || $value === '') {
                    continue;
                }

                $val_data[] = [
                    'profile_id' => $profile_id,
                    'measurement_id' => $measurement_id,
                    'value' => $value,
                ];
            }

            if (!empty($val_data)) {
                $this->db->table('thobe_customer_profile_values')
                    ->insertBatch($val_data);
            }
        }

        $this->db->transComplete();

        return [
            'success' => $this->db->transStatus(),
            'profile_id' => $profile_id,
        ];
    }

    public function delete_profile(int $profile_id): bool
    {
        return $this->delete($profile_id) !== false;
    }
}
