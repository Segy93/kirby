<button
    class           = "btn btn-warning vert-align admin_orders__status_history"
    data-target     = "#admin_orders__status_history"
    data-toggle     = "modal"
    data-order_id    = "{{ $order->id }}"
    type            = "button"
>
    Istorija statusa
</button>
<form class = "admin_order__status" data-order_id = "{{$order->id}}">
    {!! $csrf_field !!}
    <h3 class = "admin_order__status_heading">Promenite status</h3>

    <label class = "admin_order__status_label" for="admin_order__status_change">Izaberite status</label>
    <select
        id = "admin_order__status_change"
        class = "admin_order__status_change form-control"
        name = "status"
        required = "required"
    >
        @for($i = 0, $l = count($statuses); $i < $l; $i++)
            <option value = "{{$statuses[$i]}}" {{ $i === $order->last_update->status_code? 'selected' : '' }}> {{$statuses[$i]}} </option>
        @endfor
    </select>


    <label for="admin_order__status_comment" class = "d-print-none">Unesite komentar (Obavezno)</label>
    <textarea
        class       = "admin_order__status_comment form-control d-print-none"
        id          = "admin_order__status_comment"
        name        = "message"
        required    = "required"
        max-length  = "65535"
    ></textarea>


    <label for="admin_order__status_notify" class = "d-print-none">Obavesti korisnika?</label>

    <input
        type    = "checkbox"
        id      = "admin_order__status_notify"
        class   = "admin_order__status_notify d-print-none"
        name    = "notify"
    />
    <br />

    <input
            id      = "admin_order__status_submit"
            type    = "submit"
            class   = "btn btn-default d-print-none"
            value   = "Promeni"
    />
</form>
<div class = "admin_order__status_sent_notify"></div>
<div class = "admin_order__note">
    <h3>Napomena korisnika</h3>
    <p>{{$order->note === null ? 'Nema poruke korisnika' : $order->note}}</p>
</div>
<table class = "table">
    <thead>
        <th class = "admin_order_products__table_heading">Međuzbir</th>
        <th class = "admin_order_products__table_heading">Dostava</th>
        <th class = "admin_order_products__table_heading">Ukupno</th>
        <th class = "admin_order_products__table_heading">Način plaćanja</th>
        <th class = "admin_order_products__table_heading">Naručeno</th>
        <th class = "admin_order_products__table_heading">Datum dostave</th>
        <th class = "admin_order_products__table_heading admin_order_products__table_heading--link">Narudžbenica</th>
    </thead>
    <tbody>
        <tr>
            <td class = "admin_order_products__table_cell admin_order__product_price">
                {{$order->total_price_formatted}} RSD
            </td>
            <td class = "admin_order_products__table_cell admin_order__product_shipping">
                {{$order->shipping_fee_formatted}} RSD
            </td>
            <td class = "admin_order_products__table_cell admin_order__product_price_total">
                {{number_format($order->total_price + $order->shipping_fee, 2, ',', '.')}} RSD
            </td>
            <td class = "admin_order_products__table_cell admin_order__product_shipping">
                {{$order->payment_method->method}}
            </td>
            <td class = "admin_order_products__table_cell admin_order__product_date">
                {{$order->date_order->format('m.d.Y H:i:s')}}
            </td>
            <td class = "admin_order_products__table_cell admin_order__product_date">
                {{$order->date_delivery->format('m.d.Y H:i:s')}}
            </td>
            <td class = "admin_order_products__table_cell admin_order_products__table_cell--link admin_order__product_link">
                <a 
                    class = "btn btn-success vert-align admin_order__add_product d-print-none"
                    href  = "/admin/narudzbenica/{{ $order->id }}"
                >
                    Narudžbenica
                </a>
            </td>
        </tr>
    </tbody>
<table>
<button
    class           = "btn btn-primary vert-align admin_order__add_product d-print-none"
    data-target     = "#admin_order__add_product"
    data-toggle     = "modal"
    data-order_id   = "{{ $order->id }}"
    type            = "button"
>
    Dodaj proizvod
</button>
<table class="admin_order__products_table table table-striped table-sm table-bordered table-hover" data-order-id = "{{$order->id}}" >
    <thead>
            <th class="col-md-1 d-print-none">Obriši</th>
            <th class="col-md-1">Količina</th>
            <th class="col-md-1">Naziv proizvoda</th>
            <th class="col-md-1">Šifra</th>
            <th class="col-md-1">Težina</th>
            <th class="admin_order_products__table_heading_price col-md-1">Cena</th>
    </thead>

    <tbody id="admin_order__products_content">
    </tbody>
</table>

<script type="text/html" id="admin_order__products_tmpl">
    <%for( var i = 0, l = order.order_products.length; i < l; i++ ) {%>
    <% var product = order.order_products[i] %>
        <tr>
            <td class = "d-print-none">
                <button
                    class                    = "btn btn-danger vert-align admin_order__products_delete"
                    data-target              = "#admin_order__modal_delete"
                    data-toggle              = "modal"
                    data-order-product-id    = "<%= product.id %>"
                    type                     = "button"
                >
                    Obriši
                </button>
            </td>
            <td>
                <input
                    class                   = "admin_order__product_quantity"
                    data-order_id           = "<%= order.id %>"
                    data-product_id         = "<%= product.product_id %>"
                    min                     = "1"
                    oninput                 = "this.value = Math.abs(this.value)"
                    type                    = "number"
                    value                   = "<%= product.quantity %>"
                />
            </td>
            <td>
               <a
                    class = "admin_order__products_product__url"
                    href  = "/<%= product.product.url %>"
                >
                    <%= product.product.name %>
                </a>
            </td>
            <td>
                <%= product.product.artid %>
            </td>
            <td>
                <%= product.product.weight !== null ? product.product.weight : '0' %> g
            </td>
            <td class = "admin_order_products__price">
                <%= product.price_format %> RSD
            </td>
        </tr>
    <% } %>
</script>
