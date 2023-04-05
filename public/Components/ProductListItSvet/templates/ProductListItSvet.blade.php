<?php
    /** @var \App\Models\Product[] $products */
    // Nije lepo uvuceno jer se generise plain text odgovor
?>
@foreach ($products as $product)
{{ $product->artid }};{!! $product->name !!};{{ $product->price_discount }};{{ $product->url_absolute }}
@endforeach
