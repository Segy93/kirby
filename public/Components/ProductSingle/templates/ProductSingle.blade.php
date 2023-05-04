<section
    class   = "
            @if ($show_link)
                product_single__wrapper_category
            @endif
            product_single__wrapper product_single__wrapper--{!!$js_template ?  '<%=product.id %>' : $product->id!!}
        "
    data-id = "{!!$js_template ?  '<%=product.id %>' : $product->id!!}"
    itemscope itemtype="http://schema.org/Product"
>
    <meta itemprop = "sku" content = "{!!$js_template ?  '<%=product.artid %>' : $product->artid!!}" />

    <a
        aria-label      = "{!!$js_template ?  '<%=product.name %>' :$product->name!!}"
        class           = "product_single__image product_single__section"
        @if ($show_link)
            href        = "{!! $js_template ?  '<%=product.url %>' : $product->url !!}"
            tabindex    = "-1"
        @else
            href        = "{!! $js_template ?  '<%= product.images.thumbnail[0] %>' : $product->images['thumbnail'][0] !!}"
        @endif
        itemprop        = "url"
    >
        @if ($js_template)
            <%if (product.on_sale) {%>
                <span class = "product_single__on_sale">Akcija</span>
            <%}%>
        @elseif ($product->on_sale)
            <span class = "product_single__on_sale">Akcija</span>
        @endif
        @if ($js_template)
            <% if (product.presales) {%>
                <span class = "product_single__presales">Pretprodaja</span>
            <%}%>
        @elseif ($product->presales)
            <span class = "product_single__presales">Pretprodaja</span>
        @endif

        <img
            alt     = "Slika proizvoda {!!$js_template ?  '<%=product.name %>' :$product->name!!}"
            class   = "product_single__image_img"
            @if ($js_template)
                src = "<%= product.images.thumbnail[0] %>"
            @else
                src = "{!! $product->images['thumbnail'][0]!!}"
            @endif
            tabindex = "0"
            width    = "100"
        />
    </a>

    <div class = "product_single__product_info product_single__section">
        @if ($show_link)
            <a
                class = "product_single__product_name_link"
                href =  "{!! $js_template ?  '<%=product.url %>' : $product->url !!}"
                itemprop = "name"
            >
                {!!$js_template ?  '<%=product.name %>' :$product->name!!}
            </a>
        @else
            <h3
                class = "product_single__product_name_regular"
                itemprop = "name"
            >
                {!!$js_template ?  '<%=product.name %>' :$product->name!!}
            </h3>
        @endif
        @if ($js_template)
            <% var manufacturer_value = null; %>
        @else
            <?php $manufacturer_value = null; ?>
        @endif
        <ul class = "product_single__product_attributes" itemprop = "description">
            @if ($js_template)
                <%for (var j = 0, lj = product.attribute_values__category.length; j < lj; j++) { %>
                    <% var attribute = product.attribute_values__category[j]; %>
                    <% if (attribute.label === 'Proizvođač') { %>
                        <% manufacturer_value = attribute.value; %>
                    <%}%>
                    <li
                        class = "product_single__product_attribute"
                    >
                        <%= attribute.label %>: <%= attribute.value %>
                    </li>
                <%}%>
            @else
                @foreach($product->attribute_values('order_category') as $attribute_value)
                    @if( $attribute_value->attribute->label === 'Proizvođač')
                        <?php $manufacturer_value = $attribute_value->value; ?>
                    @endif
                    <li class = "product_single__product_attribute">{!! $attribute_value->attribute->label !!}: {!! $attribute_value->value !!}</li>
                @endforeach
            @endif
        </ul>
        @if ($js_template)
            <meta itemprop = "brand" content = "<%= manufacturer_value %>"/>
        @else
            <meta itemprop = "brand" content = "{!! $manufacturer_value !!}"/>
        @endif

        {!! $productRating->renderHTML($js_template ? null : $product->id) !!}
    </div>

    <div class = "product_single__control_info product_single__section">
        <section
            class    = "common_landings__visually_hidden"
            itemprop = "offers" itemscope itemtype="https://schema.org/Offer"
        >
            <meta itemprop    = "priceCurrency" content="RSD" />
            <meta itemprop    = "price" content="{!!$js_template ?  '<%=product.price_discount %>' :$product->price_discount!!}" />
            <meta itemprop    = "url" content = "{{$protocol}}://{{$_SERVER['HTTP_HOST']}}/{!!$js_template ?  '<%=product.url %>' :$product->url!!}" />
            <meta itemprop    = "priceValidUntil" content = "{{$tommorow}}" />
            @if($js_template)
                <% if (product.in_stock === true) { %>
                    <meta itemprop="availability" href="http://schema.org/InStock" content = "InStock" />
                <%} else {%>
                    <meta itemprop="availability" href="http://schema.org/OutOfStock" content = "OutOfStock"/>
                <%}%>
            @else
                @if ($product->in_stock === true)
                    <meta itemprop="availability" href="http://schema.org/InStock" content = "InStock" />
                @else
                    <meta itemprop="availability" href="http://schema.org/OutOfStock" content = "OutOfStock"/>
                @endif
            @endif
        </section>

        @if ($js_template)
            <% if (product.price_retail > 0 && product.price_discount > 0) {%>
                <% if (product.price_retail > product.price_discount) {%>
                    <p
                        class = "product_single__price_old"
                    >
                        <span
                        class       = "product_single__price_old_content">
                            &nbsp;<%= product.retail_format%> RSD &nbsp;
                        </span>
                    </p>
                <%}%>
                <p
                    class = "product_single__price product_single__price--discount"
                >
                    <span
                        class       = "product_single__price_content product_single__price_content-discount">
                            &nbsp; <%= product.discount_format%> RSD &nbsp;
                    </span>
                </p>
            <%}%>
        @else
            @if ($product->price_retail > 0 && $product->price_discount > 0)
                @if ($product->price_retail > $product->price_discount)
                    <p
                        class = "product_single__price_old"
                    >
                        <span
                            class       = "product_single__price_old_content">
                            &nbsp;{!! $product->retail_format !!} RSD &nbsp;
                        </span>
                    </p>
                @endif
                <p
                    class = "product_single__price product_single__price--discount"
                >
                    <span
                        class       = "product_single__price_content product_single__price_content-discount">
                        &nbsp;{!! $product->discount_format !!} RSD &nbsp;
                    </span>
                </p>
            @endif
        @endif
        @if ($js_template)
            <% if (product.price_retail > 0 && product.price_discount > 0) {%>
                <p
                    class = "product_single__text_note"
                >
                    *Cena važi za gotovinsko i virmansko plaćanje
                </p>
            <%}%>
        @else
            @if ($product->price_retail > 0 && $product->price_discount > 0)
                <p
                    class = "product_single__text_note"
                >
                    *Cena važi za gotovinsko i virmansko plaćanje
                </p>
            @endif
        @endif
        <p class = "product_single__artid">
            @if ($js_template)
                Šifra proizvoda: <%= product.artid %>
            @else
                Šifra proizvoda: {{$product->artid}}
            @endif
        </p>
        {{--
        @if ($js_template)
            <% if (product.in_stock === true) { %>
                <p class = "product_single__in_stock">
                    &bull; Raspoloživo
                </p>
            <%} else { %>
                <p class = "product_single__out_of_stock">
                    &bull; Nije raspoloživo
                </p>
            <% } %>
        @elseif ($product->in_stock === true)
            <p class = "product_single__in_stock">
                &bull; Raspoloživo
            </p>
        @else
            <p class = "product_single__out_of_stock">
                &bull; Nije raspoloživo
            </p>
        @endif
        --}}
        @if ($js_template)
            <% if (product.in_stock === true) { %>
                {!! $cartToggle->renderHTML(null) !!}
            <% }%>
        @elseif ($product->in_stock === true)
            {!! $cartToggle->renderHTML($product->id) !!}
        @endif

        {!! $wishListToggle->renderHTML($js_template ? null : $product->id) !!}
    </div>
</section>
