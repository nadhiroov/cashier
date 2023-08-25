<?php

namespace App\Controllers;

use App\Models\M_brand;
use App\Models\M_category;
use App\Models\M_product;
use App\Models\M_transaction;

class Home extends BaseController
{
    protected $category;
    protected $brand;
    protected $product;
    protected $trans;
    public function __construct()
    {
        $this->category = new M_category();
        $this->brand    = new M_brand();
        $this->product  = new M_product();
        $this->trans    = new M_transaction();

        $this->view     = \Config\Services::renderer();
        $this->view->setData(['menu_dashboard' => 'active']);
        $this->data['menu'] = 'Dashboard';
    }

    public function index()
    {
        return view('welcome_message');
    }
    
    public function dashboard() {
        $this->data['category'] = $this->category->countAllResults();
        $this->data['brand']    = $this->brand->countAllResults();
        $this->data['product']  = $this->product->countAllResults();
        $this->data['transaction'] = $this->trans->where('DATE(created_at) ', date('Y-m-d'))->countAllResults();
        $this->data['income']   = $this->trans->select('sum(grand_total) as total')->where('DATE(created_at)', date('Y-m-d'))->first();
        $this->data['items']   = $this->trans->select('sum(JSON_LENGTH(items)) as items')->where('DATE(created_at)', date('Y-m-d'))->first();
        
        return view('dashboard', $this->data);
    }
}
