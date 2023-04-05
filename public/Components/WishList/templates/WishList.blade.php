<section class = "wishlist__page">
    <table class = "wishlist__wrapper">
        @if (!empty($wishlist))
            <thead>
                <th class = "wishlist__table_heading wishlist__table_heading--image">Slika</th>
                <th class = "wishlist__table_heading">Naziv proizvoda</th>
                <th class = "wishlist__table_heading wishlist__table_heading--price">Cena</th>
                <th class = "wishlist__table_heading wishlist__table_heading--add">Dodaj u korpu</th>
                <th class = "wishlist__table_heading wishlist__table_heading--delete">Ukloni</th>
            </thead>
            <tbody class = "wishlist__content">
                @foreach ($wishlist as $wish)
                    <tr class = "wishlist__single_wrapper wishlist__single_wrapper--{{$wish->id}}">
                        <td class = "wishlist__table_cell wishlist__table_cell--image">
                            <img
                                alt     = "Slika proizvoda {{ $wish->product->name }}"
                                class   = "wishlist__product_image"
                                src     = "{{$wish->product->images['thumbnail'][0]}}"
                            />
                        </td>
                        <td class= "wishlist__table_cell">
                            <div
                                class = "
                                    wishlist_stock
                                    @if($wish->product->in_stock)
                                        wishlist__in_stock
                                    @elseif ($wish->product->stock_warehouse > 0)
                                        wishlist__in_warehouse
                                    @else
                                        wishlist__on_demand
                                    @endif
                                "
                                tabindex="0"
                            >
                                <span class = "wishlist_tooltip">
                                    @if($wish->product->in_stock)
                                        Raspoloživo u radnji.
                                    @elseif ($wish->product->stock_warehouse > 0)
                                        Raspoloživo u magacinu.
                                    @else
                                        Nije raspoloživo, pozvati za dostupnost.
                                    @endif
                                </span>
                            </div>
                            <a
                                href  = "{{ $wish->product->url }}"
                                class =  "wishlist__product_link"
                            >
                                <p class = "wishlist__product_name">{{ $wish->product->name }}</p>
                            </a>

                        </td>
                        <td class = "wishlist__table_cell wishlist__table_cell--price">
                            <p class = "wishlist__single_price">{{ $wish->product->discount_format }} RSD</p>
                        </td>
                        <td class = "wishlist__table_cell wishlist__table_cell--add">
                            @if ($wish->product->in_stock)
                                <button
                                    class   = "wishlist__cart_add"
                                    data-id = "{{$wish->id}}"
                                    data-product_id = "{{$wish->product->id}}"
                                >
                                    <svg class = "wishlist__cart">
                                        <use xlink:href="#wishlist__cart"></use>
                                    </svg>
                                </button>
                            @else
                                Nije raspoloživo
                            @endif
                        </td>
                        <td class = "wishlist__table_cell wishlist__table_cell--delete">
                            <button class="wishlist__delete_item common_landings__button_remove" data-id = "{{$wish->id}}">Ukloni</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</section>
