<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_product;
use App\Models\M_transaction;

class Report extends BaseController
{
    protected $model;
    protected $trans;
    public function __construct()
    {
        $this->model = new M_product();
        $this->trans = new M_transaction();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_report' => 'active']);
        if (session()->get('is_admin') != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }
    
    public function byProduct()
    {
        $this->view->setData(['submenu_byProduct' => 'active']);
        $this->data['menu'] = 'Report by product';
        return view('report/byProduct', $this->data);
    }

    public function getDataByProduct(): string
    {
        $dtTable = $this->request->getVar();
        $startDate = $this->request->getVar('startDate');
        $endDate = $this->request->getVar('endDate');
        $data = $this->model->select('id, name, sum(tb.count) as total')->join("json_table(sold_history, '$[*]' COLUMNS ( dt VARCHAR(20) path '$.date', count INT path '$.count' )) as tb", '1= 1')->limit($dtTable['length'], $dtTable['start'])->groupBy('id')->orderBy('total', 'desc')->where('deleted_at is null');
        if ($startDate != '') {
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
        $this->data['content'] = $this->model->find($id);
        return view('report/detailByProduct', $this->data);
    }

    public function detailByProductDataDaily()
    {
        $id = $this->request->getPost('id');
        $monthYear = $this->request->getPost('monthYear');
        $data = $this->model->select("DATE_FORMAT(STR_TO_DATE(dt, '%d-%m-%Y'), '%d %b') as tgl, count")->join("json_table (sold_history, '$[*]' COLUMNS ( dt VARCHAR ( 20 ) path '$.date', count INT path '$.count' )) AS tb", '1 =1')->where('id', $id)->like('dt', $monthYear)->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['tgl'][] = $row['tgl'];
                $return['count'][] = intval($row['count']);
            }
        } else {
            $return['tgl'][] = 0;
            $return['count'][] = 0;
        }
        return json_encode($return);
    }

    public function detailByProductDataMontly(){
        $id = $this->request->getPost('id');
        $monthYear = $this->request->getPost('monthYear');
        $monthYear = explode('-', $monthYear);
        $data = $this->model->select("DATE_FORMAT(STR_TO_DATE(dt, '%d-%m-%Y'), '%b %Y') as month_year, SUM(count) as count")->join('json_table(sold_history, \'$[*]\' COLUMNS ( dt VARCHAR ( 20 ) path \'$.date\', count INT path \'$.count\' )) AS tb', '1=1')->where('id', $id)->like('dt', $monthYear[1])->groupBy('YEAR(STR_TO_DATE(dt, \'%d-%m-%Y\')), MONTH(STR_TO_DATE(dt, \'%d-%m-%Y\'))')->orderBy('YEAR(STR_TO_DATE(dt, \'%d-%m-%Y\')), MONTH(STR_TO_DATE(dt, \'%d-%m-%Y\'))')->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['month_year'][] = $row['month_year'];
                $return['count'][] = intval($row['count']);
            }
        } else {
            $return['month_year'][] = 0;
            $return['count'][] = 0;
        }
        return json_encode($return);
    }

    public function detailByProductDataPrice(){
        $id = $this->request->getPost('id');
        $monthYear = $this->request->getPost('monthYear');
        $data = $this->model->select("dt, buy, tb.percent,sell")->join("json_table (price_history,'$[*]' COLUMNS ( dt VARCHAR ( 20 ) path '$.date', buy INT path '$.buy', percent INT path '$.percent', sell INT path '$.sell' )) AS tb", '1 = 1')->where('id', $id)->like('dt', $monthYear)->orderBy('dt', 'asc')->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['dt'][]     = date('d M Y', strtotime($row['dt']));
                $return['buy'][]    = intval($row['buy']);
                $return['percent'][]= (float) $row['percent'];
                $return['sell'][]   = intval($row['sell']);
            }
        } else {
            $return['dt'][]     = 0;
            $return['buy'][]    = 0;
            $return['percent'][]= 0;
            $return['sell'][]   = 0;
        }
        return json_encode($return);
    }

    public function detailByProductDataIncoming()
    {
        $id = $this->request->getPost('id');
        $monthYear = $this->request->getPost('monthYear');
        $monthYear = explode('-', $monthYear);
        $data = $this->model->select("dt, count")->join("json_table (incoming_history,'$[*]' COLUMNS ( dt VARCHAR ( 20 ) path '$.date', count INT path '$.count')) AS tb", '1 = 1')->where('id', $id)->like('dt', $monthYear[1])->orderBy('dt', 'asc')->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['dt'][]     = date('d M Y', strtotime($row['dt']));
                $return['count'][]    = intval($row['count']);
            }
        } else {
            $return['dt'][]     = 0;
            $return['count'][]    = 0;
        }
        return json_encode($return);
    }

    public function byTransaction()
    {
        $this->view->setData(['submenu_byTransaction' => 'active']);
        $this->data['menu'] = 'Transaction report';
        return view('report/byTransaction', $this->data);
    }

    public function transactionDaily() {
        $monthYear = $this->request->getPost('monthYear');
        $monthYear = explode('-', $monthYear);
        $data = $this->trans->select('DATE(created_at) AS dt,sum(qty) as items ,COUNT(*) AS count, sum(grand_total) as grand_total, sum(discount) as discount_total')->join("json_table (items,'$[*]' COLUMNS ( qty INT path '$.qty')) AS tb", '1= 1')->where('YEAR(created_at)', $monthYear[1])->where('MONTH(created_at)', $monthYear[0])->groupBy('dt')->orderBy('dt', 'asc')->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['dt'][]             = date('d M', strtotime($row['dt']));
                $return['count'][]          = intval($row['count']);
                $return['items'][]          = intval($row['items']);
                $return['grand_total'][]    = intval($row['grand_total']);
                $return['discount_total'][] = intval($row['discount_total']);
            }
        } else {
            $return['dt'][]             = 0;
            $return['count'][]          = 0;
            $return['items'][]          = 0;
            $return['grand_total'][]    = 0;
            $return['discount_total'][] = 0;
        }
        return json_encode($return);
    }

    public function transactionMonthly() {
        $monthYear = $this->request->getPost('monthYear');
        $monthYear = explode('-', $monthYear);
        $data = $this->trans->select('created_at AS dt, sum(qty) as items ,COUNT(*) AS count, sum(grand_total) as grand_total, sum(discount) as discount_total')->join("json_table (items,'$[*]' COLUMNS ( qty INT path '$.qty')) AS tb", '1= 1')->where('YEAR(created_at)', $monthYear[1])->groupBy('MONTH(created_at)', $monthYear[0])->orderBy('dt', 'asc')->find();
        if (count($data) > 0) {
            foreach ($data as $row) {
                $return['dt'][] = date('M Y', strtotime($row['dt']));
                $return['count'][] = intval($row['count']);
                $return['items'][] = intval($row['items']);
                $return['grand_total'][]    = intval($row['grand_total']);
                $return['discount_total'][] = intval($row['discount_total']);
            }
        } else {
            $return['dt'][] = 0;
            $return['count'][] = 0;
            $return['items'][] = 0;
            $return['grand_total'][]    = 0;
            $return['discount_total'][] = 0;
        }
        return json_encode($return);
    }
}
