<?php

namespace App\Controllers;

use App\Models\M_brand;
use App\Models\M_category;
use App\Models\M_product;

class Home extends BaseController
{
    protected $category;
    protected $brand;
    protected $product;
    public function __construct()
    {
        $this->category = new M_category();
        $this->brand = new M_brand();
        $this->product = new M_product();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_dashboard' => 'active']);
        $this->data['menu'] = 'Dashboard';
    }

    public function index()
    {
        return view('welcome_message');
    }
    
    function dashboard() {
        $this->data['category']= $this->category->countAllResults();
        $this->data['brand'] = $this->brand->countAllResults();
        $this->data['product'] = $this->product->countAllResults();
        return view('dashboard', $this->data);
    }
}
