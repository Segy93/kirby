<form
    action="compare_add"
    class="
        compare_toggle
        compare_toggle--{!!$js_template? '<%= product.id %>' : $product_id!!}
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
        <%var in_compare = compare.findIndex(function(row) {
            return row.id === product.id;
        }) !== -1;%>
    @endif
    <input
        type     = "checkbox"
        id       = "compare_toggle__in_compare--{!!$js_template? '<%= product.id %>' : $product_id!!}"
        class    = "common_landings__visually_hidden compare_toggle__in_compare"
        name     = "in_compare"
        tabindex = "-1"

        @if ($js_template)
            <%= in_compare ? "checked" : "" %>
        @elseif ($in_compare === true)
            checked
        @endif
    />

    <label
        class   = "common_landings__visually_hidden atom_compare_toggle__in_compare__label"
        for     = "compare_toggle__in_compare--{!!$js_template? '<%= product.id %>' : $product_id!!}"
    >
        <span class = "atom_compare_toggle__in_compare__text">
            Izbaci iz upoređivanja
        </span>
        <span class = "atom_compare_toggle__not_in__compare_text">
            Uporedi
        </span>
    </label>
    <button class = "compare_toggle__change" type = "submit">
        <svg class = "compare_products__toggle_toggle compare_products__toggle_toggle--plus">
            <use xlink:href="#compare_products__toggle_plus"></use>
        </svg>
        <span class = "compare_products__toggle_text">Dodaj za poređenje</span>
    </button>

    <button class = "compare_toggle__in_compare_already" type = "submit">
        <svg class="compare_products__toggle_toggle compare_products__toggle_toggle--minus">
            <use xlink:href="#compare_products__toggle_minus"></use>
        </svg>
        <span class = "compare_products__toggle_text">Dodato za poređenje</span>
    </button>
</form>
