<form
    action = "configurator_add"
    class  = "
        configurator_add_button__toggle
        configurator_add_button__toggle--{!!$js_template? '<%= product.id %>' : $product_id!!}
    "
    method = "post"
>
    {!! $csrf_field !!}
    <input
        name    = "product_id"
        type    = "hidden"
        value   = "{!!$js_template? '<%= product.id %>' : $product_id!!}"
    />
    <input
        name    = "configuration_id"
        type    = "hidden"
        value   = "{{ $configuration_id }}"
    />

    <button class = "configurator_add_button__change" type = "submit">
        <span class = "configurator_add_button__text">
            Dodaj u konfigurator
        </span>
    </button>
</form>
