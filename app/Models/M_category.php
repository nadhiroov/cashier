<?php

namespace App\Models;

use CodeIgniter\Model;

class M_category extends Model
{
    protected $table            = 'category';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['category'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'category'     => 'required|alpha_numeric|is_unique[category.category]'
    ];
    protected $validationMessages   = [
        'category' => [
            'is_unique' => 'This category already exist',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
