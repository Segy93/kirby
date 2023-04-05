<div class = "compared_product_page__wrapper">
    @if (!empty($products))
        <table class= "compared_product_page__table">
            <thead class= "compared_product_page__table_header">
                <tr class= "compared_product_page__table_row">
                    <th class="compared_product_page__table_heading" rowspan="2">Ime proizvoda</th>
                    @foreach ($products as $product)
                        <th
                            class = "compared_product_page__details compared_product_page__details--{{ $product->id }}
                                    compared_product_page__table_heading"
                        >
                            <div class="compared_product_page__table_heading_div">
                                <a class= "compared_product_page__table_heading_link" href= "{{ $product->url }}">
                                    {{ $product->name }}
                                </a>
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                <tr class= "compared_product_page__table_row">
                    <td class= "compared_product_page__table_data">Slika proizvoda</td>
                    @foreach ($products as $product)
                        <td
                        class = "compared_product_page__details
                                    compared_product_page__details--{{ $product->id }}
                                    compared_product_page__table_data"
                        >
                            <a class = "compared_product_page__image" href = "{{$product->url}}">
                                <img
                                    alt     = "{{ $product->name }}"
                                    src     = "{{ $product->images['thumbnail'][0] }}"
                                    width   = "100"
                                />
                            </a>
                        </td>
                    @endforeach
                </tr>
                <tr class= "compared_product_page__table_row">
                    <td class= "compared_product_page__table_data">Cena bez popusta</td>
                    @foreach ($products as $product)
                        <td
                            class = "compared_product_page__details
                                    compared_product_page__details--{{ $product->id }}
                                    compared_product_page__details--price
                                    compared_product_page__table_data"
                        >
                            {{ $product->retail_format }}
                        </td>
                    @endforeach
                </tr>
                <tr class= "compared_product_page__table_row">
                    <td class= "compared_product_page__table_data">Cena sa popustom</td>
                    @foreach ($products as $product)
                        <td
                            class = "compared_product_page__details
                                    compared_product_page__details--{{ $product->id }}
                                    compared_product_page__details--price
                                    compared_product_page__table_data"
                        >
                            {{ $product->discount_format }}
                        </td>
                    @endforeach
                </tr>

                @foreach ($attributes as $label)
                    <tr class= "compared_product_page__table_row">
                        <td class = "compared_product_page__table_data">{{ $label }}</td>
                        @foreach ($data as $data_product)
                            @if ($label !== "Link proizvođača")
                                <td
                                    class = "compared_product_page__details
                                            compared_product_page__details--{{ $data_product['product_id'] }}
                                            compared_product_page__table_data"
                                >
                                    {{ $data_product[$label] }}
                                </td>
                            @else
                                <td
                                    class = "compared_product_page__details
                                            compared_product_page__details--{{ $data_product['product_id'] }}
                                            compared_product_page__table_data"
                                >
                                    <a class="compared_product_page__table_data_link" href="{{ $data_product[$label] }}" rel = "noopener" target = "_blank">
                                        Proizvođač
                                    </a>
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
                <tr class= "compared_product_page__table_row">
                    <td
                        class = "compared_product_page__details
                                compared_product_page__table_data"
                    >
                        &nbsp
                    </td>
                    @foreach ($products as $product)
                        <td
                            class = "compared_product_page__details
                                        compared_product_page__details--{{ $product->id }}
                                        compared_product_page__details--buttons
                                        compared_product_page__table_data"
                        >
                            @if ($product->in_stock === true)
                                {!! $cartToggle->renderHTML($product->id) !!}
                            @endif

                            {!! $wishListToggle->renderHTML($product->id) !!}

                            <button class= "compared_product_page__delete_item" data-id = "{{ $product->id }}" type = "submit">
                                Izbaci
                            </button>
                        </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    @endif
</div>
