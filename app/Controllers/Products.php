<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\M_brand;
use App\Models\M_product;

class Products extends BaseController
{
    protected $model;
    protected $brand;
    public function __construct()
    {
        $this->model = new M_product();
        $this->brand = new M_brand();
        $this->view = \Config\Services::renderer();
        $this->view->setData(['menu_warehouse' => 'active', 'submenu_product' => 'active']);
        $this->data['menu'] = 'Products';
    }

    public function index()
    {
        return view('product/index', $this->data);
    }

    public function add()
    {
        $data = $this->brand->findAll();
        return view('product/add', ['brand' => $data]);
    }

    public function edit($id)
    {
        $data = $this->model->find($id);
        $brand = $this->brand->findAll();
        return view('product/edit', ['content' => $data, 'brand' => $brand]);
    }

    public function editPrice($id)
    {
        $data = $this->model->find($id);
        return view('product/editPrice', ['content' => $data]);
    }

    public function processEditPrice() {
        $form = $this->request->getPost('form');
        $data = $this->model->find($form['id']);

        if (isset($form['checkPrice'])) {
            $form['price'] = (int) str_replace('.', '', $form['price']);
            $form['purchase_price'] = (int) str_replace('.', '', $form['purchase_price']);
            $price = [
                'date'  => date('d-m-Y'),
                'buy'   => intval($form['purchase_price']),
                'percent'=> (float) $form['percent'],
                'sell'  => $form['price']
            ];
            if ($data['price_history'] == null) {
                $save['price_history'] = json_encode([$price]);
            }else{
                $oldPrice = json_decode($data['price_history'], true);
                array_push($oldPrice, $price);
                $save['price_history'] = json_encode($oldPrice);
            }
            $save['purchase_price'] = $form['purchase_price'];
            $save['percent']        = $form['percent'];
            $save['price']          = $form['price'];
        }

        if (isset($form['checkStock'])) {
            $price = [
                'date'  => date('d-m-Y'),
                'count'   => intval($form['stock']),
            ];
            if ($data['incoming_history'] == null) {
                $save['incoming_history'] = json_encode([$price]);
            } else {
                $oldPrice = json_decode($data['incoming_history'], true);
                array_push($oldPrice, $price);
                $save['incoming_history'] = json_encode($oldPrice);
            }
        }
        $save['id']             = $form['id'];
        try {
            $this->model->save($save);
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

    public function detail($id)
    {
        $this->data['content'] = $this->model->select('product.*, category, brand')->join('brand B', 'B.id = product.brand_id')->join('category C', 'C.id = B.category_id')->find($id);
        $this->data['submenu'] = 'Detail';
        return view('product/detail', $this->data);
    }

    public function getData($id = null)
    {
        $dtTable = $this->request->getVar();
        $data = $this->model->select('product.*, category, brand')->join('brand B', 'B.id = product.brand_id')->join('category C', 'C.id = B.category_id')->limit($dtTable['length'], $dtTable['start'])->orderBy('name', 'asc');
        if ($id != null) {
            $data = $data->where('B.id', $id);
        }
        $data= $data->where('product.deleted_at is null');
        if (!empty($dtTable['search']['value'])) {
            $data = $this->model->like('product.name', $dtTable['search']['value']);
            $data = $this->model->orLike('product.stock', $dtTable['search']['value']);
            $data = $this->model->orLike('product.price', $dtTable['search']['value']);
        }
        if (!empty($dtTable['order'][0]['column'])) {
            $data = $this->model->orderBy($dtTable['columns'][$dtTable['order'][0]['column']]['data'], $dtTable['order'][0]['dir']);
        }
        $filtered = $data->countAllResults(false);
        $datas = $data->findAll();
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
        $form['price'] = (int) str_replace('.', '', $form['price']);
        $form['purchase_price'] = (int) str_replace('.', '', $form['purchase_price']);

        if ($form['purchase_price'] != '' && !isset($form['id'])) {
            $price = [
                'date'  => date('d-m-Y'),
                'buy'   => intval($form['purchase_price']),
                'percent'=> (float) $form['percent'],
                'sell'  => $form['price']
            ];
            $form['price_history'] = json_encode([$price]);
        }
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

    public function delete($id = null)
    {
        try {
            $this->model->delete($id);
            $return = [
                'status'    => 'success',
                'title'     => 'Success',
                'message'   => 'Data deleted!'
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

    public function find()
    {
        $keyword = $this->request->getPost('keyword');
        $registered = $this->request->getPost('registered');
        // var_dump($registered);die;
        $item = $this->model->find_item($keyword, $registered);
        $json['data']     = "<ul id='daftar-autocomplete'>";
        foreach ($item->getResultObject() as $key) {
            $json['data'] .= "
						<li>
							<b>Barcode</b> :
							<span id='kodenya'>" . $key->barcode . "</span> <br />
							<span id='barangnya'>" . $key->name . "</span>
							<span id='harganya' style='display:none;'>" . $key->price . "</span>
							<span id='discountnya' style='display:none;'>" . $key->discount . "</span>
						</li>
					";
        }
        $json['data'] .= "</ul>";
        $json['status'] = 1;
        echo json_encode($json);
    }

    public function stock()
    {
        $barcode = $this->request->getPost('barcode');
        $stok = $this->request->getPost('stok');
        $stock = $this->model->select('name, stock')->where('barcode', $barcode)->first();
        if ($stok > $stock['stock']) {
            echo json_encode(array('status' => 0, 'message' => " Limited stock alert! We have just $stock[stock] units of $stock[name]!"));
        } else {
            echo json_encode(array('status' => 1));
        }
    }

    public function updateStoct($barcode, $itemCount)
    {
        $product = $this->model->where('barcode', $barcode)->first();
        $date = date('d-m-Y');
        if (!$product) {
            return "Produk not found";
        }
        $remainingStock = $product['stock'] - $itemCount;
        if ($remainingStock >= 0) {
            $soldHistory = json_decode($product['sold_history'], true);
            if ($soldHistory == null) { // if the history is null
                $soldHistory[] = [
                    'date' => $date,
                    'count'=> intval($itemCount)
                ];
            } else {
                $currentDate = false;
                for ($i=0; $i < count($soldHistory) ; $i++) {
                    if ($soldHistory[$i]['date'] == $date) { // add in current date
                        $soldHistory[$i]['count'] = intval($soldHistory[$i]['count']) + intval($itemCount);
                        $currentDate = true;
                        break;
                    }
                }
                if (!$currentDate) { // add in another day
                    $soldHistory[] = [
                        'date' => $date,
                        'count' => intval($itemCount)
                    ];
                }
            }
            $data = [
                'stock'         => $remainingStock,
                'sold_history'  => json_encode($soldHistory)
            ];
            // var_dump($data);die;
            $this->model->update($product['id'], $data);
            return [
                'id'    => $product['id'],
                'name'  => $product['name']
            ];
        } else {
            return false;
        }
    }
}
