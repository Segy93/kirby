<section class = "checkout_table__page">
    <h1 class = "checkout_table__title">Narudžbenica {{ $order->id }}</h1>
    <p class = "checkout_table__headnote">
        Vaša narudžbina je skoro kompletna. Molimo Vas prekontrolišite upisane podatke i ako su svi podaci tačni pritisnite "Potvrdi". 
        Možete iskoristiti dugme nazad i eventualno napraviti izmene u Vašoj narudžbini ako je neophodno.
    </p>
    <table class = "checkout_table">
        <tr class = "checkout_table__row">
            <th class = "checkout_table__heading" colspan = "3">
                Sadržaj narudžbine
            </th>
        </tr>
        <tr class = "checkout_table__row">
            <th class = "checkout_table__headers">Proizvod</th>
            <th class = "checkout_table__headers checkout_table__headers--quantity">Količina</th>
            <th class = "checkout_table__headers checkout_table__headers--price">Cena</th>
        </tr>
        @foreach($products as $product)
            <tr class = "checkout_table__row">
                <td class = "checkout_table__cell checkout_table__cell--name">
                    {{$product->product->name}}
                    <span class = "checkout_table__quantity_print">
                        &times; {{$product->quantity}}
                    </span>
                </td>
                <td class = "checkout_table__cell checkout_table__cell--quantity">&times; {{$product->quantity}}</td>
                <td class = "checkout_table__cell checkout_table__cell--price">{{number_format($product->quantity * $product->price, 2, ',', '.')}} RSD</td>
            </tr>
        @endforeach
        <tr class = "checkout_table__row">
            <th class = "checkout_table__heading" colspan = "3">
                Informacije o korisniku
            </th>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Email</th>
            <td class = "checkout_table__cell" colspan = "2">{{$user_info->email}}</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Korisničko ime</th>
            <td class = "checkout_table__cell" colspan = "2">{{$user_info->username}}</td>
        </tr>
        @if ($user_info->name !== null)
            <tr>
                <th class = "checkout_table__headers">Ime</th>
                <td class = "checkout_table__cell" colspan = "2">{{$user_info->name}}</td>
            </tr>
        @endif
        @if ($user_info->surname !== null)
            <tr>
                <th class = "checkout_table__headers">Prezime</th>
                <td class = "checkout_table__cell" colspan = "2">{{$user_info->surname}}</td>
            </tr>
        @endif
        @if ($user_info->phone_nr !== null)
            <tr>
                <th class = "checkout_table__headers">Telefon</th>
                <td class = "checkout_table__cell" colspan = "2">{{$user_info->phone_nr}}</td>
            </tr>
        @endif
        <tr>
            <th class = "checkout_table__heading" colspan = "3">
                Informacije o dostavi
            </th>
        </tr>
            @if($order->delivery_address->address_type  !== 'shop')
                <tr>
                    <th class = "checkout_table__headers">Ime</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->contact_name}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Prezime</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->contact_surname}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Telefon</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->phone_nr}}</td>
                </tr>
                @if ($order->delivery_address->company !== null)
                    <tr>
                        <th class = "checkout_table__headers">Naziv firme</th>
                        <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->company}}</td>
                    </tr>
                @endif
                @if ($order->delivery_address->pib !== null)
                    <tr>
                        <th class = "checkout_table__headers">PIB</th>
                        <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->pib}}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <th class = "checkout_table__headers">Email</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->email}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Fax</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->fax}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Radno vreme</th>
                    <td class = "checkout_table__cell" colspan = "2">{{str_replace(' \n', ', ', $order->delivery_address->open_hours)}}</td>
                </tr>
            @endif
            <tr>
                <th class = "checkout_table__headers">Adresa</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->address}}</td>
            </tr>
            <tr>
                <th class = "checkout_table__headers">Poštanski broj</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->postal_code}}</td>
            </tr>
            <tr>
                <th class = "checkout_table__headers">Grad</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->delivery_address->city}}</td>
            </tr>
        <tr class = "checkout_table__row">
            <th class = "checkout_table__heading" colspan = "3">
                Informacije o naplati
            </th>
        </tr>
            @if($order->billing_address->address_type  !== 'shop')
                <tr>
                    <th class = "checkout_table__headers">Ime</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->contact_name}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Prezime</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->contact_surname}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Telefon</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->phone_nr}}</td>
                </tr>
                @if ($order->billing_address->company !== null)
                    <tr>
                        <th class = "checkout_table__headers">Naziv firme</th>
                        <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->company}}</td>
                    </tr>
                @endif
                @if ($order->billing_address->pib !== null)
                    <tr>
                        <th class = "checkout_table__headers">PIB</th>
                        <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->pib}}</td>
                    </tr>
                @endif
            @else
                <tr>
                    <th class = "checkout_table__headers">Email</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->email}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Fax</th>
                    <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->fax}}</td>
                </tr>
                <tr>
                    <th class = "checkout_table__headers">Radno vreme</th>
                    <td class = "checkout_table__cell" colspan = "2">{{str_replace(' \n', ', ', $order->billing_address->open_hours)}}</td>
                </tr>
            @endif
            <tr>
                <th class = "checkout_table__headers">Adresa</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->address}}</td>
            </tr>
            <tr>
                <th class = "checkout_table__headers">Poštanski broj</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->postal_code}}</td>
            </tr>
            <tr>
                <th class = "checkout_table__headers">Grad</th>
                <td class = "checkout_table__cell" colspan = "2">{{$order->billing_address->city}}</td>
            </tr>
        <tr class = "checkout_table__row checkout_table__row--shipping">
            <th class = "checkout_table__heading" colspan = "3">
                Troškovi slanja
            </th>
        </tr>
        <tr class = "checkout_table__row checkout_table__row--shipping">
            <td class = "checkout_table__cell" colspan = "3">
                {{$order->shipping_fee_formatted}} RSD
            </td>
        </tr>
        <tr class = "checkout_table__row">
            <th class = "checkout_table__heading" colspan = "3">
                Plaćanje
            </th>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Datum naručivanja</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{$order->date_order_formatted}}</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Datum isporuke</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{$order->date_delivery_formatted}}</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Međuzbir</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{$order->total_price_formatted}} RSD</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Cena dostave</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{$order->shipping_fee_formatted}} RSD</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Ukupno</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{number_format($order->total_price + $order->shipping_fee, 2, ',', '.')}} RSD</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Način plaćanja</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">{{$order->payment_method->method}}</td>
        </tr>
        <tr>
            <th class = "checkout_table__headers">Naslovljeno na</th>
            <td class = "checkout_table__cell checkout_table__cell--price" colspan = "2">eXelence d.o.o.</td>
        </tr>
        @if ($order->note !== null)
            <tr class = "checkout_table__row">
                <th class = "checkout_table__heading" colspan = "3">
                    Napomena
                </th>
            </tr>
            <tr class = "checkout_table__row">
                <td class = "checkout_table__cell" colspan = "3"> {{$order->note}}</td>
            </tr>
        @endif
        <tr class = "checkout_table__row checkout_table__row--buttons">
            <td align = "center" colspan = "3">
                <form
                    action  = "checkoutConfirm"
                    class   = "modal-content"
                    id      = "checkout_page__form"
                    method  = "post"
                    role    = "form"
                >
                    {!! $csrf_field !!}

                    <button
                        class = "checkout_table__button"
                        name  = "confirm_submit"
                        type  = "submit"
                        value = "back"
                    >
                        Nazad
                    </button>
                    <button
                        class   = "checkout_table__button"
                        name    = "confirm_submit"
                        type    = "submit"
                        value   = "confirm"
                    >
                        Naruči
                    </button>
                </form>
            </td>
        </tr>
    </table>
</section>
