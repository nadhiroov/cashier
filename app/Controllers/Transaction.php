<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Products;
use App\Controllers\Member;
use App\Models\M_member;
use App\Models\M_point;
use App\Models\M_transaction;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\CapabilityProfile;
use Mike42\Escpos\Printer;

class Transaction extends BaseController
{
    protected $model;
    protected $point;
    protected $product;
    public function __construct()
    {
        $this->model = new M_transaction();
        $this->point = new M_point();
        $this->product = new Products();
        $this->member = new Member();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_selling' => 'active']);
        $this->data['menu'] = 'Transaction';
    }

    public function index()
    {
        $this->view->setData(['submenu_transaction' => 'active']);
        $today = date('Y-m-d');
        $records = $this->model->where("DATE(created_at) = '$today'")->countAllResults();
        $this->data['notaNumber'] = date('dmy') . '-' . (sprintf('%03d', $records + 1));
        return view('transaction/index', $this->data);
    }

    public function process()
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

        $member = $form['member'] == '0' ? 0 : explode('|', $form['member']);
        // $member = explode('|', $form['member']);
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

        for ($i = 0; $i < count($form['barcode']); $i++) {
            if ($form['barcode'][$i] != '') {
                $product = $this->product->updateStoct($form['barcode'][$i], $form['jumlah_beli'][$i]);
                if (!$product) {
                    $return = [
                        'status'  => 'error',
                        'title'   => 'Error',
                        'message' => 'Out of stock'
                    ];
                    echo json_encode($return);
                    return false;
                }
                $item[] = [
                    'id'    => intval($product['id']),
                    'price' => intval($form['harga_satuan'][$i]),
                    'qty'   => intval($form['jumlah_beli'][$i])
                ];

                $productPrint[] = [
                    'name'    => $product['name'],
                    'price' => $form['harga_satuan'][$i],
                    'qty'   => $form['jumlah_beli'][$i]
                ];
            }
        }

        if ($form['withPoint'] == 'true') { // point subtraction
            $this->member->pointSubtraction($member[0], intval($form['minusPoint']));
        }

        if ($member != '0' && intval($earnedPoint) > 0) { // point adding
            $this->member->pointAddition($member[0], intval($earnedPoint));
        }

        if ($member != '0') {
            $modelMember = new M_member();
            $lastPoint = $modelMember->select('point, name')->where('id', $member[0])->first();
        }


        $transactionData = [
            'nota_number'   => $form['notaNumber'],
            'member'        => $form['member'],
            'grand_total'   => $form['grandTotal'],
            'user_id'       => $form['cashierId'],
            'total_pay'     => $money,
            'discount'      => $form['totalDiscount'],
            'point_pay'     => $form['minusPoint'] ?? null,
            'point_earned'  => $earnedPoint ?? null,
            'items'         => json_encode($item)
        ];

        if ($form['nota'] == 'true') {
            $profile = CapabilityProfile::load("simple");
            $connector = new WindowsPrintConnector("POS58 Printer");
            $printer = new Printer($connector, $profile);
            $printer->initialize();
            $printer->selectPrintMode(Printer::MODE_FONT_A);
            $printer->text($this->buatBaris1Kolom('                     AIS'));
            $printer->text($this->buatBaris1Kolom('         Toko Susu&Perlengkapan Bayi'));
            $printer->text($this->buatBaris1Kolom('         Erlangga 83D, Pasuruan'));
            $printer->text($this->buatBaris1Kolom('         Telp     : 087840519421'));
            $printer->text($this->buatBaris1Kolom("         Faktur: $form[notaNumber]"));
            if (isset($lastPoint)) {
                $printer->text($this->buatBaris1Kolom("         Member   : $lastPoint[name]"));
            }
            $printer->text($this->buatBaris1Kolom("         Tanggal  : " . date('d-m-Y H:i:s')));

            $printer->text($this->buatBaris1Kolom('---------------------------------'));
            foreach ($productPrint as $key) {
                $printer->text($this->buatBaris1Kolom($key['name']));
                $printer->text($this->buatBaris3Kolom($key['qty'], $key['price'], intval($key['qty']) * intval($key['price'])));
            }
            $printer->text($this->buatBaris1Kolom('---------------------------------'));

            $printer->text($this->buatBaris3Kolom('', 'Discount: ', $form['totalDiscount']));
            $printer->text($this->buatBaris3Kolom('', 'Total: ', $form['grandTotal']));
            $printer->text($this->buatBaris3Kolom('', 'Bayar: ', $form['money']));
            $printer->text($this->buatBaris3Kolom('', 'Kembali: ', intval($form['grandTotal']) - intval($form['money'])));
            $printer->text($this->buatBaris3KolomPoint(!isset($lastPoint) ? '' : 'Point: ' . number_format($lastPoint['point'], 0, ',', '.'), '', ''));
            $printer->cut();
            $printer->close();
        }

