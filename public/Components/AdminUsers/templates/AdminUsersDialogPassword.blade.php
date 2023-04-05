<!-- Popup(Modal) za izmenu sifre -->
<div
    class           = "modal fade"
    id              = "admin_users__modal_password"
    tabindex        = "-1"
    role            = "dialog"
    aria-labelledby = "admin_users__modal_password__label"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="" id="admin_users__modal_password__form" method="post" role="form">
                {!! $csrf_field !!}
                <input name="user_id" type="hidden" />

                <div class="modal-header">
                    <button
                        aria-label      = "Close"
                        class           = "close"
                        data-dismiss    = "modal"
                        type            = "button"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="admin_users__modal_password__label">Promeni</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_users__modal_password__input_password">Nova šifra</label>
                        <input
                            autofocus   = "autofocus"
                            class       = "form-control"
                            id          = "admin_users__modal_password__input_password"
                            minlength   = "6"
                            maxlength   = "63"
                            name        = "password"
                            placeholder = "Nova šifra"
                            required    = "required"
                            type        = "password"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_password__input_repeat">Potvrda šifre</label>
                        <input
                            class       = "form-control"
                            id          = "admin_users__modal_password__input_repeat"
                            minlength   = "6"
                            maxlength   = "63"
                            name        = "password_repeat"
                            placeholder = "Potvrda šifre"
                            required    = "required"
                            type        = "password"
                        />
                    </div>
                    <div class="alert alert-success admin_common_landings_visually_hidden admin_users__password_success">
                        <strong>Uspešno ste promenili lozinku!</strong>
                    </div>
                </div>

                <div class="modal-footer ">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input class="btn btn-primary" id="admin_users__modal_password__save" type="submit" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
</div>
