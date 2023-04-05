<section class = "cart">
    <?php $total = 0; ?>
    @if ($errors !== null)
        <ul role = "alert">
            @foreach($errors as $error)
                <li>
                    {{$error}}
                </li>
            @endforeach
        </ul>
    @endif
    <table class="cart__list">
        @if (!empty($cart))
            <thead>
                <th class = "cart_single__table_heading cart_single__table_heading--image">Slika</th>
                <th class = "cart_single__table_heading">Proizvodi</th>
                <th class = "cart_single__table_heading cart_single__table_heading--quantity">Količina</th>
                <th class = "cart_single__table_heading cart_single__table_heading--price">Cena</th>
                <th class = "cart_single__table_heading cart_single__table_heading--delete">Ukloni</th>
            </thead>

            <tbody class = "cart__content">
                @foreach ($cart as $item)
                    <tr class="cart_single__wrapper cart_single__wrapper--{{ $item->id }}">
                        <td class = "cart_single__table_cell cart_single__table_cell--image">
                            <img
                                alt     = "Slika proizvoda {{ $item->product->name }}"
                                class   = "cart_single__product_image"
                                src     = "{{$item->product->images['thumbnail'][0]}}"
                            />
                        </td>

                        <td class = "cart_single__table_cell cart_single__table_cell--name">
                            <p
                                class = "cart_single__product_name"
                            >
                                <a
                                    class =  "cart_single__product_link"
                                    href  =  "{{$item->product->url}}"
                                >
                                    {{ $item->product->name }}
                                </a>
                            </p>
                        </td>

                        <td class = "cart_single__table_cell cart_single__table_cell--quantity">
                            <form
                                action          = "changeCart"
                                class           = "cart__form_update cart__form_update--{{$item->product->id}}"
                                data-price      = "{{ $item->product->price_discount }}"
                                data-quantity   = "{{ $item->quantity }}"
                                method          = "post"
                            >
                                {!! $csrf_field !!}

                                <input
                                    class   = "cart__change_quantity"
                                    name    = "quantity"
                                    type    = "number"
                                    min     = "1"
                                    value   = "{{ $item->quantity }}"
                                    data-id = "{{$item->product->id}}"
                                />

                                <input
                                    name = "product_id"
                                    type = "hidden"
                                    value = "{{$item->product->id}}"
                                />

                                <button
                                    class   = "common_landings__visually_hidden cart_form__update_submit cart_form__update_submit--{{$item->product->id}}"
                                    data-id = "{{$item->product->id}}"
                                    type    = "submit"
                                >
                                    Promeni
                                </button>
                            </form>
                        </td>

                        <td class = "cart_single__table_cell cart_single__table_cell--price">
                            <?php $total +=  $item->product->price_discount * $item->quantity; ?>
                            <p class = "cart_single__price">{{ $item->product->discount_format }}</p>
                        </td>

                        <td class = "cart_single__table_cell cart_single__table_cell--delete">
                            <form
                                action          = "removeCart"
                                class           = "cart__form_delete"
                                data-price      = "{{ $item->product->price_discount }}"
                                data-quantity   = "{{ $item->quantity }}"
                                method          = "post"
                            >
                                {!! $csrf_field !!}
                                <input
                                    name    = "id"
                                    type    = "hidden"
                                    value   = "{{$item->id}}"
                                />
                                <input
                                    name = "product_id_delete"
                                    type = "hidden"
                                    data-quantity   = "{{ $item->quantity }}"
                                    value = "{{$item->product->id}}"
                                />
                                <button
                                    class="cart__delete_item common_landings__button_remove"
                                    type = "submit"
                                >
                                    Ukloni
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                <tr class = "cart_single__wrapper">
                    <td colspan="3" class="cart__total" id="cart__total">
                        Međuzbir: <span class = "cart__total_price" data-total = "{{ $total }}"> {{number_format($total, 2, ',', '.')}}</span>
                    </td>

                    <td colspan="2" class="cart__order">
                        <a class="cart__link_order" href = "/kasa">Naruči</a>
                    </td>
                </tr>
            </tbody>
        @endif
    </table>
