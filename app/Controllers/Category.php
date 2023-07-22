<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_category;

class Category extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_category();
        // $this->view->setData(['menu_warehouse' => 'active']);
        // view()->setData(['menu_warehouse' => 'active']);
        $this->data['menu'] = 'category';
    }
    public function index()
    {
        return view('category/index', $this->data);
    }

    function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->limit($dtTable['length'], $dtTable['start']);
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
