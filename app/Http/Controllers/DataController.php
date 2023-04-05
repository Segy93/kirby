<?php

namespace App\Http\Controllers;

use App\Components\DataImport;

class DataController extends BaseController {

    public function index() {
        return View('DataImport/templates/DataImport');
    }

    public function productsImport($category_name = null) {
        $dic = new DataImport('importProducts');
        return $dic->import($category_name);
    }

    public function productImport($artid = null) {
        $dic = new DataImport('importProduct');
        return $dic->import($artid);
    }

    public function productcUpdate($category_name = null) {
        $dic = new DataImport('updateProducts');
        return $dic->import($category_name);
    }

    public function productUpdate($artid = null) {
        $dic = new DataImport('updateProduct');
        return $dic->import($artid);
    }
}
