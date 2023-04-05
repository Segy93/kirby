<form
    action="wishlist_add"
    class="
        atom_wishlist_toggle
        atom_wishlist_toggle--{{ $button_mode }}
        atom_wishlist_toggle--{!!$js_template? '<%= product.id %>' : $product_id!!}
    "
    method="post"
>
    {!! $csrf_field !!}
    <input
        name    = "product_id"
        type    = "hidden"
        value   = "{!!$js_template? '<%= product.id %>' : $product_id!!}"
    />

    @if ($js_template)

        <%var in_wishlist = wishlist.findIndex(function(row) {
            return row.product_id === product.id;
        }) !== -1;%>
    @endif

    <input
        type  = "checkbox"
        class = "common_landings__visually_hidden atom_wishlist_toggle__in_wishlist"
        name  = "in_wishlist"
        id    = "atom_wishlist_toggle__in_wishlist--{!!$js_template? '<%= product.id %>' : $product_id!!}"
        @if ($js_template)
            <%= in_wishlist ? "checked" : "" %>
        @elseif ($in_wishlist === true)
            checked
        @endif
    />

    <label
        class   = "common_landings__visually_hidden atom_wishlist_toggle__in_wishlist__label"
        for     = "atom_wishlist_toggle__in_wishlist--{!!$js_template? '<%= product.id %>' : $product_id!!}"
    >
        <span class = "atom_wishlist_toggle__in_wishlist__text">
            Dodato u listu želja
        </span>
        <span class = "atom_wishlist_toggle__not_in__wishlist_text">
            Dodaj u listu želja
        </span>
    </label>

    <button class = "atom_wishlist_toggle__change atom_wishlist_toggle__change--{{ $button_mode }}" type = "submit">
        <svg class="atom_wishlist__toggle_toggle atom_wishlist__toggle_toggle--plus atom_wishlist__toggle_toggle--{{ $button_mode }}">
            <use xlink:href="#atom_wishlist__toggle_plus"></use>
        </svg>
        <span class = "atom_wishlist__toggle_text atom_wishlist__toggle_text--{{ $button_mode }}">
            Dodaj u listu želja
        </span>
    </button>

    <button class = "atom_wishlist_toggle__in_wishlist_already atom_wishlist_toggle__in_wishlist_already--{{ $button_mode }}" type = "submit">
        <svg class="atom_wishlist__toggle_toggle atom_wishlist__toggle_toggle--minus atom_wishlist__toggle_toggle--{{ $button_mode }}">
            <use xlink:href="#atom_wishlist__toggle_minus"></use>
        </svg>
        <span class = "atom_wishlist__toggle_text atom_wishlist__toggle_text--{{ $button_mode }}">
            Dodato u listu želja
        </span>
    </button>
</form>
