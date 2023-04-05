<?php header('Content-type: text/html; charset=utf-8'); ?>

<!DOCTYPE html>
<html>
<head>
    <title>{{ !empty($seo) ? $seo->title : 'Monitor system' }}</title>
    <link rel="stylesheet" href="/Components/DataImport/css/DataImport.css" />
    <script src="/Components/DataImport/js/DataImport.js"></script>
</head>
<body>
    <div id="main_window" style="padding-top: 120px; ">
        <h1>Monitor system</h1>
        <div class='progress_bar' id='progress_bar'>
            <div class='category_outter' id='category_outter_id'>
                <div class='category_inner_name'></div>
                <div class='category_inner'></div>
            </div>

            <div class='product_outter' id='product_outter_id'>
                <div class='product_inner_name'></div>
                <div class='product_inner'></div>
            </div>
        </div>
        <?php
            $num_all_categories = 0;
            $current_category_num = 0;
            $current_category_name = '';
            $num_all_products_category = 0;
            $current_product_num = 0;
            $current_product_name = '';

            foreach ($data as $array) {
                if (is_array($array)) {
                    $num_all_categories         =   $array['num_all_categories'];
                    $current_category_num       =   $array['current_category_num'];
                    $current_category_name      =   $array['current_category_name'];
                    $num_all_products_category  =   $array['num_all_products_category'];
                    $current_product_num        =   $array['current_product_num'];
                    $current_product_name       =   $array['current_product_name'];

                    if ($num_all_categories !== 0 &&
                        $current_category_num !== 0 &&
                        $num_all_products_category !== 0 &&
                        $current_product_num !== 0) {

                        $percent_category = intval(($current_category_num / $num_all_categories) * 100);

                        $percent_product = intval(($current_product_num / $num_all_products_category) * 100);

                        $print_immediate("
                            <script>
                                updateProgressBar('$current_category_name', '$current_product_name', '$percent_category', '$percent_product');
                            </script>
                        ");

                        if (!empty($current_product_name)) $print_immediate('Trenutni proizvod: '.$current_product_name.'<br />');

                        if (array_key_exists('success', $array)) $print_immediate($array['success'].'<br />');

                        if (array_key_exists('error', $data)) $print_immediate($array['error'].'<br />');
                    }
                } else {
                    $print_immediate($array);
                }

                $print_immediate("
                    <script>
                        start_scroll_down();
                    </script>
                ");
            }
        ?>
    </div>
</body>
</html>
