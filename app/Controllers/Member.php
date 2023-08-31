<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_member;

class Member extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_member();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_member' => 'active', 'submenu_members' => 'active']);
        $this->data['menu'] = 'Members';
    }

    public function index()
    {
        return view('member/index', $this->data);
    }

    public function edit($id)
    {
        $data = $this->model->find($id);
        return view('member/edit', ['content' => $data]);
    }

    public function detail($id)
    {
        $data = $this->model->find($id);

        $add = [];
        $min = [];
        // foreach (json_decode($data['point_history']) as $key) {
        //     if ($key->type = 'add') {
        //         $add += [
        //             'date' => date('D, M Y', strtotime($key->date)),
        //             'point'=> $key->point
        //         ];
        //     }elseif ($key->type = 'min') {
        //         $min += [
        //             'date' => date('D, M Y', strtotime($key->date)),
        //             'point' => $key->point
        //         ];
        //     }
        // }
        // $this->data['add'] = $add;
        // $this->data['min'] = $min;
        $this->data['point_history'] = json_decode($data['point_history']);
        $this->data['content'] = $data;
        return view('member/detail', $this->data);
    }

    public function getData()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->limit($dtTable['length'], $dtTable['start'])->orderBy('name', 'asc');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('name', $dtTable['search']['value']);
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

    public function process()
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

    public function getAll()
    {
        $data = $this->model->select('id, name, phone, point')->findAll();
        echo json_encode($data);
    }

    public function pointSubtraction($id, $subtraction)
    {
        $member = $this->model->find($id);
        if (intval($member['point']) >= $subtraction) {
            // current point
            $data['id'] = $id;
            $data['point'] = intval($member['point']) - $subtraction;
            $history = [
                'type'  => 'min',
                'date'  => date('Y-m-d H:i:s'),
                'point' => $subtraction
            ];
            // point history
            $data['point_history'] = json_decode($member['point_history'], true);
            $data['point_history'][] = $history;
            $data['point_history'] = json_encode($data['point_history']);
            try {
                $this->model->save($data);
                return true;
            } catch (\Exception $er) {
                //throw $th;
            }
        }
    }

    public function pointAddition($id, $addition)
    {
        $member = $this->model->find($id);
        // current point
        $data['id'] = $id;
        $data['point'] = intval($member['point'] ?? 0)  + intval($addition);
        $history = [
            'type'  => 'add',
            'date'  => date('Y-m-d H:i:s'),
            'point' => $addition
        ];
        // point history
        $currentPointHistory = $member['point_history'] == null ? [] : json_decode($member['point_history'], true);
        $currentPointHistory[] = $history;
        $data['point_history'] = json_encode($currentPointHistory);
        try {
            $this->model->save($data);
            return true;
        } catch (\Exception $er) {
            //throw $th;
        }
    }
}
