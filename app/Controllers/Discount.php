<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_discount;

class Discount extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_discount();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_discount' => 'active']);
        $this->data['menu'] = 'Discount';
    }
    
    public function index()
    {
        return view('discount/index');
    }
}
