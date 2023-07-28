<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_transaction;

class Transaction extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_transaction();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_selling' => 'active', 'submenu_transaction' => 'active']);
        $this->data['menu'] = 'Products';
    }

    public function index()
    {
        return view('transaction/index', $this->data);
    }
}
