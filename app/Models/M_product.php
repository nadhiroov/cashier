<?php

namespace App\Models;

use CodeIgniter\Model;

class M_product extends Model
{
    protected $table            = 'product';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = ['brand_id', 'barcode', 'name', 'stock', 'price', 'price_history', 'sold_history', 'incoming_history', 'purchase_price', 'percent'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'name'     => 'required|alpha_numeric_punct',
        'stock'     => 'required|integer',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;


    public function find_item($keyword, $registered)
    {
        $db = db_connect();
        $registered = explode(',', $registered);

        $query = $db->table('product')
            ->select('barcode, name, price, if(discount is not null, (price*(discount/100)), 0) as discount')
            ->join('discount as B', "product.id = B.product_id AND CURRENT_TIMESTAMP() BETWEEN B.date_start AND B.date_end", 'left')
            ->where('deleted_at', null)
            ->where('stock >', 0)
            ->groupStart()
            ->like('barcode', $keyword)
            ->orLike('name', $keyword)
            ->groupEnd();

        $query->whereNotIn('barcode', $registered);
        return $query->get();
    }

    public function update_stok($id, $total)
    {
        $sql = "
			UPDATE `pj_barang` SET `total_stok` = `total_stok` - " . $total . " WHERE `id` = '" . $id . "'
		";

        return $this->db->query($sql);
    }
}
