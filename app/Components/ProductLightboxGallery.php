<?php

namespace App\Components;

use App\Components\BaseComponent;

/**
 *
 */
class ProductLightboxGallery extends BaseComponent {
    protected $css       = [
        'ProductLightboxGallery/css/ProductLightboxGallery.css',
        'ProductLightboxGallery/css/lightbox.css',
    ];

    protected $js = [
        'ProductLightboxGallery/js/lightbox.js',
        'ProductLightboxGallery/js/ProductLightBoxGallery.js',
    ];
    public function renderHTML($product = null, $product_name = null) {
        $args = [
             'pictures'     =>  $product->images['thumbnail'],
             'full_images'  =>  $product->images['full_width'],
             'product_name' =>  $product_name,
        ];
        return view('ProductLightboxGallery/templates/ProductLightboxGallery', $args);
    }
}
