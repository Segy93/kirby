@if ($banner !== null)
    <a href = "{{$banner->link}}">
        <img
            alt     = "{{$banner->title}}"
            class   = "search_list__banner"
            data-id = "{{$banner->id}}"
            src     = "uploads_static/originals/{{$banner->image}}"
        />
    </a>
@endif

<div class = "search_list">
    @foreach ($products as $product)
        {!! $product_single->renderHTML($product) !!}
    @endforeach
</div>

<button
    class       = "search_list__load_more"
    data-search = "{{ $search }}"
    type        = "button"
>
    Učitaj još
</button>

<script type = "text/html" id = "search_list__tmpl" nonce="{{$_SESSION['token']}}">
    <% if (products.length > 0) { %>
        <%for (var i = 0, l = products.length; i < l; i++) {%>
            <% var product = products[i]; %>
            {!! $product_single->renderHTML() !!}
        <%}%>
        <% var last = products[products.length - 1]; %>
    <% } else { %>
        <p>Nema proizvoda</p>
    <%}%>
</script>
