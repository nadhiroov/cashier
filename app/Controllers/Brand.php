<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_brand;
use App\Models\M_category;

class Brand extends BaseController
{
    protected $model;
    protected $category;
    public function __construct()
    {
        $this->model = new M_brand();
        $this->category = new M_category();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_warehouse' => 'active', 'submenu_brand' => 'active']);
        $this->data['menu'] = 'Brands';
    }

    public function index()
    {
        $this->data['category'] = $this->category->findAll();
        return view('brand/index', $this->data);
    }

    function getData() {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('brand.id, brand.brand, category')->join('category B', 'B.id = brand.category_id', 'left')
        ->limit($dtTable['length'], $dtTable['start'])->where('brand.deleted_at', null);
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('produk', $dtTable['search']['value']);
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
                'message'   => 'Data berhasil disimpan'
            ];
        } catch (\Throwable $th) {
            $return = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => $th->getMessage()
            ];
        }
        echo json_encode($return);
    }
}
