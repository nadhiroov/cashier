<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_point;

class Setting extends BaseController
{
    protected $point;
    public function __construct()
    {
        $this->point = new M_point();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_setting' => 'active']);
        $this->data['menu'] = 'Settings';
        if (session()->get('is_admin') != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function index()
    {
        $this->data['point'] = $this->point->first();
        return view('setting/index', $this->data);
    }

    function processPoint()
    {
        $form = $this->request->getPost('form');
        /* if (!$this->model->validate($form)) {
            $return = [
                'status' => 'error',
                'title'  => 'Error',
                'message' => $this->model->validation->getError()
            ];
            echo json_encode($return);
            return false;
        } */
        try {
            $this->point->save($form);
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
