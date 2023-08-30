<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_point;
use App\Models\M_product;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

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

    public function download()
    {
        $mproduct = new M_product();
        $products = $mproduct->select('barcode, name, stock, price')->orderBy('name', 'asc')->findAll();

        $spreadsheet = new Spreadsheet();

        $spreadsheet->setActiveSheetIndex(0);

       /*  $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('logo');
        $drawing->setPath(base_url('assets/img/ais.png'));
        $drawing->setCoordinates('B1');
        $drawing->setOffsetX(110);
        $drawing->setRotation(25);
        $drawing->getShadow()->setVisible(true);
        $drawing->getShadow()->setDirection(45);
        $drawing->setWorksheet($spreadsheet->getActiveSheet()); */


        $spreadsheet->getActiveSheet()->setCellValue('A2', 'Toko Susu&Perlengkapan Bayi')
            ->setCellValue('A3', 'Jalan Erlangga 83D, Pasuruan');
        $spreadsheet->getActiveSheet()->mergeCells('A2:E2');
        $spreadsheet->getActiveSheet()->mergeCells('A3:E3');

        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
        $spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
        $spreadsheet->getActiveSheet()->getStyle('A3')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        /* $richText = new RichText();
        $richText->createText('This invoice is ');

        $payable = $richText->createTextRun('payable within thirty days after the end of the month');
        $payable->getFont()->setBold(true);
        $payable->getFont()->setItalic(true);
        $payable->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));

        $richText->createText(', unless specified otherwise on the invoice.');

        $spreadsheet->getActiveSheet()->getCell('A18')->setValue($richText);
         */

        $spreadsheet->getActiveSheet()
            ->setCellValue('A5', 'No.')
            ->setCellValue('B5', 'Barcode')
            ->setCellValue('C5', 'Product')
            ->setCellValue('D5', 'Stock')
            ->setCellValue('E5', 'Price');

        $column = 5;
        $no = 0;
        
        foreach ($products as $product) {
            $spreadsheet->getActiveSheet()
                ->setCellValue('A' . $column, ++$no)
                ->setCellValue('B' . $column, $product['barcode'])
                ->setCellValue('C' . $column, $product['name'])
                ->setCellValue('D' . $column, $product['stock'])
                ->setCellValue('E' . $column, 'Rp. ' . number_format($product['price'], 0,',', '.'));
            $column++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = date('Y-m-d-His') . '-products-stock';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }
}
