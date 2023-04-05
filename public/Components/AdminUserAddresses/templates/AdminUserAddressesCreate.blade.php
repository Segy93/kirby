<?php /*Forma za kreiranje korisnika */ ?>
<h3>
    {{$user->username}}
</h3><hr>
@if ($permissions['address_create'])
    <form action="" enctype="multipart/form-data" id="admin_addresses__create__form" method="post">
        {!! $csrf_field !!}
        <div class="form-group">
            <input type = "hidden" class = "admin_addresses__create__user_id" data-user-id = "{{$id}}"/>

            <label for="admin_addresses__create__contact_name">Kontakt ime</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__contact_name"
                maxlength       = "63"
                name            = "contact_name"
                placeholder     = "Kontakt ime"
                required        = "required"
                type            = "text"
                pattern         = "[A-z]+"
            />
            
            <label for="admin_addresses__create__contact_surname">Kontakt prezime</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__contact_surname"
                maxlength       = "63"
                name            = "contact_surname"
                placeholder     = "Kontakt prezime"
                required        = "required"
                type            = "text"
                pattern         = "[A-z]+"
            />

            <label for="admin_addresses__create__mobile_phone">Broj telefona</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__phone"
                maxlength       = "63"
                name            = "phone"
                pattern         = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                placeholder     = "Telefon"
                required        = "required"
                type            = "tel"
                pattern         = "[0-9 \+\-]+"
            />

            <label for="admin_addresses__create__company">Naziv firme</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__company"
                maxlength       = "63"
                name            = "company"
                placeholder     = "Kompanija"
                type            = "text"
                pattern         = "[A-z]+"
            />
            <label for="admin_addresses__create__pib">PIB</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__pib"
                maxlength       = "9"
                name            = "pib"
                placeholder     = "PIB"
                type            = "number"
            />

            <label for="admin_addresses__create__address_of_living">Ulica</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__address_of_living"
                maxlength       = "255"
                name            = "address_of_living"
                placeholder     = "Adresa stanovanja"
                required        = "required"
                type            = "text"
            />

            <label for="admin_addresses__create__post_code">Poštanski broj</label>
            <input
                class           = "form-control"
                id              = "admin_addresses__create__post_code"
                max             = "37282"
                maxlength       = "63"
                min             = "11000"
                name            = "post_code"
                placeholder     = "Poštanski broj"
                required        = "required"
                type            = "number"
            />

            <label
                for     = "checkout_page__city"
                class   = "checkout_page__city_label"
            >
                Grad
            </label>
            <input
                name        = "city"
                class       = "form-control"
                id          = "admin_addresses__create_city"
                data-type   = "delivery"
                placeholder = 'Grad'
                required
            />

            <input
                class           = "btn btn-default"
                id              = "admin_addresses__create__submit"
                type            = "submit"
                value           = "Napravi"
            />
        </div>
    </form>
@endif
