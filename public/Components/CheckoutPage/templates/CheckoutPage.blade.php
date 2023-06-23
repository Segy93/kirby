<script src='https://www.google.com/recaptcha/enterprise.js?hl=sr' nonce="{{$_SESSION['token']}}" ></script>
<div class = "checkout_page">
    <h1 class = "checkout_page__title">
        Kasa
    </h1>
    @if ($user === null)
        <p class = "checkout_page__temporary_email checkout_page__temporary_email--print">
            Niste prijavljeni. Prijavljivanjem imate mogućnost praćenja statusa narudžbine, upravljanje adresama i puno drugih opcija.
        </p>

        <nav class = "checkout_page__temporary_email checkout_page__temporary_email--print">
            <a class = "checkout_page__temporary_email_link" href = "/prijava" >Prijavi se</a>
            <a class = "checkout_page__temporary_email_link" href = "/registracija" >Registruj se</a>
        </nav>

        <div class = "checkout_page__temporary_email">
            <h3>
                <label class = " checkout_page__temporary_email checkout_page__temporary_email--print" for="checkout_page__temporary_email">
                    Ukoliko ne želite nalog unesite email adresu
                </label>
            </h3>

            <input
                autocomplete    = "email"
                class           = "checkout_page__temporary_email_input"
                form            = "checkout_page__form"
                id              = "checkout_page__temporary_email"
                name            = "temporary_email"
                required        = "required"
                type            = "text"
            />
        </div>
    @endif

    <table class = "checkout_page__cart_preview">
        <thead>
            <th class = "checkout_page__table_heading">Slika</th>
            <th class = "checkout_page__table_heading">Naziv</th>
            <th class = "checkout_page__table_heading checkout_page__table_heading--quantity">Količina</th>
            <th class = "checkout_page__table_heading checkout_page__table_heading--price checkout_page__table_heading--price_unit">Cena</th>
            <th class = "checkout_page__table_heading checkout_page__table_heading--price">Ukupno</th>
        <thead>
        <tbody>
            @foreach ($cart as $item)
                <tr class = "checkout_page__table_row checkout_page__product_single">
                    <td class = "checkout_page__table_cell checkout_page__table_cell--main checkout_page__product_picture">
                        <img
                            alt     = "{{$item->product->name}}"
                            src     = "{{$item->product->images['thumbnail'][0]}}"
                            width   = "50"
                            class   = "checkout_page__product_image"
                        />
                    </td>
                    <td class = "checkout_page__table_cell--main">
                        <a
                            href    = "{{$item->product->url}}"
                            class   = "checkout_page__table_cell checkout_page__product_name"
                        >
                            {{$item->product->name}}
                        </a>
                    </td>
                    <td class = "checkout_page__table_cell checkout_page__table_cell--main checkout_page__product_quantity">{{ $item->quantity }}</td>
                    <td
                        class = "checkout_page__table_cell
                                checkout_page__table_cell--main
                                checkout_page__table_cell--price
                                checkout_page__table_cell--price_unit"
                    >
                        <span class = "checkout_page__price_discount">
                            {{ $item->product->discount_format }}
                        </span>
                        <span class = "checkout_page__price_retail common_landings__display_none">
                            {{ $item->product->retail_format }}
                        </span>
                    </td>
                    <td
                        class = "checkout_page__table_cell checkout_page__table_cell--main checkout_page__table_cell--price"
                    >
                        <span class = "checkout_page__price_discount">
                            {{ number_format($item->product->price_discount * $item->quantity, 2, ',', '.') }}
                        </span>
                        <span class = "checkout_page__price_retail common_landings__display_none">
                            {{ number_format($item->product->price_retail * $item->quantity, 2, ',', '.') }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class = "checkout_page__table_total">
            <tr class = "checkout_page__table_row">
                <td class = "checkout_page__table_cell checkout_page__table_cell--total_label">
                    Međuzbir:
                </td>
                <td class = "checkout_page__table_cell checkout_page__table_cell--price" colspan = "4">
                    <span class = "checkout_page__price_discount">
                        {{ number_format($total_price_discount, 2, ',', '.') }}
                    </span>
                    <span class = "checkout_page__price_retail common_landings__display_none">
                        {{ number_format($total_price_retail, 2, ',', '.') }}
                    </span>
                </td>
            </tr>
            <tr class = "checkout_page__table_row checkout_page__table_row--shipping">
                <td class = "checkout_page__table_cell checkout_page__table_cell--shipping_label">
                    Poštarina:
                </td>
                <td class = "checkout_page__table_cell checkout_page__table_cell--shipping_fee" colspan = "4">
                    <span class = "checkout_page__shipping_cost">
                        {{ number_format($shipping_fee, 2, ',', '.') }}
                    </span>
                </td>
            </tr>
            <tr class = "checkout_page__table_row">
                <td class = "checkout_page__table_cell checkout_page__table_cell--foot checkout_page__table_cell--price_all_label">
                    Ukupno:
                </td>
                <td class = "checkout_page__table_cell checkout_page__table_cell--foot checkout_page__table_cell--price_all" colspan = "4">
                    <span class = "checkout_page__price_all_discount">
                        {{ number_format($total_price_discount + $shipping_fee, 2, ',', '.') }}
                    </span>
                    <span class = "checkout_page__price_all_retail common_landings__display_none">
                        {{ number_format($total_price_retail + $shipping_fee, 2, ',', '.') }}
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>

    @if ($errors !== null)
        <ul role = "alert" class = "checkout_page__order_error_list">
            @foreach ($errors as $error)
                <li>
                    {{$error}}
                </li>
            @endforeach
        </ul>
    @endif
    @if ($user !== null)
        <h2 class = "checkout_page__subtitle">
            Informacije o korisniku
        </h2>
        <p class = "checkout_page__text">
            Informacije o narudžbini biće poslate na Vaš e-mail naveden u nastavku.
        </p>
        <table class = "checkout_page__table_info">
            <tr>
                <th class = "checkout_page__table_info_heading">
                    E-mail adresa:
                </th>
                <td class = "checkout_page__table_info_cell">
                    {{ $user->email }}
                </td>
            </tr>
            <tr>
                <th class = "checkout_page__table_info_heading">
                    Korisničko ime:
                </th>
                <td class = "checkout_page__table_info_cell">
                    {{ $user->username }}
                </td>
            </tr>
        </table>
    @endif
    <h2 class = "checkout_page__subtitle">
        Informacije o narudžbini
    </h2>
    <section class = "checkout_page__add_address">
        <div class = "checkout_page__error_list">
        </div>
        <div aria-live="polite" class = "checkout_page__address_created">
        </div>
        <h3>Dodaj adresu</h3>
        <span class = "checkout_page__add_address__required">Obavezna polja su naznačena sa </span>
        <form
            action          = "checkout/addAddress"
            autocomplete    = "on"
            class           = "modal-content"
            id              = "checkout_page__add_address_form"
            method          = "post"
            role            = "form"
        >
            {!! $csrf_field !!}
            <input type = "hidden" value = "1" name = "add_address" />
            <ul class = "checkout_page__list">
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__name"
                        class   = "checkout_page__add_address__label checkout_page__name_label checkout_page__add_address__required"
                    >
                        Ime
                    </label>
                    <input
                        autocomplete    = "given-name"
                        class           = "checkout_page__add_address__input checkout_page__name"
                        id              = "checkout_page__name"
                        maxlength       = "63"
                        name            = "name"
                        required        = "required"
                        type            = "text"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__surname"
                        class   = "checkout_page__add_address__label checkout_page__surname_label checkout_page__add_address__required"
                    >
                        Prezime
                    </label>
                    <input
                        autocomplete    = "family-name"
                        class           = "checkout_page__add_address__input checkout_page__surname"
                        id              = "checkout_page__surname"
                        maxlength       = "63"
                        name            = "surname"
                        required        = "required"
                        type            = "text"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__home_address"
                        class   = "checkout_page__add_address__label checkout_page__home_address__label checkout_page__add_address__required"
                    >
                        Adresa stanovanja
                    </label>
                    <input
                        autocomplete    = "street-address"
                        class           = "checkout_page__add_address__input checkout_page__home_address"
                        id              = "checkout_page__home_address"
                        maxlength       = "127"
                        name            = "address"
                        required        = "required"
                        type            = "text"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__post_code"
                        class   = "checkout_page__add_address__label checkout_page__post_code__label checkout_page__add_address__required"
                    >
                        Poštanski kod
                    </label>
                    <input
                        autocomplete    = "postal-code"
                        class           = "checkout_page__add_address__input checkout_page__post_code"
                        id              = "checkout_page__post_code"
                        max             = "37282"
                        maxlength       = "5"
                        min             = "11000"
                        name            = "post_code"
                        required        = "required"
                        type            = "number"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__phone"
                        class   = "checkout_page__add_address__label checkout_page__phone_label checkout_page__add_address__required"
                    >
                        Broj telefona
                    </label>
                    <input
                        autocomplete    = "tel-local"
                        class           = "checkout_page__add_address__input checkout_page__phone"
                        id              = "checkout_page__phone"
                        maxlength       = "31"
                        name            = "phone"
                        required        = "required"
                        type            = "tel"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__country"
                        class   = "checkout_page__add_address__label checkout_page__country_label checkout_page__add_address__required"
                    >
                        Grad
                    </label>
                    <input
                        autocomplete    = "address-level2"
                        class           = "checkout_page__add_address__input checkout_page__country"
                        data-type       = "delivery"
                        id              = "checkout_page__country"
                        maxlength       = "127"
                        name            = "city"
                        required        = "required"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__company"
                        class   = "checkout_page__add_address__label checkout_page__company_label"
                    >
                        Naziv firme
                    </label>
                    <input
                        autocomplete    = "organization"
                        class           = "checkout_page__add_address__input checkout_page__company"
                        id              = "checkout_page__company"
                        maxlength       = "63"
                        name            = "company"
                        type            = "text"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <label
                        for     = "checkout_page__pib"
                        class   = "checkout_page__add_address__label checkout_page__pib_label"
                    >
                        PIB
                    </label>
                    <input
                        autocomplete    = "pib"
                        class           = "checkout_page__add_address__input checkout_page__pib"
                        id              = "checkout_page__pib"
                        maxlength       = "9"
                        name            = "pib"
                        type            = "number"
                    />
                </li>
                <li class = "checkout_page__address_field">
                    <input class = "checkout_page__button" type="submit" value = "Kreiraj adresu"/>
                    <input class = "checkout_page__button checkout_page__button--cancel" type="button" value = "Otkaži"/>
                </li>
            </ul>
        </form>
    </section>

    <form
        action          = "checkoutPost"
        autocomplete    = "on"
        class           = "modal-content"
        enctype         = "multipart/form-data"
        id              = "checkout_page__form"
        method          = "post"
        role            = "form"
    >
        {!! $csrf_field !!}
        <p
            class = "checkout_page__text"
        >
            *Dostava preko 15 000 din je besplatna, preuzimanje u radnji se ne naplaćuje
        </p>
        <table class = "checkout_page__address_info">
            <tr>
                <td class = "checkout_page__dropdown">
                    <label class = "checkout_page__label checkout_page__dropdown_delivery">
                        Način isporuke
                    </label>
                    <input
                        class   = "checkout_page__delivery_shop"
                        checked
                        id      = "checkout_page__delivery_shop"
                        name    = "delivery_info"
                        type    = "radio"
                        value   = "0"
                    >
                    <label class = "checkout_page__dropdown_delivery" for = "checkout_page__delivery_shop">
                        Preuzimanje u radnji
                    </label>
                    <br/>
                    <input
                        class   = "checkout_page__delivery_home"
                        id      = "checkout_page__delivery_home"
                        name    = "delivery_info"
                        type    = "radio"
                        value   = "1"
                    >
                    <label class = "checkout_page__dropdown_delivery" for = "checkout_page__delivery_home">
                        Preuzimanje na kućnu adresu
                    </label>

                    <label class = "checkout_page__label" for = "checkout_page__shipping_info">
                        Adresa za dostavu
                    </label>
                    <select
                        class = "checkout_page__address_list checkout_page__shipping_info"
                        id = "checkout_page__shipping_info"
                        name = "shipping_address"
                        required = "required"
                    >
                        <option value = "">Izaberi...</option>
                            @foreach ($shops as $shop)
                                <option class = "checkout_page__info_shop" value = "{{$shop->id}}">{{$shop->name}}</option>
                            @endforeach
                            @if ($user !== null && !$user->addresses->isEmpty())
                                @foreach ($user->addresses as $address)
                                    <option
                                        class = "checkout_page__info_addresses"
                                        value = "{{$address->id}}"
                                    >
                                        {{$address->address}} | {{$address->contact_name}} {{$address->contact_surname}}
                                    </option>
                                @endforeach
                            @endif
                    </select>
                    <label class = "checkout_page__label" for = "checkout_page__billing_info">
                        Adresa za plaćanje
                    </label>
                    <select
                        class = "checkout_page__address_list checkout_page__billing_info"
                        id = "checkout_page__billing_info"
                        name = "billing_address"
                        required = "required"
                    >
                        <option value = "">Izaberi...</option>
                            @foreach ($shops as $shop)
                                <option class = "checkout_page__info_shop" value = "{{$shop->id}}">{{$shop->name}}</option>
                            @endforeach
                            @if ($user !== null && !$user->addresses->isEmpty())
                                @foreach ($user->addresses as $address)
                                    <option
                                        class = "checkout_page__info_addresses"
                                        value = "{{$address->id}}"
                                    >
                                        {{$address->address}} | {{$address->contact_name}} {{$address->contact_surname}}
                                    </option>
                                @endforeach
                            @endif
                    </select>
                </td>
            </tr>
            <tr class = "checkout_page__table_row checkout_page__table_row--button">
                <td>
                    <input
                        type    = "button"
                        class   = "checkout_page__button checkout_page___add_address__button checkout_page__visibility_hidden"
                        value   = "Dodaj adresu"
                    />
                </td>
            </tr>
            <tr>
                <td>
                    <label class = "checkout_page__user_label" for = "checkout_page__user_name">
                        Ime
                    </label>
                    <br/>
                    <input
                        class       = "checkout_page__user_data_fields"
                        id          = "checkout_page__user_name"
                        maxlength   = "63"
                        name        = "user_data_name"
                        required    = "required"
                        type        = "text"
                        value       = "{{ $user !== null ? $user->name : '' }}"
                    >
                </td>
            </tr>
            <tr>
                <td>
                    <label class = "checkout_page__user_label" for = "checkout_page__user_surname">
                        Prezime
                    </label>
                    <br/>
                    <input
                        class       = "checkout_page__user_data_fields"
                        id          = "checkout_page__user_surname"
                        maxlength   = "63"
                        name        = "user_data_surname"
                        required    = "required"
                        type        = "text"
                        value       = "{{ $user !== null ? $user->surname : '' }}"
                    >
                </td>
            </tr>
            <tr>
                <td>
                    <label class = "checkout_page__user_label" for = "checkout_page__user_phone">
                        Broj telefona
                    </label>
                    <br/>
                    <input
                        class       = "checkout_page__user_data_fields"
                        id          = "checkout_page__user_phone"
                        maxlength   = "31"
                        name        = "user_data_phone"
                        required    = "required"
                        type        = "text"
                        value       = "{{ $user !== null ? $user->phone_nr : '' }}"
                    >
                </td>
            </tr>
        </table>
        {{--  <h2 class = "checkout_page__subtitle">
            Specijalni popust
        </h2>
        <div class = "checkout_page__box checkout_page__special_discount">
        </div>
        <h2 class = "checkout_page__subtitle">
            <label for = "checkout_page__coupon_input">
                Kupon za popust
            </label>
        </h2>
        <div class = "checkout_page__box checkout_page__coupon">

            <input class = "checkout_page__coupon_input" id = "checkout_page__coupon_input" type="text" name = "coupon" />
            <div class = "checkout_page__coupon_validity">
            </div>
            <button type = "button">Primeni kupon</button>
        </div> --}}
        <h2 class = "checkout_page__subtitle">
            <label for = "checkout_page__terms_ofuse__input">
                Ugovorni i opšti uslovi
            </label>
        </h2>
        <div class = "checkout_page__box checkout_page__terms_ofuse">
            <table class = "checkout_page__table_contract">
                <tr>
                    <td>
                        <input
                            class       = "checkout_page__terms_ofuse__input"
                            id          = "checkout_page__terms_ofuse__input"
                            name        = "terms_ofuse"
                            required    = "required"
                            type        = "checkbox"
                        />
                        <label for = "checkout_page__terms_ofuse__input">
                            Upoznat sam i slažem se sa <a href = "/ugovorne-odredbe" target = "_blank">ugovornim i opštim uslovima</a>.
                        </label>
                    </td>
                </tr>
            </table>
        </div>
        <h2 class = "checkout_page__subtitle">
            <label for = "checkout_page__payment_type">
                Način plaćanja
            </label>
        </h2>
        <div class = "checkout_page__box checkout_page__payment">
            <p class = "checkout_page__note">
                Izaberite neki od ponuđenih načina plaćanja.
            </p>
            @foreach ($payment as $pay)
                <input
                    class = "payment_method"
                    id    = "payment_method--{{$pay->method}}"
                    type  = "radio"
                    name  = "payment_type"
                    value = "{{$pay->id}}"
                    data-method = "{{$pay->method}}"
                    @if ($pay->method === 'Keš')
                        checked
                    @endif
                />
                <label
                    for = "payment_method--{{$pay->method}}"
                >
                    {{$pay->label}}
                </label>
                <br/>
            @endforeach
            {{-- <select
                class       = "checkout_page__payment_type"
                id          = "checkout_page__payment_type"
                name        = "payment_type"
                required    = "required"
            >
                @foreach ($payment as $pay)
                    <option value="{{$pay->id}}">{{$pay->method}}</option>
                @endforeach
            </select> --}}
        </div>
        <h2 class = "checkout_page__subtitle">
            <label for = "checkout_page__date">
                Datum preuzimanja
            </label>
        </h2>
        <div class = "checkout_page__box checkout_page__arrival_date">
            <p class = "checkout_page__note">
                Plaćanje prilikom preuzimanja (plaćanje u gotovini, kuriru ili u maloprodajnom objektu ako lično preuzimate).
                Ako je neophodno odaberite željeni datum kada bi preuzeli pošiljku.
            </p>
            <input
                class       = "checkout_page__date"
                id          = "checkout_page__date"
                min         = "{{date('Y-m-d')}}"
                name        = "arrival_date"
                required    = "required"
                type        = "date"
            />
        </div>
        <div class = "checkout_page__break">
            <h2 class = "checkout_page__subtitle">
                <label for = "checkout_page__note_field">
                    Napomena
                </label>
            </h2>

            <div class = "checkout_page__box">
                <textarea class = "checkout_page__note_field" id = "checkout_page__note_field" name = "note" cols = "30" rows = "10"></textarea>
            </div>
            @if ($user === null)
                <div
                    class        = "g-recaptcha checkout_page__captcha"
                    data-sitekey = "{{ $site_key }}"
                    data-theme   = "light"
                >
                </div>
            @endif
            <button
                class = "checkout_page__button_order"
                type = "submit"
            >
                Naruči
            </button>
        </div>
    </form>
</div>
