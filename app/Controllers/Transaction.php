<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_point;
use App\Models\M_product;
use App\Models\M_transaction;

class Transaction extends BaseController
{
    protected $model;
    protected $point;
    protected $product;
    public function __construct()
    {
        $this->model = new M_transaction();
        $this->point = new M_point();
        $this->product = new M_product();
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
            $pointSetting = $this->point->first();
            $flor = floor($form['grandTotal'] / $pointSetting['nominal']);
            $earnedPoint = $flor * $pointSetting['point'];
        }

        // use point payment
        $money = $form['money'];
        if ($form['withPoint'] == 'true') {
            $form['money'] = $form['money'] + $member[2];
            $form['minusPoint'] = $form['grandTotal'] - $money;
            $form['updatedPoint'] = $member[2] - $form['minusPoint'];
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
                $item[] = [
                    'id'    => $form['barcode'][$i],
                    'price' => $form['harga_satuan'][$i],
                    'qty'   => $form['jumlah_beli'][$i]
                ];
                
            }
        }

        $transactionData = [
            'nota_number'   => $form['notaNumber'],
            'member'        => $form['member'],
            'grand_total'   => $form['grandTotal'],
            'user_id'       => $form['cashierId'],
            'total_pay'     => $money,
            'discount'      => $form['totalDiscount'],
            'point_pay'     => $form['minusPoint'],
            'point_earned'  => $earnedPoint ?? null,
            'items'         => json_encode($item)
        ];
        $this->model->save($transactionData);

        // update stock


        // update point member
        var_dump($transactionData);
    }
}
