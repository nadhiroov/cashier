<?php

namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'fullname', 'email', 'img', 'is_admin', 'theme'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

     // Validation
    protected $validationRules      = [
        'username'     => 'required|alpha_dash|min_length[5]|is_unique[users.username]',
        'email'        => 'required|valid_email|is_unique[users.email]',
        'password'     => 'required|min_length[8]',
    ];
    protected $validationMessages   = [
        'username' => [
            'required' => 'Username is required.',
            'alpha_dash' => 'Username can only contain letters, numbers, dashes, and underscores.',
            'min_length' => 'Username must be at least 5 characters long.',
            'is_unique' => 'Username is already taken.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please provide a valid email address.',
            'is_unique' => 'Email is already taken.'
        ],
        'password' => [
            'required' => 'Password is required.',
            'min_length' => 'Password must be at least 8 characters long.'
        ],
        'img' => [
            'mime_in[file, image/png, image/jpg,image/jpeg, image/gif]',
            'max_size[file, 4096]',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
