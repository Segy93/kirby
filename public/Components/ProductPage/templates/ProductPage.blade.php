e<section class = "product_page__wrapper product_page__wrapper--{{$product->id}}" data-id = "{{$product->id}}">
    {!! $productSingle->renderHTML($product) !!}
    {!! $gallery->renderHTML($product, $product->name) !!}
    {!! $infoShippingCost->renderHTML($product->price_discount) !!}

    <div class="product_page__experts">
        {!! $contact_experts->renderHTML() !!}
    </div>

    <div class="product_page__stock">
        {!! $stock->renderHTML($product) !!}
    </div>

    <div class="product_page__share">
        {!! $social_share->renderHTML() !!}
    </div>

    {!! $name->renderHTML($product->name) !!}
    {!! $banner->renderHTML() !!}
    {!! $tabs->renderHTML($child_args) !!}
</section>
