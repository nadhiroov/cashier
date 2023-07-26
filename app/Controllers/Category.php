<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_brand;
use App\Models\M_category;

class Category extends BaseController
{
    protected $model;
    protected $brand;
    public function __construct()
    {
        $this->model = new M_category();
        $this->brand = new M_brand();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_warehouse' => 'active', 'submenu_category' => 'active']);
        $this->data['menu'] = 'Category';
    }

    public function index()
    {
        return view('category/index', $this->data);
    }

    public function detail($id)
    {
        $this->data['submenu'] = 'Detail';
        $this->data['content'] = $this->model->find($id);
        return view('category/detail', $this->data);
    }

    function edit() 
    {
        $id = $this->request->getPost('id');
        $data = $this->model->find($id);
        return view('category/edit', ['content' => $data]);
    }

    function editBrand($id)
    {
        $this->data['content'] = $this->brand->find($id);
        $this->data['category'] = $this->model->findAll();
        $this->data['fromCategory'] = true;
        return view('brand/edit', $this->data);
    }

    function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->limit($dtTable['length'], $dtTable['start'])->orderBy('category', 'asc');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('category', $dtTable['search']['value']);
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
        if (!$this->model->validate($form)) {
            $return = [
                'status' => 'error',
                'title'  => 'Error',
                'message' => $this->model->validation->getError()
            ];
            echo json_encode($return);
            return false;
        }
        try {
            $this->model->save($form);
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data saved successfully'
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
