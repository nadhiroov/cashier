<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Report extends BaseController
{
    public function byProduct()
    {
        return view('report/byProduct');
    }
}
