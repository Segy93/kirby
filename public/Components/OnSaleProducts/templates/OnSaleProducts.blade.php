<h1> Proizvodi na akciji </h1>
    @foreach($products as $product)
    	{!! $product_single__compact->renderHTML($product) !!}
    @endforeach
