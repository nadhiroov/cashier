<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_transaction;

class Transaction extends BaseController
{
    protected $model;
    public function __construct()
    {
        $this->model = new M_transaction();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_selling' => 'active', 'submenu_transaction' => 'active']);
        $this->data['menu'] = 'Products';
    }

    public function index()
    {
        return view('transaction/index', $this->data);
    }

    function process()
    {
        $form = $this->request->getPost();
        // if not select product at all
        if ($form['barcode'][0] == '') {
            $return = [
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Plese select at least one product'
            ];
            echo json_encode($return);
            return false;
        }

        if ($form['money'] == '') {
            $return = [
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Please enter money'
            ];
            echo json_encode($return);
            return false;
        }

        $member = explode('|', $form['member']);
        if ($member != '0') {
            $form['member'] = $member[0];
        }

        // use point payment
        $money = $form['money'];
        if ($form['withPoint'] == 'true') {
            $form['moneyPoint'] = $form['money'] + $member[2];
            $form['point'] = $member[2];
        }

        // less payment
        if (($form['money']) < $form['grandTotal']) {
            $return = [
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Less payment'
            ];
            echo json_encode($return);
            return false;
        }

        for ($i=0; $i < count($form['barcode']); $i++) { 
            if ($form['barcode'][$i] != '') {
                
            }
        }

        $transactionData = [
            'nota_number'   => $form['notaNumber'],
            'member'        => $form['member'],
            'grand_total'   => $form['grandTotal'],
            'user_id'       => $form['cashierId'],
            'total_pay'     => $money
        ];
        var_dump($form);
    }
}