</section>
<script type = "text/html" id = "cart_tmpl">
    <%if (cart !== ""){%>
        <thead>
            <th class = "cart_single__table_heading cart_single__table_heading--image">Slika</th>
            <th class = "cart_single__table_heading">Proizvodi</th>
            <th class = "cart_single__table_heading cart_single__table_heading--quantity">Količina</th>
            <th class = "cart_single__table_heading cart_single__table_heading--price">Cena</th>
            <th class = "cart_single__table_heading cart_single__table_heading--delete">Ukloni</th>
        </thead>
        <% total = 0 %>
        <tbody class = "cart__content">
            <% cart.forEach(function(item) {%>
                <tr class="cart_single__wrapper cart_single__wrapper--<%= item.id %>">
                    <td class = "cart_single__table_cell cart_single__table_cell--image">
                        <img
                            alt     = "Slika proizvoda <%= item.product.name %>"
                            class   = "cart_single__product_image"
                            src     = "<%= item.product.images['thumbnail'][0] %>"
                        />
                    </td>

                    <td class = "cart_single__table_cell cart_single__table_cell--name">
                        <p
                            class = "cart_single__product_name"
                        >
                            <a
                                class =  "cart_single__product_link"
                                href  =  "<%= item.product.url %>"
                            >
                                <%= item.product.name %>
                            </a>
                        </p>
                    </td>

                    <td class = "cart_single__table_cell cart_single__table_cell--quantity">
                        <form
                            action          = "changeCart"
                            class           = "cart__form_update cart__form_update--<%= item.product.id %>"
                            data-price      = "<%= item.product.price_discount %>"
                            data-quantity   = "<%= item.quantity %>"
                            method          = "post"
                        >
                            {!! $csrf_field !!}
                            
                            <input
                                class   = "cart__change_quantity"
                                name    = "quantity"
                                type    = "number"
                                min     = "1"
                                value   = "<%= item.quantity %>"
                                data-id = "<%= item.product.id %>"
                            />

                            <input
                                name = "product_id"
                                type = "hidden"
                                value = "<%= item.product.id %>"
                            />

                            <button
                                class   = "common_landings__visually_hidden cart_form__update_submit cart_form__update_submit--<%= item.product.id%>"
                                data-id = "<%= item.product.id %>"
                                type    = "submit"
                            >
                                Promeni
                            </button>
                        </form>
                    </td>

                    <td class = "cart_single__table_cell cart_single__table_cell--price">
                        <% total += item.product.price_discount * item.quantity; %>
                        <p class = "cart_single__price"><%= item.product.discount_format %></p>
                    </td>

                    <td class = "cart_single__table_cell cart_single__table_cell--delete">
                        <form
                            action          = "removeCart"
                            class           = "cart__form_delete"
                            data-price      = "<%= item.product.price_discount %>"
                            data-quantity   = "<%= item.quantity %>"
                            method          = "post"
                        >
                            {!! $csrf_field !!}
                            <input
                                name    = "id"
                                type    = "hidden"
                                value   = "<%= item.id %>"
                            />
                            <input
                                name = "product_id_delete"
                                type = "hidden"
                                data-quantity   = "<%= item.quantity %>"
                                value = "<%= item.product.id %>"
                            />
                            <button
                                class="cart__delete_item common_landings__button_remove"
                                type = "submit"
                            >
                                Ukloni
                            </button>
                        </form>
                    </td>
                </tr>
            <%});%>

            <%
                var formatPrice = function(price) {
                    if ("Intl" in window) {
                        var options = {
                            style: "currency",
                            minimumFractionDigits: 2,
                            currency: "RSD",
                        };
                        return new Intl.NumberFormat('sr-RS', options).format(price);
                    } else {
                        return price;
                    }
                };
            %>
            <tr class = "cart_single__wrapper">
                <td colspan="3" class="cart__total" id="cart__total">
                    Međuzbir: <span class = "cart__total_price" data-total = "<%= total %>"><%= formatPrice(total)%></span>
                </td>

                <td colspan="2" class="cart__order">
                    <a class="cart__link_order" href = "/kasa">Naruči</a>
                </td>
            </tr>
        </tbody>
    <%}%>
</script>
