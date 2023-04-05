<section class = "order_details">
    <h1 class = "order_details__title">Narudžbina {{ $order->id }}</h1>
    <table class = "order_details__products_table">
        <thead>
            <th class = "order_details__table_heading order_details__table_heading--image">Slika</th>
            <th class = "order_details__table_heading order_details__table_heading--artid">Šifra artikla</th>
            <th class = "order_details__table_heading">Ime proizvoda</th>
            <th class = "order_details__table_heading order_details__table_heading--quantity">Količina</th>
            <th class = "order_details__table_heading order_details__table_heading--price">Cena</th>
        </thead>
        <tbody class = "order_details__content">
            @foreach ($order->order_products as $order_product)
                <tr class="order_details__table_row">
                    <td class = "order_details__table_cell order_details__table_cell--image">
                        <img
                            alt     = "Slika proizvoda {{ $order_product->product->name }}"
                            class   = "order_details__product_image"
                            src     = "{{$order_product->product->images['thumbnail'][0]}}"
                        />
                    </td>
                    <td class = "order_details__table_cell order_details__table_cell--artid">
                        {{ $order_product->product->artid }}
                    </td>
                    <td class = "order_details__table_cell order_details__table_cell--name">
                        <p
                            class = "order_details__product_name"
                        >
                            <a
                                href  =  "{{$order_product->product->url}}"
                                class =  "order_details__product_link"
                            >
                                {{ $order_product->product->name }}
                            </a>
                        </p>
                    </td>
                    <td class = "order_details__table_cell order_details__table_cell--quantity">
                        {{ $order_product->quantity }} 
                    </td>
                    <td class = "order_details__table_cell order_details__table_cell--price">
                        <p class = "order_details__price">
                            {{ $order_product->price_format }}
                        </p>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class = "order_details__total">
            <tr class = "order_details__table_row order_details__table_row--sum">
                <td class = "order_details__table_cell">
                    Međuzbir:
                </td>
                <td class = "order_details__table_cell order_details__table_cell--prices" colspan = "4">
                    <span class="order_details__total_price">
                        {{ $order->total_price_formatted }}
                    </span>
                </td>
            </tr>
            <tr class = "order_details__table_row order_details__table_row--shipping">
                <td class = "order_details__table_cell">
                    Poštarina:
                </td>
                <td class = "order_details__table_cell order_details__table_cell--prices" colspan = "4">
                    <span class="order_details__total_price">
                        {{ $order->shipping_fee_formatted }}
                    </span>
                </td>
            </tr>
            <tr class = "order_details__table_row order_details__table_row--total">
                <td class = "order_details__table_cell">
                    Ukupno:
                </td>
                <td class = "order_details__table_cell order_details__table_cell--prices" colspan = "4">
                    <span class="order_details__total_price">
                        {{ number_format($order->total_price + $order->shipping_fee, 2, ',', '.') }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>
    <h2 class = "order_details__info_title">Info o narudžbini</h2>
    <table class = "order_details__info_table">
        <tr class = "order_details__info_table_row">
            <th class="order_details__info_table_header">
                Adresa isporuke
            </th>
            <td class="order_details__info_table_cell">
                @if ($order->delivery_address)
                {{ $order->delivery_address->address }}
                @else
                    Adresa nije pronadjena
                @endif
            </td>
        </tr>
        <tr class = "order_details__info_table_row">
            <th class="order_details__info_table_header">
                Adresa plaćanja
            </th>
            <td class="order_details__info_table_cell">
                @if ($order->billing_address)
                {{ $order->billing_address->address }}
                @else
                    Adresa nije pronadjena
                @endif
            </td>
        </tr>
        @if($order->user->name !== null && $order->user->surname !== null)
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header">
                    Ime
                </th>
                <td class="order_details__info_table_cell">
                    {{ $order->user->name }}
                </td>
            </tr>
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header">
                    Prezime
                </th>
                <td class="order_details__info_table_cell">
                    {{ $order->user->surname }}
                </td>
            </tr>
        @endif
        @if($order->user->phone_nr !== null)
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header">
                    Telefon
                </th>
                <td class="order_details__info_table_cell">
                    {{ $order->user->phone_nr }}
                </td>
            </tr>
        @endif
        <tr class = "order_details__info_table_row">
            <th class="order_details__info_table_header">
                Datum naručivanja
            </th>
            <td class="order_details__info_table_cell">
                @if ($order->date_order)
                {{ $order->date_order->format('d.m.Y.') }}
                @endif
            </td>
        </tr>
        <tr class = "order_details__info_table_row">
            <th class="order_details__info_table_header">
                Datum isporuke
            </th>
            <td class="order_details__info_table_cell">
                @if ($order->date_delivery)
                {{ $order->date_delivery->format('d.m.Y.') }}
                @endif
            </td>
        </tr>
        <tr class = "order_details__info_table_row">
            <th class="order_details__info_table_header">
                Način plaćanja
            </th>
            <td class="order_details__info_table_cell">
                {{ $order->payment_method->method }}
            </td>
        </tr>
        @if ($order->last_update !== null)
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header">
                    Status
                </th>
                <td class="order_details__info_table_cell order_details__info_table_cell--status">
                    @if ($order->last_update)
                    {{ $order->last_update->status }}
                    @endif
                </td>
            </tr>
        @endif
        @if ($order->last_update)
            @if ($order->last_update->status === "nepotvrđeno")
                <tr class = "order_details__info_table_row order_details__info_table_row--buttons">
                    <th class="order_details__info_table_header">
                        Kontrola narudžbine
                    </th>
                    <td class="order_details__info_table_cell order_details__info_table_cell--buttons">
                        <input
                            class           = "order_details__confirm_item"
                            data-order-id   = "{{$order->id}}"
                            type            = "button"
                            value           = "Potvrdi narudžbinu"
                        />
                        <input
                            class           = "order_details__return_item"
                            data-order-id   = "{{$order->id}}"
                            type            = "button"
                            value           = "Vrati u korpu"
                        />
                        <input
                            class           = "order_details__delete_item"
                            data-order-id   = "{{$order->id}}"
                            type            = "button"
                            value           = "Otkaži narudžbinu"
                        />
                    </td>
                </tr>
            @endif
        @endif
    </table>

    <h2 class = "order_details__info_title">Istorijat statusa</h2>
    @foreach ($order->updates as $update)
        <table class = "order_details__info_table">
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header order_details__info_table_header--status" colspan = "2">
                    {{ $update->date->setTimezone(new \DateTimeZone('Europe/Belgrade'))->format('d.m.Y. H:i') }}
                </th>
            </tr>
            <tr class = "order_details__info_table_row">
                <th class="order_details__info_table_header">
                    Status
                </th>
                <td class="order_details__info_table_cell">
                    {{ $update->status }}
                </td>
            </tr>
            @if ($update->comment_user !== null)
                <tr class = "order_details__info_table_row">
                    <th class="order_details__info_table_header">
                        Komentar korisnika
                    </th>
                    <td class="order_details__info_table_cell">
                        {{ $update->comment_user }}
                    </td>
                </tr>
            @endif
            @if ($update->comment_admin !== null)
                <tr class = "order_details__info_table_row">
                    <th class="order_details__info_table_header">
                        Komentar admina
                    </th>
                    <td class="order_details__info_table_cell">
                        {{ $update->comment_admin }}
                    </td>
                </tr>
            @endif
        </table>
    @endforeach
    <div class = "order_details__page_break">
        <h2 class = "order_details__info_title">Podaci za isporuku</h2>
        <table class = "order_details__info_table">
            @if ($order->delivery_address)
                @if ($order->delivery_address->address_type === 'shop')
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Adresa isporuke
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class  = "order_details__info_link"
                                href   = "https://www.google.rs/maps/place/{{$order->delivery_address->address}}"
                                rel    = "noopener"
                                target = "_blank"
                            >
                                {{ $order->delivery_address->address }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Email
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class = "order_details__info_link"
                                href  = "mailto:{{$order->delivery_address->email}}"
                            >
                                {{ $order->delivery_address->email }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Fax
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class = "order_details__info_link"
                                href  = "tel:{{preg_replace('/^0/', '+381', $order->delivery_address->fax)}}"
                            >
                                {{ $order->delivery_address->fax }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Radno vreme
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ str_replace(' \n', ', ', $order->delivery_address->open_hours) }}
                        </td>
                    </tr>
                @else
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Adresa isporuke
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->delivery_address->address }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Ime
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->delivery_address->contact_name }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Prezime
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->delivery_address->contact_surname }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Telefon
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->delivery_address->phone_nr }}
                        </td>
                    </tr>
                    @if ($order->delivery_address->company !== null)
                        <tr class = "order_details__info_table_row">
                            <th class="order_details__info_table_header">
                                Naziv firme
                            </th>
                            <td class="order_details__info_table_cell">
                                {{ $order->delivery_address->company }}
                            </td>
                        </tr>
                    @endif
                    @if ($order->delivery_address->pib !== null)
                        <tr class = "order_details__info_table_row">
                            <th class="order_details__info_table_header">
                                PIB
                            </th>
                            <td class="order_details__info_table_cell">
                                {{ $order->delivery_address->pib }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endif
        </table>
        <h2 class = "order_details__info_title">Podaci za plaćanje</h2>
        <table class = "order_details__info_table">
            @if ($order->billing_address)
                @if ($order->billing_address->address_type === 'shop')
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Adresa plaćanja
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class  = "order_details__info_link"
                                href   = "https://www.google.rs/maps/place/{{$order->billing_address->address}}"
                                rel    = "noopener"
                                target = "_blank"
                            >
                                {{ $order->billing_address->address }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Email
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class = "order_details__info_link"
                                href  = "mailto:{{$order->billing_address->email}}"
                            >
                                {{ $order->billing_address->email }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Fax
                        </th>
                        <td class="order_details__info_table_cell">
                            <a
                                class = "order_details__info_link"
                                href  = "tel:{{preg_replace('/^0/', '+381', $order->billing_address->fax)}}"
                            >
                                {{ $order->billing_address->fax }}
                            </a>
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Radno vreme
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ str_replace(' \n', ', ', $order->billing_address->open_hours) }}
                        </td>
                    </tr>
                @else
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Adresa plaćanja
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->billing_address->address }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Ime
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->billing_address->contact_name }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Prezime
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->billing_address->contact_surname }}
                        </td>
                    </tr>
                    <tr class = "order_details__info_table_row">
                        <th class="order_details__info_table_header">
                            Telefon
                        </th>
                        <td class="order_details__info_table_cell">
                            {{ $order->billing_address->phone_nr }}
                        </td>
                    </tr>
                    @if ($order->billing_address->company !== null)
                        <tr class = "order_details__info_table_row">
                            <th class="order_details__info_table_header">
                                Naziv firme
                            </th>
                            <td class="order_details__info_table_cell">
                                {{ $order->billing_address->company }}
                            </td>
                        </tr>
                    @endif
                    @if ($order->billing_address->pib !== null)
                        <tr class = "order_details__info_table_row">
                            <th class="order_details__info_table_header">
                                PIB
                            </th>
                            <td class="order_details__info_table_cell">
                                {{ $order->billing_address->pib }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endif
        </table>
        @if ($order->note !== null)
            <h2 class = "order_details__info_title">Napomena</h2>
            <p class = "order_details__note">
                {{ $order->note }}
            </p>
        @endif
    </div>
</section>
