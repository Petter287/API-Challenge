<?php

namespace App\Models;

use CodeIgniter\Model;

abstract class Base_model extends Model
{
    protected $useTimestamps = true;
    protected $createdField  = 'createdAt';
    protected $updatedField  = 'updatedAt';

    protected $useSoftDeletes = true;
    protected $deletedField = 'deletedAt';

    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];
    protected $beforeDelete = ['setDeletedBy'];

    public function __construct()
    {
        parent::__construct();

        $auditFields = [
            'createdBy',
            'updatedBy',
            'deletedBy',
        ];

        $this->allowedFields = array_values(array_unique(array_merge(
            $this->allowedFields,
            $auditFields
        )));
    }

    protected function setCreatedBy(array $data): array
    {
        $data['data']['createdBy'] = $data['data']['createdBy'] ?? 'system';

        return $data;
    }

    protected function setUpdatedBy(array $data): array
    {
        $data['data']['updatedBy'] = $data['data']['updatedBy'] ?? 'system';

        return $data;
    }

    protected function setDeletedBy(array $data): array
    {
        $data['data']['deletedBy'] = $data['data']['deletedBy'] ?? 'system';

        return $data;
    }

    public function deleteWithUser(int $id, string $user = 'system'): bool
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->where('deletedBy', null)
            ->where('deletedAt', null)
            ->update([
                'deletedBy' => $user,
                'deletedAt' => date('Y-m-d H:i:s'),
                'updatedBy' => $user,
                'updatedAt' => date('Y-m-d H:i:s'),
            ]);
    }
}
