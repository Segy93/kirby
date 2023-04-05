<?php header('Content-type: text/html; charset=utf-8'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>{{ !empty($seo) ? $seo->title : 'Monitor system' }}</title>
    <link rel="stylesheet" href="/Components/DataImport/css/DataImport.css" />
    <link rel="stylesheet" href="/Components/libs/bootstrap/css/bootstrap.min.css" />
    <script src="/Components/DataImport/js/DataImport.js"></script>
</head>
<body>
    <div id="main_window" style="padding-top: 120px; ">
        <div class='progress_bar' id='progress_bar'>
            <div class='category_outter' id='category_outter_id'>
                <div class='category_inner_name'></div>
                <div class='category_inner'></div>
            </div>

            <div class='product_outter' id='product_outter_id'>
                <div class='product_inner_name'></div>
                <div class='product_inner'></div>
            </div>

            <h1 class="title">Monitor system</h1>
            <button class="btn btn-sm btn-primary" onclick="importProducts()">Uvoz podataka</button>
            <button class="btn btn-sm btn-primary" onclick="updateProducts()">Ažuriranje podataka</button>
            <button class="btn btn-sm btn-primary" onclick="stopImport()">Stopiraj uvoz</button>
            <input id="category_type" type="text" style="color: black"/>
        </div>
        <br/>
        <div id="product-info">
        </div>
    </div>
</body>
</html>
