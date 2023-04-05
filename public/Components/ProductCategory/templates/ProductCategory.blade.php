<section class = "product_category" data-category-id = "{{$category->id}}">
    <div
        class="
            product_category__products
            product_category__products--list
        "
        data-category-id="{{$category->id}}"
        data-sale="{{$sale}}"
        data-category = "{{$url}}"
    >
        @if ($products)
            @foreach ($products as $index => $product)
                @if ($index % 6 === 0 && $index !== 0)
                    {!! $banner->renderHTML() !!}
                @endif
                {!! $product_single->renderHTML($product) !!}
            @endforeach
        @else
            <p class = "product_category__no_items">Nema proizvoda  u kategoriji</p>
        @endif
    </div>

    <div
        class="
            product_category__products
            product_category__products--grid
            common_landings__display_none
        "
        data-category-id="{{$category->id}}"
        data-sale = "{{$sale}}"
    >
        @if ($products)
            @foreach ($products as $product)
                @if ($index % 6 === 0 && $index !== 0)
                    {!! $banner->renderHTML() !!}
                @endif
                {!! $product_compact->renderHTML($product) !!}
            @endforeach
        @else
            <p class = "product_category__no_items">Nema proizvoda  u kategoriji</p>
        @endif
    </div>

    <button
        type    = "button"
        class   = "product_category__load_more"
    >
        Učitaj još
    </button>
</section>

<script type = "text/html" id = "product_category__tmpl_list" nonce="{{$_SESSION['token']}}">
    <%var banners_count_list  = banners.length; %>
    <%var banners_index_list = last_printed;%>
    <%var printed_now   = 0;%>
    <% if (products.length > 0) { %>
        <%for (var i = 0, l = products.length; i < l; i++) {%>
            <% var product = products[i]; %>
            <% if ( (total_printed_list + i) % 5 === 0 && (total_printed_list + i) !== 0 && banners_count_list  > printed_now) {%>
                <% var banner = banners[banners_index_list++]; %>
                {!! $banner->renderHTML(true) !!}
                <% printed_now++;%>

                <%if (banners_index_list === banners_count_list ) {%>
                    <%banners_index_list = 0;%>
                <%}%>
            <%}%>
            {!! $product_single->renderHTML() !!}
        <%}%>
        <% var last = products[products.length - 1]; %>
    <% } else { %>
        <p>Nema proizvoda  u kategoriji</p>
    <%}%>
</script>


<script type = "text/html" id = "product_category__tmpl_grid" nonce="{{$_SESSION['token']}}">
    <%var banners_count_grid = banners.length; %>
    <%var banners_index_grid = last_printed;%>
    <%var printed_now   = 0;%>
    <% if(products.length > 0) { %>
        <%for(var i = 0, l = products.length; i < l; i++) {%>
            <% var product = products[i]; %>
            <% if ( (total_printed_grid + i) % 6 === 0 && (total_printed_grid + i) !== 0 && banners_count_grid > printed_now) {%>
                <% var banner = banners[banners_index_grid++]; %>
                {!! $banner->renderHTML(true) !!}
                <% printed_now++;%>

                <%if (banners_index_grid === banners_count_grid) {%>
                    <%banners_index_grid = 0;%>
                <%}%>
            <%}%>
            {!! $product_compact->renderHTML() !!}
        <%}%>
        <% var last = products[products.length - 1]; %>
    <% } else { %>
        <p>Nema proizvoda  u kategoriji</p>
    <%}%>
</script>
