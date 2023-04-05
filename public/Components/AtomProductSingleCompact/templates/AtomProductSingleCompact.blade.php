<article class = "product_single__compact" itemscope itemtype = "http://schema.org/Product">
    <meta itemprop = "sku" content = "{!!$js_template ?  '<%=product.artid %>' : $product->artid!!}" />
    <section class = "product_single__compact_information">
        <a
            href    = "{!!$js_template ? '<%=product.url %>' : $product->url!!}"
            class   = "product_single__compact_url"
        >
            <h4 class = "product_single__compact_headline" itemprop = "name">
                {!!$js_template ? '<%=product.name %>' : $product->name!!}
            </h4>
        </a>

        <a
            class = "product_single__compact_image"
            tabindex    = "-1"
            href = "{!!$js_template ? '<%=product.url %>' : $product->url!!}"
        >
            <span class = "product_single_compact__on_sale">
                @if ($js_template)
                    <%=product.on_sale ? "Akcija" : "" %>
                @elseif ($product->on_sale)
                    Akcija
                @endif
            </span>
            <span class = "product_single_compact__presales">
                @if ($js_template)
                    <%=product.presales ? "Pretprodaja" : "" %>
                @elseif ($product->presales)
                    Pretprodaja
                @endif
            </span>
            <img
                alt         = "Slika proizvoda {!!$js_template ?  '<%=product.name %>' :$product->name!!}"
                class       = "product_single__compact_headine__image"
                itemprop    = "image"
                tabindex    = "-1"
                @if ($js_template)
                    src = "<%= product.images.thumbnail[0] %>"
                @else
                    src = "{!! $product->images['thumbnail'][0]!!}"
                @endif
            />
        </a>

        <div class = "product_single__compact_price" itemprop = "offers" itemscope itemtype="https://schema.org/Offer">
            <meta itemprop    = "priceCurrency" content="RSD" />
            <meta itemprop    = "price" content="{!!$js_template ? '<%=product.price_discount %>' : $product->price_discount!!}" />
            <meta itemprop    = "url" content = "{{$protocol}}://{{$_SERVER['HTTP_HOST']}}/{!!$js_template ? '<%=product.url %>' : $product->url!!}" />
            <meta itemprop    = "priceValidUntil" content = "{{$tommorow}}" />

            @if ($js_template)
                <% if (product.in_stock) { %>
                    <meta itemprop="availability" href="http://schema.org/InStock" content = "InStock" />
                <%} else {%>
                    <meta itemprop="availability" href="http://schema.org/OutOfStock" content = "OutOfStock" />
                <%}%>
            @else
                @if ($product->in_stock)
                    <meta itemprop="availability" href="http://schema.org/InStock" content = "InStock"/>
                @else
                    <meta itemprop="availability" href="http://schema.org/OutOfStock" content = "OutOfStock"/>
                @endif
            @endif

            @if ($js_template)
                <% if (product.price_retail > product.price_discount) {%>
                    <p class = "product_single__compact_price__old">
                        <%= product.retail_format%> RSD
                    </p>
                <%}%>

                <p class = "product_single__compact_price_discount">
                    <%= product.discount_format%> RSD
                </p>
            @else
                @if ($product->price_retail > $product->price_discount)
                    <p class = "product_single__compact_price__old">
                        {!! $product->retail_format !!} RSD
                    </p>
                @endif

                <p class = "product_single__compact_price_discount">
                    {!! $product->discount_format !!} RSD
                </p>
            @endif
        </div>
        @if ($js_template)
                <% var manufacturer_value = null;%>
        @else
            <?php $manufacturer_value = null; ?>
        @endif
        <ul class = "product_single__compact_attributes" itemprop = "description">
            @if ($js_template)
                <%for (var j = 0, lj = product.attribute_values__category.length; j < lj; j++) { %>
                    <% var attribute = product.attribute_values__category[j]; %>
                    <li
                        class       = "product_single__compact_atributes__single"
                    >
                        <% if (attribute.label === 'Proizvođač') { %>
                            <% manufacturer_value = attribute.value; %>
                        <%}%>
                        <div
                            class = "common_landings__visually_hidden"
                            itemprop    = "additionalProperty"
                            itemscope
                            itemtype    = "http://schema.org/PropertyValue"
                        >
                            <meta itemprop = "name" content = "<%= attribute.label %>"/>
                            <% var changed_value =  attribute.value.replace(/\"/g, "");%>
                            <meta itemprop = "value" content = "<%= changed_value %>"/>
                        </div>
                        <%= attribute.label %>: <%= attribute.value %>
                    </li>
                <%}%>
            @else

            @foreach ($product->attribute_values__category as $attribute_value)
                <li
                    class       = "product_single__compact_atributes__single"
                >
                    @if ($attribute_value->attribute->label === 'Proizvođač')
                        <?php $manufacturer_value = $attribute_value->value; ?>
                    @endif
                    <div
                        class = "common_landings__visually_hidden"
                        itemprop    = "additionalProperty"
                        itemscope
                        itemtype    = "http://schema.org/PropertyValue"
                    >
                        <meta itemprop = "name" content = "{!! $attribute_value->attribute->label !!}"/>
                        <meta itemprop = "value" content = "{!! preg_replace('/"/', '', $attribute_value->value)!!}"/>
                    </div>
                    {!! $attribute_value->attribute->label !!}: {!! $attribute_value->value !!}
                </li>
                @endforeach
            @endif
        </ul>
        @if ($js_template)
            <meta itemprop = "brand" content = "<%= manufacturer_value %>"/>
        @else
            <meta itemprop = "brand" content = "{!! $manufacturer_value !!}"/>
        @endif
    </section>

    <div class = "product_single__compact_rating">
        {!! $product_rating->renderHTML($js_template ?  null : $product->id) !!}
    </div>
</article>