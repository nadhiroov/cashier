<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Controllers\Products;
use App\Controllers\Member;
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
            $printer->text($this->buatBaris1Kolom('Toko Susu dan Perlengkapan Bayi AIS'));
            $printer->text($this->buatBaris1Kolom('Pasuruan, telp : 081xxxxxxx'));
            $printer->text($this->buatBaris1Kolom("Faktur: $form[notaNumber]"));
            $printer->text($this->buatBaris1Kolom("Tanggal: " . date('d-m-Y H:i:s')));

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
            $printer->text($this->buatBaris1Kolom('Terimaksih atas kunjungan anda'));

            $printer->feed(4);
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

    function buatBaris1Kolom($kolom1)
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

    function buatBaris3Kolom($kolom1, $kolom2, $kolom3)
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

    function buatBaris3KolomPoint($kolom1, $kolom2, $kolom3)
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

    function testPrint()
    {
        $profile = CapabilityProfile::load("simple");
        $connector = new WindowsPrintConnector("POS58 Printer");
        $printer = new Printer($connector, $profile);
        $printer->initialize();
        $printer->selectPrintMode(Printer::MODE_FONT_A);
        $printer->text($this->buatBaris1Kolom('                     AIS'));
        $printer->text($this->buatBaris1Kolom('         Toko Susu&Perlengkapan Bayi'));
        $printer->text($this->buatBaris1Kolom('         Erlangga 83D, Pasuruan'));
        $printer->text($this->buatBaris1Kolom('         Telp     : 081xxxxxxx'));
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
}
