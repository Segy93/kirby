@if ($permissions['user_update'])
    <!-- Modal za izmenu -->
    <div
        aria-labelledby     = "admin_users__modal_edit__label"
        class               = "modal fade"
        id                  = "admin_users__modal_edit"
        role                = "dialog"
        tabindex            = "-1"
    >
        <div class="modal-dialog" role="document">
            <form action="" class="modal-content" enctype="multipart/form-data" id="admin_users__modal_edit__form" method="post" role="form">
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

                    <h4 class="modal-title" id="admin_users__modal_edit__label">Promeni</h4>
                </div>

                <div class="modal-body">
                    <input name="user_id" type="hidden" />

                    <div class="form-group">
                        <label for="admin_users__modal_edit__username">Korisničko ime</label>
                        <input
                            autofocus   = "autofocus"
                            class       = "form-control"
                            id          = "admin_users__modal_edit__username"
                            maxlength   = "63"
                            name        = "username"
                            placeholder = "Korisničko ime"
                            required    = "required"
                            type        = "text"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_edit__email">Email</label>
                        <input
                            class="form-control"
                            id          = "admin_users__modal_edit__email"
                            maxlength   = "63"
                            name        = "email"
                            placeholder = "Email"
                            required    = "required"
                            type        = "email"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_edit__name">Ime</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_edit__name"
                            maxlength   = "63"
                            name        = "name"
                            placeholder = "Ime"
                            type        = "text"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_edit__surname">Prezime</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_edit__surname"
                            maxlength   = "127"
                            name        = "surname"
                            placeholder = "Prezime"
                            type        = "text"
                        />
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="admin_users__modal_edit__address_living">Adresa stanovanja</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_edit__address_living"
                            maxlength   = "255"
                            name        = "address_of_living"
                            placeholder = "Korisničko ime"
                            type        = "text"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_edit__address_delivery">Adresa isporuke</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_edit__address_delivery"
                            maxlength   = "255"
                            name        = "address_of_delivery"
                            placeholder = "Adresa isporuke"
                            type        = "text"
                        />
                    </div>
                    !-->
                    <div class="form-group">
                        <label for="admin_users__modal_edit__mobile">Mobilni telefon</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_edit__mobile"
                            maxlength   = "63"
                            name        = "mobile_phone"
                            pattern     = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                            placeholder = "Mobini telefon"
                            type        = "tel"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input class="btn btn-primary" id="admin_users__modal_edit__save" type="submit" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
@endif
