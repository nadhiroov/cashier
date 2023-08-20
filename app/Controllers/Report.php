<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_product;

class Report extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_product();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_report' => 'active']);
        $this->data['menu'] = 'Report by product';
        if (session()->get('is_admin') != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function byProduct()
    {
        $this->view->setData(['submenu_byProduct' => 'active']);
        return view('report/byProduct', $this->data);
    }

    public function getDataByProduct(): string
    {
        $dtTable = $this->request->getVar();
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $data = $this->model->select('id, name, sum(tb.count) as total')->join('json_table(sold_history, \'$[*]\' COLUMNS ( dt VARCHAR(20) path \'$.date\', count INT path \'$.count\' )) as tb', '1 = 1')->limit($dtTable['length'], $dtTable['start'])->groupBy('id')->orderBy('total', 'desc')->where('deleted_at is null');
        if ($startDate != '') {
            // $data = $this->model->where('tb.dt >=', date('Y-m-d', strtotime($startDate)))->where('tb.dt <=', date('Y-m-d', strtotime($endDate)));
            $data = $this->model->where('tb.dt >=', $startDate)->where('tb.dt <=', $endDate);
        }
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

    public function detailByProduct($id = null)
    {
        $this->view->setData(['submenu_byProduct' => 'active']);
        return view('report/detailByProduct', $this->data);
    }

    public function detailByProductDataDaily()
    {
        $id = $this->request->getPost('id');
        return true;
    }
}
