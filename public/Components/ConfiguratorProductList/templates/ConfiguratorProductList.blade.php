<section class = "configurator_product_list__page">
    <table class = "configurator_product_list__wrapper">
        <tbody class = "configurator_product_list__content">
            @foreach ($products as $product)
                @for ($i = 0; $i < $product['quantity']; $i++)
                    <tr
                        class = "
                            configurator_product_list__single_wrapper
                            configurator_product_list__single_wrapper_category_id--{{$product['product']->category_id}}
                            configurator_product_list__single_wrapper--{{$product['product']->id}}
                        "
                    >
                        <td class = "configurator_product_list__table_cell configurator_product_list__table_cell--image">
                            <img
                                alt     = "Slika proizvoda {{ $product['product']->name }}"
                                class   = "configurator_product_list__product_image"
                                src     = "{{$product['product']->images['thumbnail'][0]}}"
                            />
                        </td>
                        <td class= "configurator_product_list__table_cell">
                            <div
                                class = "
                                    configurator_product_list_stock
                                    @if($product['product']->in_stock)
                                        configurator_product_list__in_stock
                                    @elseif ($product['product']->stock_warehouse > 0)
                                        configurator_product_list__in_warehouse
                                    @else
                                        configurator_product_list__on_demand
                                    @endif
                                "
                                tabindex="0"
                            >
                                <span class = "configurator_product_list_tooltip">
                                    @if ($product['product']->in_stock)
                                        Raspoloživo u radnji.
                                    @elseif ($product['product']->stock_warehouse > 0)
                                        Raspoloživo u magacinu.
                                    @else
                                        Nije raspoloživo, pozvati za dostupnost.
                                    @endif
                                </span>
                            </div>
                            <a
                                href  = "{{ $product['product']->url }}"
                                class =  "configurator_product_list__product_link"
                            >
                                <p class = "configurator_product_list__product_name">{{ $product['product']->name }}</p>
                            </a>

                        </td>
                        <td class = "configurator_product_list__table_cell configurator_product_list__table_cell--price">
                            <p class = "configurator_product_list__single_price">{{ $product['product']->discount_format }} RSD</p>
                        </td>
                        <td class = "configurator_product_list__table_cell configurator_product_list__table_cell--delete">
                            <form
                                action = "konfigurator-brisanje-proizvoda"
                                class  = "configurator_product_list__delete_item_form"
                                method = "post"
                            >
                                {!! $csrf_field !!}
                                <input name = "product_id" type = "hidden" value = "{{ $product['product']->id }}"/>
                                <input name = "category_id" type = "hidden" value = "{{ $product['product']->category_id }}"/>
                                <input name = "configuration_name" type = "hidden" value = "{{ $configuration_name }}"/>
                                <button
                                    class           = "configurator_product_list__delete_item common_landings__button_remove"
                                    type            = "submit"
                                >
                                    Ukloni
                                </button>
                            </form>
                        </td>
                    </tr>
                @endfor
                <tr
                    class = "
                        configurator_product_list__single_wrapper
                        configurator_product_list__single_wrapper_category_id_specs--{{$product['product']->category_id}}
                        configurator_product_list__single_wrapper_specs--{{$product['product']->id}}
                    "
                >
                    <td class = "configurator_product_list__single_wrapper_cell_specs" colspan = "4">
                        <input
                            class = "configurator_product_list__specs_show common_landings__hidden"
                            id    = "configurator_product_list__specs_show--{{ $product['product']->id }}"
                            type  = "checkbox"
                        />
                        <label
                            class = "configurator_product_list__specs_label"
                            for   = "configurator_product_list__specs_show--{{ $product['product']->id }}"
                        >
                            Specifikacija
                        </label>
                        <table class="configurator_product_list__table_specs" itemscope itemtype="http://schema.org/PropertyValue">
                            @foreach ($product['product']->attribute_values__order_product as $attribute_value)
                                @if ($attribute_value->attribute->label !== 'Link proizvođača' || ($product['product']->link === null && $attribute_value->attribute->label === 'Link proizvođača'))
                                    <tr class = "configurator_product_list__table_specs_row">
                                        <th class="configurator_product_list__table_specs_header" itemprop="name">{{ $attribute_value->attribute->label }}</th>
                                        <td class="configurator_product_list__table_specs_cell" itemprop="value">
                                            @if ($attribute_value->attribute->label === "Link proizvođača")
                                                <a class = "configurator_product_list__link" href="{{ $attribute_value->value }}" rel="noopener" target = "_blank">
                                                    {{ $attribute_value->value }}
                                                </a>
                                            @else
                                                {{ $attribute_value->value }}
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            @if ($product['product']->shouldShowDimensions())
                                <tr class = "configurator_product_list__table_specs_row">
                                    <th class="configurator_product_list__table_specs_header" itemprop="name">Dimenzije</th>
                                    <td class="configurator_product_list__table_specs_cell" itemprop="value">{{ $product['product']->getDimensionsDisplay() }}</td>
                                </tr>
                            @endif

                            @if ($product['product']->shouldShowWeight())
                                <tr class = "configurator_product_list__table_specs_row">
                                    <th class="configurator_product_list__table_specs_header" itemprop="name">Težina</th>
                                    <td class="configurator_product_list__table_specs_cell" itemprop="value">{{ $product['product']->getWeightDisplay() }}</td>
                                </tr>
                            @endif
                            @if ($product['product']->ean !== null)
                                <tr class = "configurator_product_list__table_specs_row">
                                    <th class="configurator_product_list__table_specs_header" itemprop="name">EAN</th>
                                    <td class="configurator_product_list__table_specs_cell" itemprop="value">{{ $product['product']->ean }}</td>
                                </tr>
                            @endif
                            @if ($product['product']->link !== null)
                                <tr class = "configurator_product_list__table_specs_row">
                                    <th class="configurator_product_list__table_specs_header" itemprop="name">Link proizvođača</th>
                                    <td class="configurator_product_list__table_specs_cell" itemprop="value">
                                        <a class = "configurator_product_list__table_product_link" href="{{ $product['product']->link }}" rel="noopener" target = "_blank">{{ $product['product']->link }}</a>
                                    </td>
                                </tr>
                            @endif
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</section>
