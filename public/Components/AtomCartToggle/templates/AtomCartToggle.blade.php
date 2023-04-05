<form
    action="cart_add"
    class="
        atom_cart_toggle
        atom_cart_toggle--{{ $button_mode }}
        atom_cart_toggle--{!!$js_template? '<%= product.id %>' : $product_id!!}
    "
    method="post"
>
    {!! $csrf_field !!}

    @if ($js_template)
        <%var index = cart.findIndex(function(row) {
            return row.product_id === product.id;
        });%>
        <%var quantity = index === -1 ? 0 : cart[index].quantity;%>
    @endif
    <label
        class   = "common_landings__visually_hidden"
        for     = "atom_cart_toggle__quantity--{!!$js_template? '<%= product.id %>' : $product_id!!}"
    >
        Količina
    </label>
    <input
        class   = "common_landings__visually_hidden atom_cart_toggle__quantity"
        {{-- data-quantity jer se value ne azurira u HTML-u, pa CSS ne detektuje promenu --}}
        data-quantity = "{!! $js_template ? '<%= quantity %>' : $quantity !!}"
        name    = "quantity"
        tabindex    = "-1"
        type    = "text"
        id      = "atom_cart_toggle__quantity--{!!$js_template? '<%= product.id %>' : $product_id!!}"
        value   = "{!! $js_template ? '<%= quantity %>' : $quantity !!}"
    >
    @if ($js_template)

    <%var in_cart = cart.findIndex(function(row) {
        return row.product_id === product.id;
    }) !== -1;%>
    @endif

    <input
        type  = "checkbox"
        class = "common_landings__visually_hidden atom_cart_toggle__in_cart"
        name  = "in_cart"
        id    = "atom_cart_toggle__in_cart--{!!$js_template? '<%= product.id %>' : $product_id!!}"
        @if ($js_template)
            <%= in_cart ? "checked" : "" %>
        @elseif ($in_cart === true)
            checked
        @endif
    />
    <label
        class   = "common_landings__visually_hidden atom_cart_toggle__in_cart__label"
        for     = "atom_cart_toggle__in_cart--{!!$js_template? '<%= product.id %>' : $product_id!!}"
    >
        <span class = "atom_cart_toggle__in_cart__text">
            Dodato u korpu
        </span>
        <span class = "atom_cart_toggle__not_in__cart_text">
            Dodaj u korpu
        </span>
    </label>
    <input
        name        = "product_id"
        type        = "hidden"
        value       = "{!!$js_template ? '<%= product.id %>' : $product_id!!}"
    />

    <button class = "atom_cart_toggle__change atom_cart_toggle__change--{{ $button_mode }}" type = "submit">
        <svg class="atom_cart__toggle_toggle atom_cart__toggle_toggle--cart_add atom_cart__toggle_toggle--{{ $button_mode }}">
            <use xlink:href="#atom_cart__toggle_add"></use>
        </svg>
        <span class="atom_cart__toggle_text atom_cart__toggle_text--{{ $button_mode }}">
            Dodaj u korpu
        </span>
    </button>

    <button class = "atom_cart_toggle__in_cart_already atom_cart_toggle__in_cart_already--{{ $button_mode }}" type="submit">
        <svg class="atom_cart__toggle_toggle atom_cart__toggle_toggle--cart_remove atom_cart__toggle_toggle--{{ $button_mode }}">
            <use xlink:href="#atom_cart__toggle_remove"></use>
        </svg>
        <span class="atom_cart__toggle_text atom_cart__toggle_text--{{ $button_mode }}">
            Dodato u korpu
        </span>
    </button>
</form>
