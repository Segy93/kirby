<section class = "order__page">
    <table class = "order__wrapper">
        @if (!empty($orders))
            <thead>
                <th class = "order__table_heading">Datum</th>
                <th class = "order__table_heading order__table_heading--address">Mesto isporuke</th>
                <th class = "order__table_heading order__table_heading--status">Status</th>
                <th class = "order__table_heading order__table_heading--price">Ukupna cena</th>
                <th class = "order__table_heading order__table_heading--details">Detalji</th>
                <th class = "order__table_heading order__table_heading--controls">Kontrola narudžbine</th>
            </thead>
            <tbody class = "order__content">
                @foreach ($orders as $order)
                    <tr class = "order__single_wrapper order__single_wrapper--{{ $order->id }}">
                        <td class = "order__table_cell">
                            {{ $order->date_order->format('d.m.Y.') }}
                        </td>
                        <td class= "order__table_cell order__table_cell--address">
                            {{ $order->delivery_address->address }}
                        </td>
                        <td class = "order__table_cell order__table_cell--status">
                            @if ($order->last_update)
                                {{ $order->last_update->status }}
                            @else
                                Bez statusa
                            @endif
                        </td>
                        <td class = "order__table_cell order__table_cell--price">
                            <p class = "order__single_price">{{ number_format($order->total_price + $order->shipping_fee, 2, ',', '.') }}</p>
                        </td>
                        <td class = "order__table_cell order__table_cell--details">
                            @if (strpos($path, 'narudzbine') === false)
                                <a class = "order__details" href = "{{ $path }}/narudzbine/{{ $order->id }}">
                                    Detalji
                                </a>
                            @else
                                <a class = "order__details" href = "{{ $path }}/{{ $order->id }}">
                                    Detalji
                                </a>
                            @endif
                        </td>
                        @if ($order->last_update !== null)
                            <td class = "order__table_cell order__table_cell__buttons order__table_cell__buttons--{{ $order->id }}">
                                @if ($order->last_update)
                                    @if ($order->last_update->status === "nepotvrđeno")
                                        <input
                                            class           = "order_list__confirm_item"
                                            data-order-id   = "{{$order->id}}"
                                            type            = "button"
                                            value           = "Potvrdi"
                                        />
                                        <input
                                            class           = "order_list__return_item"
                                            data-order-id   = "{{$order->id}}"
                                            type            = "button"
                                            value           = "Vrati u korpu"
                                        />
                                        <input
                                            class           = "order_list__delete_item"
                                            data-order-id   = "{{$order->id}}"
                                            type            = "button"
                                            value           = "Otkaži"
                                        />
                                    @else
                                        Narudžbina je potvrđena!
                                    @endif
                                @endif
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</section>

