<?php

namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table            = 'user';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'fullname', 'email'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

     // Validation
    protected $validationRules      = [
        'username'     => 'required|alpha_dash|min_length[5]|is_unique[user.username]',
        'email'        => 'required|valid_email|is_unique[user.email]',
        'password'     => 'required|min_length[8]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
