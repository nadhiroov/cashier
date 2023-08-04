<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_discount;
use App\Models\M_product;

class Discount extends BaseController
{
    protected $model;
    protected $product;
    public function __construct()
    {
        $this->model = new M_discount();
        $this->product = new M_product();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_discount' => 'active']);
        $this->data['menu'] = 'Discount';
    }
    
    public function index()
    {
        return view('discount/index', $this->data);
    }

    public function add()
    {
        $this->data['product'] = $this->product->findAll();
        return view('discount/add', $this->data);
    }
    
    function edit($id) {
        $this->data['content'] = $this->model->select('discount.*, name, price')->join('product B', 'B.id = discount.product_id')->find($id);
        $this->data['product'] = $this->product->findAll();
        return view('discount/edit', $this->data);
    }

    function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('discount.*, name, price')->join('product B', 'B.id = discount.product_id')->limit($dtTable['length'], $dtTable['start'])->orderBy('name', 'asc')->where('deleted_at is null');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('product.name', $dtTable['search']['value']);
        }
        if (!empty($dtTable['order'][0]['column'])) {
            $data = $this->model->orderBy($dtTable['columns'][$dtTable['order'][0]['column']]['data'], $dtTable['order'][0]['dir']);
        }
        $filtered = $data->countAllResults(false);
        $datas = $data->find();
        $return = array(
            "draw" => $dtTable['draw'],
            "recordsFiltered" => $filtered,
            "recordsTotal" => $this->model->countAllResults(),
            "data" => $datas
        );
        return json_encode($return);
    }

    function process()
    {
        $form = $this->request->getPost('form');
        $date = explode('-', $form['date']);
        
        $saveData = [
            'product_id' => $form['product_id'],
            'discount'   => $form['discount'],
            'date_start' => date('Y-m-d H:i:s' ,strtotime($date[0])),
            'date_end'   => date('Y-m-d H:i:s', strtotime($date[1]))
        ];

        try {
            $this->model->save($saveData);
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data saved successfully'
            ];
        } catch (\Exception $th) {
            $return = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => $th->getMessage()
            ];
        }
        echo json_encode($return);
    }
}
