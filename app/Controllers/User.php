<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_user;

class User extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_user();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_user' => 'active']);
        $this->data['menu'] = 'User management';
    }

    function index()
    {
        return view('user/index', $this->data);
    }

    function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->limit($dtTable['length'], $dtTable['start'])->orderBy('fullname', 'asc');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('fullname', $dtTable['search']['value']);
            $data = $this->model->orLike('username', $dtTable['search']['value']);
            $data = $this->model->orLike('email', $dtTable['search']['value']);
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
        $image = $this->request->getFile('userimage');
        $validateImage = $this->validate([
            'file' => [
                'mime_in[file, image/png, image/jpg,image/jpeg, image/gif]',
                'max_size[file, 4096]',
            ],
        ]);
        
        if (!$this->model->validate($form)) {
            $errors = $this->model->errors();
            $errorMessages = implode("<br>", $errors);
            $return = [
                'status' => 'error',
                'title'  => 'Error',
                'message' => $errorMessages
            ];
            echo json_encode($return);
            return false;
        }
        try {
            if ($image->isValid()) {
                # code...
            }
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
}
