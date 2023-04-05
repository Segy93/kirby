<div class = "user_profile__addresses">
    <div id="user_profile__addresses">
        <div id="user_profile__addresses__body">
            <div id="user_profile__addresses__list">
                <ul class = "user_profile__unordered_list">
                    @foreach ($addresses as $address)
                        <li class = "user_profile__address_list">
                            <form class = "user_profile__address_delete_form user_profile__address_delete_form--{{$address->id}}">
                                {!! $csrf_field !!}
                                <ul
                                    class = "user_profile__address_view user_profile__address_view--{{$address->id}}"
                                    data-address-id = "{{$address->id}}"
                                >
                                    <li class = "user_profile__address_view_list">
                                        Ime:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->contact_name }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Prezime:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->contact_surname }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Telefon:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->phone_nr }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Adresa:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->address }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Poštanski broj:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->postal_code }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Grad:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->city }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        Naziv firme:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->company }}
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        PIB:
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        {{ $address->pib }}
                                    </li>
                                    <li class = "user_profile__address_view_list user_profile__address_view_list--buttons">
                                        <input
                                            class = "user_profile__address_button user_profile__address_button--change"
                                            data-address-id = "{{$address->id}}"
                                            type = "button"
                                            value = "Izmeni"
                                        />
                                        <input
                                            class = "user_profile__address_button user_profile__address_button--delete"
                                            data-address-id = "{{$address->id}}"
                                            type = "button"
                                            value = "Obriši"
                                        />
                                    </li>
                                </ul>
                            </form>
                            <form
                                class = "
                                    user_profile__address_edit_form
                                    user_profile__address_edit_form--{{$address->id}}
                                "
                                data-address-id = "{{$address->id}}"
                                method = "post"
                            >
                                {!! $csrf_field !!}
                                <ul class = "
                                    user_profile__address_edit
                                    user_profile__address_edit--{{$address->id}}
                                    user_profile__hidden
                                ">
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_name--{{$address->id}}"
                                        >
                                            Ime:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "given-name"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--name"
                                            id              = "user_profile__address_edit_name--{{$address->id}}"
                                            maxlength       = "63"
                                            name            = "contact_name"
                                            placeholder     = "Ime"
                                            required        = "required"
                                            type            = "text"
                                            value           = "{{$address->contact_name}}"
                                        />
                                    </li>

                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_surname--{{$address->id}}"
                                        >
                                            Prezime:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "family-name"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--surname"
                                            id              = "user_profile__address_edit_surname--{{$address->id}}"
                                            maxlength       = "63"
                                            name            = "contact_surname"
                                            placeholder     = "Prezime"
                                            required        = "required"
                                            type            = "text"
                                            value           = "{{$address->contact_surname}}"
                                        />
                                    </li>

                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_phone--{{$address->id}}"
                                        >
                                            Telefon:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "tel-local"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--phone user_profile__address_edit_field--{{$address->id}}"
                                            id              = "user_profile__address_edit_phone--{{$address->id}}"
                                            maxlength       = "63"
                                            name            = "phone"
                                            pattern         = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                                            placeholder     = "Broj telefona"
                                            required        = "required"
                                            title           = "Telefon nije u dobrom formatu! Dozvoljeni su brojevi, razmaci, do 3 crtice, najviše po 1 + i /!"
                                            type            = "tel"
                                            value           = "{{$address->phone_nr}}"
                                        />
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_address--{{$address->id}}"
                                        >
                                            Adresa:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "street-address"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--address user_profile__address_edit_field--{{$address->id}}"
                                            id              = "user_profile__address_edit_address--{{$address->id}}"
                                            maxlength       = "127"
                                            name            = "address"
                                            placeholder     = "Adresa"
                                            required        = "required"
                                            type            = "text"
                                            value           = "{{$address->address}}"
                                        />
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_postal_code--{{$address->id}}"
                                        >
                                            Poštanski broj:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "postal-code"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--postal_code user_profile__address_edit_field--postal_code--{{$address->id}}"
                                            id              = "user_profile__address_edit_postal_code--{{$address->id}}"
                                            max             = "37282"
                                            maxlength       = "5"
                                            min             = "11000"
                                            name            = "postal_code"
                                            placeholder     = "Poštanski broj"
                                            required        = "required"
                                            type            = "number"
                                            value           = "{{$address->postal_code}}"
                                        />
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_city--{{$address->id}}"
                                        >
                                            Grad:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            class       = "user_profile__address_edit_field user_profile__address_edit_city user_profile__address_edit_city--{{$address->id}}"
                                            data-city   = "{{$address->city}}"
                                            id          = "user_profile__address_edit_city--{{$address->id}}"
                                            name        = "city"
                                            required    = "required"
                                        >
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_company--{{$address->id}}"
                                        >
                                            Naziv firme:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "organization"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--company user_profile__address_edit_field--company--{{$address->id}}"
                                            id              = "user_profile__address_edit_company--{{$address->id}}"
                                            maxlength       = "63"
                                            name            = "company"
                                            placeholder     = "Kompanija"
                                            type            = "text"
                                            value           = "{{$address->company}}"
                                        />
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <label
                                            class   = "user_profile__address_label"
                                            for     = "user_profile__address_edit_company--{{$address->id}}"
                                        >
                                            PIB:
                                        </label>
                                    </li>
                                    <li class = "user_profile__address_view_list">
                                        <input
                                            autocomplete    = "organization"
                                            class           = "user_profile__address_edit_field user_profile__address_edit_field--pib user_profile__address_edit_field--pib--{{$address->id}}"
                                            id              = "user_profile__address_edit_pib--{{$address->id}}"
                                            maxlength       = "9"
                                            name            = "pib"
                                            placeholder     = "PIB"
                                            type            = "number"
                                            value           = "{{$address->pib}}"
                                        />
                                    </li>
                                    <li class = "user_profile__address_view_list user_profile__address_view_list--buttons">
                                        <input
                                            class           = "user_profile__address_button user_profile__address_button--save user_profile__address_button--{{$address->id}}"
                                            data-address-id = "{{$address->id}}"
                                            type            = "submit"
                                            value           = "Sačuvaj"
                                        />
                                        <input
                                            class = "user_profile__address_button user_profile__address_button--cancel"
                                            data-address-id = "{{$address->id}}"
                                            type = "button"
                                            value = "Odustani"
                                        />
                                    </li>
                                </ul>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>

            <form method = "post" class = "user_profile__address_create_form">
                {!! $csrf_field !!}
                <ul class = "user_profile__address_create user_profile__hidden">
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_name"
                        >
                            Ime:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "given-name"
                            class           = "user_profile__address_create_field user_profile__address_create_field--name"
                            id              = "user_profile__address_create_name"
                            maxlength       = "63"
                            name            = "contact_name"
                            placeholder     = "Ime"
                            required        = "required"
                            type            = "text"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_surname"
                        >
                            Prezime:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "family-name"
                            class           = "user_profile__address_create_field user_profile__address_create_field--surname"
                            id              = "user_profile__address_create_surname"
                            maxlength       = "63"
                            name            = "contact_surname"
                            placeholder     = "Prezime"
                            required        = "required"
                            type            = "text"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_phone"
                        >
                            Broj telefona:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "tel-local"
                            class           = "user_profile__address_create_field user_profile__address_create_field--phone"
                            id              = "user_profile__address_create_phone"
                            maxlength       = "63"
                            name            = "phone"
                            pattern         = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                            placeholder     = "Broj telefona"
                            required        = "required"
                            title           = "Telefon nije u dobrom formatu! Dozvoljeni su brojevi, razmaci, do 3 crtice, najviše po 1 + i /!"
                            type            = "tel"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_address"
                        >
                            Adresa:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "street-address"
                            class           = "user_profile__address_create_field user_profile__address_create_field--address"
                            id              = "user_profile__address_create_address"
                            maxlength       = "127"
                            name            = "address"
                            placeholder     = "Adresa"
                            required        = "required"
                            type            = "text"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_postal_code"
                        >
                            Poštanski broj:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "postal-code"
                            class           = "user_profile__address_create_field user_profile__address_create_field--postal_code"
                            id              = "user_profile__address_create_postal_code"
                            max             = "37282"
                            maxlength       = "5"
                            min             = "11000"
                            name            = "postal_code"
                            placeholder     = "Poštanski broj"
                            required        = "required"
                            type            = "number"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_city"
                        >
                            Grad:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            name        = "city"
                            class       = "user_profile__address_create_field user_profile__address_create--city"
                            id          = "user_profile__address_create_city"
                            placeholder = "Grad"
                            required    = "required"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_company"
                        >
                            Naziv firme:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "organization"
                            class           = "user_profile__address_create_field user_profile__address_create_field--company"
                            id              = "user_profile__address_create_company"
                            maxlength       = "63"
                            name            = "company"
                            placeholder     = "Kompanija"
                            type            = "text"
                        />
                    </li>
                    <li class = "user_profile__address_view_list">
                        <label
                            class   = "user_profile__address_label"
                            for     = "user_profile__address_create_pib"
                        >
                            PIB:
                        </label>
                    </li>
                    <li class = "user_profile__address_view_list">
                        <input
                            autocomplete    = "pib"
                            class           = "user_profile__address_create_field user_profile__address_create_field--pib"
                            id              = "user_profile__address_create_pib"
                            maxlength       = "9"
                            name            = "pib"
                            placeholder     = "PIB"
                            type            = "number"
                        />
                    </li>
                    <li class = "user_profile__address_view_list user_profile__address_view_list--buttons">
                        <input
                            type            = "submit"
                            value           = "Sačuvaj"
                            class           = "user_profile__address_create_button user_profile__address_create_button--save"
                        />
                        <input
                            type            = "button"
                            value           = "Odustani"
                            class           = "user_profile__address_create_button user_profile__address_create_button--cancel"
                        />
                    </li>
                </ul>
            </form>
        </div>

        <input
            class   = "user_profile__add_address"
            type    = "button"
            value   = "Dodaj adresu"
        />

    </div>
    <div class = "user_profile__addresses_error">
    </div>
    <script type="text/html" id="user_profile__addresses_tmpl">
        <ul class = "user_profile__unordered_list">
            <%addresses.forEach(function(address) {%>
                <li class = "user_profile__address_list">
                    <form class = "user_profile__address_delete_form user_profile__address_delete_form--<%= address.id %>">
                        {!! $csrf_field !!}
                        <ul
                            class = "user_profile__address_view user_profile__address_view--<%= address.id %>"
                            data-address-id = "<%= address.id %>"
                        >
                            <li class = "user_profile__address_view_list">
                                Ime:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.contact_name %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Prezime:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.contact_surname %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Broj telefona:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.phone_nr %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Adresa:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.address %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Poštanski broj:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.postal_code %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Grad:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.city %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                Naziv firme:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.company %>
                            </li>
                            <li class = "user_profile__address_view_list">
                                PIB:
                            </li>
                            <li class = "user_profile__address_view_list">
                                <%= address.pib %>
                            </li>
                            <li class = "user_profile__address_view_list user_profile__address_view_list--buttons">
                                <input
                                    class = "user_profile__address_button user_profile__address_button--change"
                                    data-address-id = "<%= address.id %>"
                                    type = "button"
                                    value = "Izmeni"
                                />
                                <input
                                    class = "user_profile__address_button user_profile__address_button--delete"
                                    data-address-id = "<%= address.id %>"
                                    type = "button"
                                    value = "Obriši"
                                />
                            </li>
                        </ul>
                    </form>

                    <form
                        class = "
                            user_profile__address_edit_form
                            user_profile__address_edit_form--<%= address.id %>
                        "
                        data-address-id = "<%= address.id %>"
                        method = "post"
                    >
                        {!! $csrf_field !!}
                        <ul class = "
                            user_profile__address_edit
                            user_profile__address_edit--<%= address.id %>
                            user_profile__hidden
                        ">
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_name--<%= address.id %>"
                                >
                                    Ime:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "given-name"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--name"
                                    id              = "user_profile__address_edit_name--<%= address.id %>"
                                    maxlength       = "63"
                                    name            = "contact_name"
                                    required        = "required"
                                    type            = "text"
                                    value           = "<%= address.contact_name %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_surname--<%= address.id %>"
                                >
                                    Prezime:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "family-name"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--surname"
                                    id              = "user_profile__address_edit_surname--<%= address.id %>"
                                    maxlength       = "63"
                                    name            = "contact_surname"
                                    required        = "required"
                                    type            = "text"
                                    value           = "<%= address.contact_surname %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_phone--<%= address.id %>"
                                >
                                    Broj telefona:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "tel-local"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--phone user_profile__address_edit_phone--<%= address.id %>"
                                    id              = "user_profile__address_edit_phone--<%= address.id %>"
                                    maxlength       = "63"
                                    name            = "phone"
                                    pattern         = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                                    required        = "required"
                                    title           = "Telefon nije u dobrom formatu! Dozvoljeni su brojevi, razmaci, do 3 crtice, najviše po 1 + i /!"
                                    type            = "tel"
                                    value           = "<%= address.phone_nr %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_address--<%= address.id %>"
                                >
                                    Adresa:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "street-address"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--address user_profile__address_edit_address--<%= address.id %>"
                                    id              = "user_profile__address_edit_address--<%= address.id %>"
                                    maxlength       = "127"
                                    name            = "address"
                                    required        = "required"
                                    type            = "text"
                                    value           = "<%= address.address %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_postal_code--<%= address.id %>"
                                >
                                    Poštanski broj:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "postal-code"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--postal_code user_profile__address_edit_postal_code--<%= address.id %>"
                                    id              = "user_profile__address_edit_postal_code--<%= address.id %>"
                                    max             = "37282"
                                    maxlength       = "5"
                                    min             = "11000"
                                    name            = "postal_code"
                                    required        = "required"
                                    type            = "number"
                                    value           = "<%= address.postal_code %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_city--<%= address.id %>"
                                >
                                    Grad:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    class       = "user_profile__address_edit_field user_profile__address_edit_city user_profile__address_edit_city--<%= address.id %>"
                                    data-city   = "<%= address.city %>"
                                    id          = "user_profile__address_edit_city--<%= address.id %>"
                                    name        = "city"
                                    required    = "required"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_company--<%= address.id %>"
                                >
                                    Naziv firme:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "organization"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--company user_profile__address_edit_company--<%= address.id %>"
                                    id              = "user_profile__address_edit_company--<%= address.id %>"
                                    maxlength       = "63"
                                    name            = "company"
                                    type            = "text"
                                    value           = "<%= address.company %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list">
                                <label
                                    class   = "user_profile__address_label"
                                    for     = "user_profile__address_edit_company--<%= address.id %>"
                                >
                                    PIB:
                                </label>
                            </li>
                            <li class = "user_profile__address_view_list">
                                <input
                                    autocomplete    = "pib"
                                    class           = "user_profile__address_edit_field user_profile__address_edit_field--pib user_profile__address_edit_pib--<%= address.id %>"
                                    id              = "user_profile__address_edit_pib--<%= address.id %>"
                                    maxlength       = "9"
                                    name            = "pib"
                                    type            = "number"
                                    value           = "<%= address.pib %>"
                                />
                            </li>
                            <li class = "user_profile__address_view_list user_profile__address_view_list--buttons">
                                <input
                                    class           = "user_profile__address_button user_profile__address_button--save user_profile__address_button--<%= address.id %>"
                                    data-address-id = "<%= address.id %>"
                                    type            = "submit"
                                    value           = "Sačuvaj"
                                />
                                <input
                                    class = "user_profile__address_button user_profile__address_button--cancel"
                                    data-address-id = "<%= address.id %>"
                                    type = "button"
                                    value = "Odustani"
                                />
                            </li>
                        </ul>
                    </form>
                </li>
            <%});%>
        </ul>
    </script>
</div>
