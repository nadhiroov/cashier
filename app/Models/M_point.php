<?php

namespace App\Models;

use CodeIgniter\Model;

class M_point extends Model
{
    protected $table            = 'point';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nominal', 'point'];

    // Dates
    protected $useTimestamps = false;
}
