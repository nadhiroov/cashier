<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_brand;
use App\Models\M_product;

class Products extends BaseController
{
    protected $model;
    protected $brand;
    public function __construct()
    {
        $this->model = new M_product();
        $this->brand = new M_brand();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_warehouse' => 'active', 'submenu_product' => 'active']);
        $this->data['menu'] = 'Products';
    }

    public function index()
    {
        return view('product/index', $this->data);
    }

    function add()
    {
        $data = $this->brand->findAll();
        return view('product/add', ['brand' => $data]);
    }

    function edit($id)
    {
        $data = $this->model->find($id);
        $brand = $this->brand->findAll();
        return view('product/edit', ['content' => $data, 'brand' => $brand]);
    }

    function editPrice($id)
    {
        $data = $this->model->find($id);
        if ($data['price_history'] == null) {
            $price = $data['price'];
        }else{

        }
        return view('product/edit', ['content' => $data]);
    }

    function detail($id) {
        $this->data['content'] = $this-> model->select('product.*, category, brand')->join('brand B', 'B.id = product.brand_id')->join('category C', 'C.id = B.category_id')->find($id);
        $this->data['submenu'] = 'Detail';
        return view('product/detail', $this->data);

    }

    function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('product.*, category, brand')->join('brand B', 'B.id = product.brand_id')->join('category C', 'C.id = B.category_id')->limit($dtTable['length'], $dtTable['start'])->orderBy('name', 'asc');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('product.name', $dtTable['search']['value']);
            $data = $this->model->orLike('product.stock', $dtTable['search']['value']);
            $data = $this->model->orLike('product.price', $dtTable['search']['value']);
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
        try {
            $this->model->save($form);
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

    public function delete($id = null)
    {
        try {
            $this->model->delete($id);
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data deleted!'
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
