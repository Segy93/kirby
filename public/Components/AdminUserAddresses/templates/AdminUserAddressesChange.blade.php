@if ($permissions['address_update'])
    <!-- Modal za izmenu -->
    <div
        aria-labelledby     = "admin_address__modal_edit__label"
        class               = "modal fade"
        id                  = "admin_address__modal_edit"
        role                = "dialog"
        tabindex            = "-1"
    >
        <div class="modal-dialog" role="document">
            <form action="" class="modal-content" enctype="multipart/form-data" id="admin_address__modal_edit__form" method="post" role="form">
                {!! $csrf_field !!}
                <div class="modal-header">
                    <button
                        aria-label      = "Close"
                        class           = "close"
                        data-dismiss    = "modal"
                        type            = "button"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="admin_address__modal_edit__label">Promeni</h4>
                </div>

                <div class="modal-body">
                    <input name="user_id" type="hidden" />

                    
                    <div class="form-group">
                        <label for="admin_address__edit__contact_name">Kontakt ime</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__contact_name"
                            maxlength       = "63"
                            name            = "contact_name"
                            placeholder     = "Kontakt ime"
                            required        = "required"
                            type            = "text"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_address__edit__contact_surname">Kontakt prezime</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__contact_surname"
                            maxlength       = "63"
                            name            = "contact_surname"
                            placeholder     = "Kontakt prezime"
                            required        = "required"
                            type            = "text"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_address__edit__company">Naziv firme</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__company"
                            maxlength       = "63"
                            name            = "company"
                            placeholder     = "Kompanija"
                            type            = "text"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_address__edit__pib">PIB</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__pib"
                            maxlength       = "9"
                            name            = "pib"
                            placeholder     = "PIB"
                            type            = "number"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_address__edit__phone_nr">Broj telefona</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__phone_nr"
                            maxlength       = "63"
                            name            = "phone_nr"
                            pattern         = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                            placeholder     = "Telefon"
                            type            = "tel"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_address__edit__address">Ulica</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__address"
                            maxlength       = "255"
                            name            = "address"
                            placeholder     = "Adresa stanovanja"
                            required        = "required"
                            type            = "text"
                        />
                    </div>
                    <div class="form-group">
                    <div class="form-group">
                        <label for="admin_address__edit__postal_code">Poštanski broj</label>
                        <input
                            class           = "form-control"
                            id              = "admin_address__edit__postal_code"
                            max             = "37282"
                            maxlength       = "63"
                            min             = "11000"
                            name            = "postal_code"
                            placeholder     = "Poštanski broj"
                            type            = "number"
                        />
                    </div>
                    <label
                        for     = "checkout_page__country"
                        class   = "checkout_page__country_label"
                    >
                        Grad i država
                    </label>
                        <input
                            name        = "country"
                            class       = "form-control"
                            id          = "admin_address__edit__country"
                            data-type   = "delivery"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input class="btn btn-primary" id="admin_address__modal_edit__save" type="submit" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
@endif
