<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_point;
use App\Models\M_product;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Setting extends BaseController
{
    public function __construct()
    {
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_setting' => 'active']);
        $this->data['menu'] = 'Settings';
        if (session()->get('is_admin') != 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
    }

    public function index()
    {
        $point = new M_point();
        $this->data['point'] = $point->first();
        return view('setting/index', $this->data);
    }

    public function processPoint()
    {
        $form = $this->request->getPost('form');
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

    public function resetPoint() {
        try {
            $db = db_connect();
            $db->simpleQuery('UPDATE member SET point = 0');
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data saved successfully'
            ];
        } catch (\Throwable $er) {
            $return = [
                'status'    => 'error',
                'title'     => 'Error',
                'message'   => $er->getMessage()
            ];
        }
        echo json_encode($return);
    }

    public function download()
    {
        $mproduct = new M_product();
        $products = $mproduct->select('barcode, name, stock, price')->orderBy('name', 'asc')->findAll();

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);

        $date = date('d-m-Y');
        $spreadsheet->getActiveSheet()->setCellValue('A2', 'Toko Susu&Perlengkapan Bayi')
            ->setCellValue('A3', 'Jalan Erlangga 83D, Pasuruan')
            ->setCellValue('A4', "SKU Tanggal $date");

        $spreadsheet->getActiveSheet()->mergeCells('A2:E2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:E3');
        $spreadsheet->getActiveSheet()->mergeCells('A4:E4');

        $spreadsheet->getActiveSheet()->getStyle('A2:A4')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]
        );

        $spreadsheet->getActiveSheet()
            ->setCellValue('A7', 'No.')
            ->setCellValue('B7', 'Barcode')
            ->setCellValue('C7', 'Product')
            ->setCellValue('D7', 'Stock')
            ->setCellValue('E7', 'Price');

        $spreadsheet->getActiveSheet()->getStyle('A7:E7')->applyFromArray(
            [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_MEDIUM,
                    ]
                ]
            ]
        );
        
        $column = 8;
        $no = 0;

        foreach ($products as $product) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $column, ++$no)
                ->setCellValue('B' . $column, $product['barcode'])
                ->setCellValue('C' . $column, $product['name'])
                ->setCellValue('D' . $column, $product['stock'])
                ->setCellValue('E' . $column, 'Rp. ' . number_format($product['price'], 0, ',', '.'));
            $column++;
        }
        $column--;
        $spreadsheet->getActiveSheet()->getStyle("A8:E$column")->applyFromArray(
            [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ]
            ]
        );

        $writer = new Xls($spreadsheet);
        $filename = date('Y-m-d-His') . '-products-stock';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xls');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
