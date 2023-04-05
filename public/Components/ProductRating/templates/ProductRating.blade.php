<section class = "product_rating__container">
    <form action= "">
        {!! $csrf_field !!}
        <section
            class    = "common_landings__visually_hidden"
            itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating"
        >
            <span itemprop = "ratingValue">{!!$js_template ? '<%= product.rating %>': $rating!!}</span>
            <span itemprop = "reviewCount">{!!$js_template ? 
                '<%= product.rating_count === 0 ? 1 : product.rating_count %>': 
                $rating_count === 0 ? 1 : $rating_count!!}
            </span>
        </section>
        <section
            class			= "product_rating__list"
            data-rating 	= "{!!$js_template ? '<%= product.rating %>' : $rating!!}"
            data-product_id = "{!!$js_template ? '<%= product.id %>' : $product_id!!}"
            itemscope
            itemtype        = " http://schema.org/Rating"
        >
            <meta itemprop = "worstRating" content = "1">
            <meta itemprop = "bestRating"  content = "5">
            <div class = "product_rating__current_rating">
                <span
                    class = "
                        product_rating__value
                        product_rating__value--{!!$js_template ? '<%= product.id %>' : $product_id!!}
                    "
                    itemprop = "ratingValue"
                >
                    {!!$js_template ? '<%= product.rating %>': $rating!!}
                </span>
            </div>

            <ul class = "product_rating__star_container">
                @for ($i = 1; $i <= 5; $i++)
                    <li
                        class = "
                            product_rating__star
                            @if ($js_template)
                                <%if(product.rating >={{$i}}) {%>
                                    product_rating__star--selected
                                <%}%>
                            @elseif ($rating >= $i)
                                product_rating__star--selected
                            @endif
                        "
                        data-product_id = "{!!$js_template ? '<%= product.id %>' : $product_id!!}"
                        data-rating = "{{$i}}"
                    >
                        <input
                            aria-label = "Daj proizvodu ocenu {{$i}}"
                            type    = "radio"
                            name    = "product_rating"
                            class   = "product_rating__star_radio"
                            value   = "{{$i}}"
                        />
                    </li>
                @endfor
            </ul>
        </section>
    </form>
</section>
