<table
    class           = "admin_order_addresses__table table table-striped table-sm table-bordered table-hover d-print-block"
    data-order-id   = "{{$order->id}}"
>
    <thead>
        @if ($permissions['order_update'])
            <th class="col-md-1 admin_order_addresses__table_heading">
                <label>
                    Podaci
                </label>
            </th>
            <th class="col-md-1">
                <label for = "admin_order_addresses__delivery_address_dropdown">
                    Informacije za slanje
                </label>
            </th>
            <th class="col-md-1">
                <label for = "admin_order_addresses__billing_address_dropdown">
                    Informacije za plaćanje
                </label>
            </th>
        @endif
    </thead>
    <tbody id="admin_order_addresses__content">
        <tr class = "admin_order__addresses_name">
            <td>
                Ime i prezime
            </td>
            <td class = "admin_order__delivery_name">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->contact_name}}
                @else
                    {{$order->user->name}}
                @endif
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->contact_surname}}
                @else
                    {{$order->user->surname}}
                @endif
            </td>

            <td class = "admin_order__billing_name">
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->contact_name}}
                @else
                    {{$order->user->name}}
                @endif
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->contact_surname}}
                @else
                    {{$order->user->surname}}
                @endif
            </td>
        </tr>
        <tr
            class = "
                admin_order__addresses_company
                @if ($order->delivery_address->address_type === 'shop'
                && $order->billing_address->address_type === 'shop')
                    common_landings__display_none
                @endif
            "
        >
            <td>
                Naziv firme
            </td>
            <td class = "admin_order__delivery_company">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->company}}
                @endif
            </td>
            <td class = "admin_order__billing_company">
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->company}}
                @endif
            </td>
        </tr>
        <tr
            class = "
                admin_order__addresses_pib
                @if ($order->delivery_address->address_type === 'shop'
                && $order->billing_address->address_type === 'shop')
                    common_landings__display_none
                @endif
            "
        >
            <td>
                PIB
            </td>
            <td class = "admin_order__delivery_pib">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->pib}}
                @endif
            </td>
            <td class = "admin_order__billing_pib">
                @if($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->pib}}
                @endif
            </td>
        </tr>
        <tr
            class = "
                admin_order__addresses_address
                @if ($order->delivery_address->address_type === 'shop'
                && $order->billing_address->address_type === 'shop')
                    common_landings__display_none
                @endif
            "
        >
            <td>
                Adresa
            </td>
            <td  class = "admin_order__delivery_address">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->address}}
                @endif
            </td>
            <td class = "admin_order__billing_address">
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->address}}
                @endif
            </td>
        </tr>

        <tr
            class = "
                admin_order__addresses_city
                @if ($order->delivery_address->address_type === 'shop'
                && $order->billing_address->address_type === 'shop')
                    common_landings__display_none
                @endif
            "
        >
            <td>
                Grad
            </td>
            <td class = "admin_order__delivery_city">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->city}}
                @endif
            </td>
            <td class = "admin_order__billing_city">
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->city}}
                @endif
            </td>
        </tr>
        <tr class = "admin_order__addresses_phone">
            <td>
                Telefon
            </td>
            <td class = "admin_order__delivery_phone">
                @if ($order->delivery_address->address_type !== 'shop')
                    {{$order->delivery_address->phone_nr}}
                @else
                    {{$order->user->phone_nr}}
                @endif
            </td>
            <td class = "admin_order__billing_phone">
                @if ($order->billing_address->address_type !== 'shop')
                    {{$order->billing_address->phone_nr}}
                @else
                    {{$order->user->phone_nr}}
                @endif
            </td>
        </tr>
        <tr>
            <td>
                Email
            </td>
            <td>
                {{$order->user->email}}
            </td>
            <td>
                {{$order->user->email}}
            </td>
        </tr>
        <tr class = "d-print-none">
            <td class = "admin_order_addresses__delivery_type">
                @if ($order->delivery_address->address_type !== 'shop')
                    Dostava na kućnu adresu
                @else
                    Dostava u radnji
                @endif
            </td>
            <td>
                <form
                    id              = "admin_order_addresses__delivery_address_form"
                    data-order_id   = "{{$order->id}}"
                    data-type       = "delivery"
                    method          = "POST"
                >
                    {!! $csrf_field !!}
                    <select
                        class   = "form-control"
                        id      = "admin_order_addresses__delivery_address_dropdown"
                        name    = "address_id"
                    >
                        <optgroup label = "Radnje">
                            @foreach ($shops as $shop)
                                <option
                                    data-type = "shop"
                                    value = "{{$shop->id}}"
                                    {{ $order->delivery_address->id === $shop->id ? 'selected' : '' }}
                                >
                                    {{ $shop->address->address }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup class = "admin_order__delivery_addresses" label = "Adrese">
                            @foreach ($user_addresses as $address)
                                <option
                                    data-type = "user"
                                    value = "{{$address->id}}"
                                    {{ $order->delivery_address->id === $address->id ? 'selected' : '' }}
                                >
                                    {{ $address->contact_name }} | {{ $address->address }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    <button
                        class           = "btn btn-warning vert-align admin_order__info
                                            {{ $order->delivery_address->address_type === 'shop' ? 'common_landings__display_none' : '' }}"
                        data-target     = "#admin_order__delivery_info"
                        data-toggle     = "modal"
                        data-order-id   = "{{ $order->id }}"
                        id              = "admin_order_addresses__button_delivery_open"
                        type            = "button"
                    >
                        Otvori
                    </button>
                    <input
                        type    = "submit"
                        class   = "btn btn-primary"
                        value   = "Promeni"
                    />
                </form>
            </td>
            <td>
                <form
                    id              = "admin_order_address__billing_address__form"
                    method          = "POST"
                    data-order_id   = "{{$order->id}}"
                    data-type       = "billing"
                >
                    {!! $csrf_field !!}
                    <select
                        class   = "form-control"
                        id      = "admin_order_addresses__billing_address_dropdown"
                        name    = "address_id"
                    >
                        <optgroup label = "Radnje">
                            @foreach ($shops as $shop)
                                <option
                                    data-type = "shop"
                                    value = "{{$shop->id}}"
                                    {{ $order->billing_address->id === $shop->id ? 'selected' : '' }}
                                >
                                    {{ $shop->address->address }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup class = "admin_order__billing_addresses" label = "Adrese">
                            @foreach ($user_addresses as $address)
                                <option
                                    data-type = "user"
                                    value = "{{$address->id}}"
                                    {{ $order->billing_address->id === $address->id ? 'selected' : '' }}
                                >
                                    {{ $address->contact_name }} | {{ $address->address }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                        <button
                            class           = "btn btn-warning vert-align admin_order__info
                                                {{ $order->billing_address->address_type === 'shop' ? 'common_landings__display_none' : '' }}"
                            data-target     = "#admin_order__billing_info"
                            data-toggle     = "modal"
                            data-order-id   = "{{ $order->id }}"
                            id              = "admin_order_addresses__button_billing_open"
                            type            = "button"
                        >
                            Otvori
                        </button>

                        <input
                            type    = "submit"
                            class   = "btn btn-primary"
                            value   = "Promeni"
                        />
                </form>
            </td>
        </tr>
    </tbody>
</table>
<div class = "admin_order__addresses_notify"></div>
