<article class = "configurator_setup__wrapper">
    <section class = "configurator_setup__container">
        <img
            alt    = "Asus logo"
            class  = "configurator_setup__image"
            height = "32"
            src    = "/Components/ConfiguratorSetup/img/asus.png"
            width  = "150"
        >
        <h1 class = "configurator_setup__heading">
            <label
                class = "configurator_setup__name_label"
                for   = "configurator_setup__name_input"
            >
                Daj naziv svojoj konfiguraciji
            </label>
        </h1>
        <form
            class  = "configurator_setup__form"
            @if ($configuration_name === null)
                action = "konfigurator-kreiraj"
                id     = "configurator_setup__form_create"
            @else
                action = "konfigurator-izmeni"
                id     = "configurator_setup__form_edit"
            @endif
            method     = "post"
        >
            {!! $csrf_field !!}
            <input
                class = "configurator_setup__id"
                name  = "configuration_id"
                type  = "hidden"
                value = "{{ $configuration_id }}"
            />
            <input
                class       = "configurator_setup__name_input"
                id          = "configurator_setup__name_input"
                name        = "name"
                placeholder = "Naziv konfiguracije"
                required
                type        = "text"
                value       = "{{ $configuration_name_session }}"
            />
            @if ($name_taken)
                <p
                    class = "configurator_setup__error"
                    role  = "alert"
                >
                    {{ $name_taken }}
                </p>
            @endif
        </form>
        <?php $case_power = null; ?>
        <?php $case_category_id = null; ?>
        @foreach ($categories as $category)
            @if ($category !== null)
                <?php $current_config = $categories_config[$category->name_import]; ?>
                <section class = "configurator_setup__component_single">
                    <?php $type_exists = array_key_exists($category->name_import, $selected_products); ?>
                    <img
                        alt   = "{{ $current_config['message'] }}"
                        class = "
                            configurator_setup__category_image
                            configurator_setup__category_image--{{ $category->id }}
                            @if ($type_exists && !$current_config['allow_multiple'])
                                common_landings__hidden
                            @endif
                        "
                        src   = "/Components/ConfiguratorSetup/img/{{ $current_config['img'] }}"
                    />
                    <h3
                        class = "
                            configurator_setup__instruction_headings
                            configurator_setup__instruction_headings--{{ $category->id }}
                            @if ($type_exists && !$current_config['allow_multiple'])
                                common_landings__hidden
                            @endif
                        "
                    >
                        {{ $current_config['message'] }}
                    </h3>
                    <a
                        aria_label   = "{{ $current_config['message'] }}"
                        class        = "
                            configurator_setup__add_button
                            configurator_setup__add_button--{{$category->id}}
                            @if ($type_exists && !$current_config['allow_multiple'])
                                common_landings__hidden
                            @endif
                        "
                        href = "{{ route('configuratorAdd', ['category_url' => $category->url, 'name' => $configuration_name_url ]) }}"
                    >
                        Izaberi
                    </a>
                    @if (array_key_exists($category->name_import, $selected_products))
                        @foreach ($selected_products[$category->name_import] as $product)
                            @foreach ($errors as $error)
                                @if (array_key_exists('product_id', $error) && $error['product_id'] === $product['product']->id)
                                    <p
                                        class = "
                                            configurator_setup__error
                                            configurator_setup__error--{{ $product['product']->id }}
                                        "
                                        role  = "alert"
                                    >
                                        {{ $error['message'] }}
                                    </p>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                    @if (array_key_exists($category->name_import, $selected_products))
                        {!! $products_list->renderHTML($selected_products[$category->name_import]) !!}
                    @endif
                    @if ($category->name_import === 'CASE' && array_key_exists($category->name_import, $selected_products))
                        @foreach ($selected_products[$category->name_import] as $product)
                            @foreach ($product['product']->attribute_values__order_product as $attribute_value)
                                @if ($attribute_value->attribute->label === 'Napajanje')
                                    <?php $case_power = $attribute_value->value; ?>
                                    <?php $case_category_id = $category->id; ?>
                                @endif
                            @endforeach
                        @endforeach
                    @endif
                    @if ($category->name_import === 'PSU' && $case_power !== null && $case_power !== 'Bez napajanja')
                        <p
                            class = "
                                configurator_setup__warning
                                configurator_setup__warning--{{ $case_category_id }}
                            "
                            role  = "alert"
                        >
                            Kućište sadrži napajanje od
                            <span class = "configurator_setup__power">
                                {{ $case_power }}
                            </span>
                        </p>
                    @endif
                </section>
            @endif
        @endforeach
        <section class = "configurator_setup__total_price">
            <div class = "configurator_setup__total_price_name">
                Ukupna cena konfiguracije
            </div>
            <div class = "configurator_setup__total_price_count">
                {{ $total_price }} RSD
            </div>
        </section>
        <section class = "configurator_setup__button_wrapper">
            <button
                class = "configurator_setup__button_create"
                @if ($configuration_name === null)
                    form  = "configurator_setup__form_create"
                @else
                    form  = "configurator_setup__form_edit"
                @endif
                type  = "submit"
            >
                @if ($configuration_name === null)
                    Sačuvaj konfiguraciju
                @else
                    Promeni naziv
                @endif
            </button>
            <a
                class = "configurator_setup__button_order"
                href  = "{{ $configuration_name !== null ? route('checkoutConfigurator', ['name' => urlencode($configuration_name)]) : route('checkoutConfigurator') }}"
            >
                Naruči
            </a>
            @if ($configuration_id !== null)
                <form
                    action = "konfiguracija-brisanje"
                    class  = "configurator_setup__delete_form"
                    method = "post"
                >
                    {!! $csrf_field !!}
                    <input name = "configuration_id" type = "hidden" value = "{{ $configuration_id }}"/>
                    <button
                        class    = "configurator_setu__delete_button common_landings__button_remove"
                        type     = "submit"
                    >
                        Obriši
                    </a>
                </form>
            @endif
        </section>
    </section>
</article>