        try {
            $this->model->save($transactionData);
            $return = [
                'status'  => 'success',
                'title'   => 'Success',
                'message' => 'Transaction success!'
            ];
            echo json_encode($return);
            return true;
        } catch (\Exception $er) {
            $return = [
                'status'  => 'error',
                'title'   => 'Error',
                'message' => 'Transaction error'
            ];
            echo json_encode($return);
            return false;
        }
    }

    public function print($id)
    {
        $data = $this->model->select('transaction.*, b.name as memberName, p.name as produckName, tb.price, tb.qty')->join("json_table (items,'$[*]' COLUMNS ( idProd INT path '$.id', price INT path '$.price', qty INT path '$.qty')) AS tb", '1= 1')->join('member b', 'transaction.member = b.id', 'left')->join('product p', 'tb.idProd = p.id')->orderBy('created_at', 'desc')->where('transaction.id', $id)->find();
        if ($data[0]['member'] != 0) {
            $modelMember = new M_member();
            $lastPoint = $modelMember->select('point, name')->where('id', $data[0]['member'])->first();
        }
        
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("POS58 Printer");
        $printer = new Printer($connector, $profile);
        $printer->initialize();
        $printer->selectPrintMode(Printer::MODE_FONT_A);
        $printer->text($this->buatBaris1Kolom('                     AIS'));
        $printer->text($this->buatBaris1Kolom('         Toko Susu&Perlengkapan Bayi'));
        $printer->text($this->buatBaris1Kolom('         Erlangga 83D, Pasuruan'));
        $printer->text($this->buatBaris1Kolom('         Telp     : 087840519421'));
        $printer->text($this->buatBaris1Kolom("         Faktur: $data[0][nota_number]"));
        if (isset($lastPoint)) {
            $printer->text($this->buatBaris1Kolom("         Member   : $lastPoint[name]"));
        }
        $printer->text($this->buatBaris1Kolom("         Tanggal  : " . date('d-m-Y H:i:s', strtotime($data['0']['created_at']))));

        $printer->text($this->buatBaris1Kolom('---------------------------------'));
        foreach ($data as $key) {
            $printer->text($this->buatBaris1Kolom($key['produckName']));
            $printer->text($this->buatBaris3Kolom($key['qty'], $key['price'], intval($key['qty']) * intval($key['price'])));
        }
        $printer->text($this->buatBaris1Kolom('---------------------------------'));

        $printer->text($this->buatBaris3Kolom('', 'Discount: ', $data[0]['totalDiscount']));
        $printer->text($this->buatBaris3Kolom('', 'Total: ', $data[0]['grand_total']));
        if ($data[0]['point_pay'] != null) {
            $data[0]['money'] += $data[0]['point_pay'];
        }
        $printer->text($this->buatBaris3Kolom('', 'Bayar: ', $data[0]['total_pay']));
        $printer->text($this->buatBaris3Kolom('', 'Kembali: ', intval($data[0]['grand_total']) - intval($data[0]['money'])));
        $printer->text($this->buatBaris3KolomPoint(!isset($lastPoint) ? '' : 'Point: ' . number_format($lastPoint['point'], 0, ',', '.'), '', ''));
        $printer->cut();
        $printer->close();
        echo json_encode([
            'status'  => 'success',
            'title'   => 'Success',
            'message' => 'Print success!'
        ]);
    }

    public function buatBaris1Kolom($kolom1)
    {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 42;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = count($kolom1Array);

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        // return implode($hasilBaris, "\n") . "\n";
        return implode("\n", $hasilBaris) . "\n";
    }

    public function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
    {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 14;
        $lebar_kolom_2 = 14;
        $lebar_kolom_3 = 14;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        // return implode($hasilBaris, "\n") . "\n";
        return implode("\n", $hasilBaris) . "\n";
    }

    public function buatBaris3KolomPoint($kolom1, $kolom2, $kolom3)
    {
        // Mengatur lebar setiap kolom (dalam satuan karakter)
        $lebar_kolom_1 = 20;
        $lebar_kolom_2 = 11;
        $lebar_kolom_3 = 11;

        // Melakukan wordwrap(), jadi jika karakter teks melebihi lebar kolom, ditambahkan \n 
        $kolom1 = wordwrap($kolom1, $lebar_kolom_1, "\n", true);
        $kolom2 = wordwrap($kolom2, $lebar_kolom_2, "\n", true);
        $kolom3 = wordwrap($kolom3, $lebar_kolom_3, "\n", true);

        // Merubah hasil wordwrap menjadi array, kolom yang memiliki 2 index array berarti memiliki 2 baris (kena wordwrap)
        $kolom1Array = explode("\n", $kolom1);
        $kolom2Array = explode("\n", $kolom2);
        $kolom3Array = explode("\n", $kolom3);

        // Mengambil jumlah baris terbanyak dari kolom-kolom untuk dijadikan titik akhir perulangan
        $jmlBarisTerbanyak = max(count($kolom1Array), count($kolom2Array), count($kolom3Array));

        // Mendeklarasikan variabel untuk menampung kolom yang sudah di edit
        $hasilBaris = array();

        // Melakukan perulangan setiap baris (yang dibentuk wordwrap), untuk menggabungkan setiap kolom menjadi 1 baris 
        for ($i = 0; $i < $jmlBarisTerbanyak; $i++) {

            // memberikan spasi di setiap cell berdasarkan lebar kolom yang ditentukan, 
            $hasilKolom1 = str_pad((isset($kolom1Array[$i]) ? $kolom1Array[$i] : ""), $lebar_kolom_1, " ");
            // memberikan rata kanan pada kolom 3 dan 4 karena akan kita gunakan untuk harga dan total harga
            $hasilKolom2 = str_pad((isset($kolom2Array[$i]) ? $kolom2Array[$i] : ""), $lebar_kolom_2, " ", STR_PAD_LEFT);

            $hasilKolom3 = str_pad((isset($kolom3Array[$i]) ? $kolom3Array[$i] : ""), $lebar_kolom_3, " ", STR_PAD_LEFT);

            // Menggabungkan kolom tersebut menjadi 1 baris dan ditampung ke variabel hasil (ada 1 spasi disetiap kolom)
            $hasilBaris[] = $hasilKolom1 . " " . $hasilKolom2 . " " . $hasilKolom3;
        }

        // Hasil yang berupa array, disatukan kembali menjadi string dan tambahkan \n disetiap barisnya.
        // return implode($hasilBaris, "\n") . "\n";
        return implode("\n", $hasilBaris) . "\n";
    }

    public function testPrint()
    {
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("POS58 Printer");
        $printer = new Printer($connector, $profile);
        $printer->initialize();
        $printer->selectPrintMode(Printer::MODE_FONT_A);
        $printer->text($this->buatBaris1Kolom('                     AIS'));
        $printer->text($this->buatBaris1Kolom('         Toko Susu&Perlengkapan Bayi'));
        $printer->text($this->buatBaris1Kolom('         Erlangga 83D, Pasuruan'));
        $printer->text($this->buatBaris1Kolom('         Telp     : 087840519421'));
        $printer->text($this->buatBaris1Kolom("         Faktur   : 110823-001"));
        $printer->text($this->buatBaris1Kolom("         Tanggal  : " . date('d-m-Y H:i:s')));

        $printer->text($this->buatBaris1Kolom('------------------------------------------'));
        $printer->text($this->buatBaris1Kolom('Dancow Coklat 500gr'));
        $printer->text($this->buatBaris3Kolom(3, 'Rp. 20.000', 'Rp. 60.000'));
        $printer->text($this->buatBaris1Kolom('Dancow Vanilla 500gr'));
        $printer->text($this->buatBaris3Kolom(3, 'Rp. 19.000', 'Rp. 57.000'));

        $printer->text($this->buatBaris1Kolom('------------------------------------------'));
        $printer->text($this->buatBaris3Kolom('', 'Discount: ', 'Rp. 1000'));
        $printer->text($this->buatBaris3Kolom('', 'Total: ', 'Rp. 116.000'));
        $printer->text($this->buatBaris3Kolom('', 'Bayar: ', 'Rp. 120.000'));
        $printer->text($this->buatBaris3Kolom('', 'Kembali: ', 'Rp. 4.000'));
        $printer->text($this->buatBaris3KolomPoint('Point: Rp. 1.000', '', ''));
        $printer->cut();
        $printer->close();
    }

    public function historyIndex()
    {
        $this->view->setData(['submenu_history' => 'active']);
        return view('transHistory/index', $this->data);
    }

    public function getDataHistory()
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('transaction.*, JSON_LENGTH(transaction.items) as item, name')->join('member B', 'transaction.member = B.id', 'left')->limit($dtTable['length'], $dtTable['start'])->orderBy('created_at', 'desc');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('nota_number', $dtTable['search']['value']);
            $data = $this->model->orLike('name', $dtTable['search']['value']);
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

    public function detailTrans($id)
    {
        $this->view->setData(['submenu_history' => 'active']);
        $this->data['content'] = $this->model->select('transaction.*, b.name as memberName, u.fullname')->join('users u', 'u.id = transaction.user_id', 'left')->join('member b', 'transaction.member = b.id', 'left')->find($id);
        // dd($this->data['content']);
        return view('transHistory/detail', $this->data);
    }

    public function detailTransData($id)
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('transaction.*, b.name as memberName, p.name as produckName, tb.price, tb.qty')->join("json_table (items,'$[*]' COLUMNS ( idProd INT path '$.id', price INT path '$.price', qty INT path '$.qty')) AS tb", '1= 1')->join('member b', 'transaction.member = b.id', 'left')->join('product p', 'tb.idProd = p.id')->orderBy('created_at', 'desc')->where('transaction.id', $id);
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('nota_number', $dtTable['search']['value']);
            // $data = $this->model->orLike('name', $dtTable['search']['value']);
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
}
