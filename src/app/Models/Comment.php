<?php

namespace App\Models;

use CodeIgniter\Model;

class Comment extends Model
{
    protected $table      = 'comments';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'text', 'date'];
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
    protected $allowCallbacks = true;
    protected $afterFind      = ['formatDate'];

    protected function formatDate($data)
    {
        for ($i = 0; $i < count($data['data']); $i++) {
            $data['data'][$i]['date'] = date('H:i d.m.Y', strtotime($data['data'][$i]['date']));
        }
        return $data;
    }

    public function getTotalComments()
    {
        return $this->db->query("SELECT COUNT(id) as total FROM comments")->getRow()->total;
    }
}
