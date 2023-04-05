<div class = "related_products">
    @foreach ($products as $product)
        <div class = "related_products__single">
    	    {!! $product_single__compact->renderHTML($product) !!}
        </div>
    @endforeach
</div>
