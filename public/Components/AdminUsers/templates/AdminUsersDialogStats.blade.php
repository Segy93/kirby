@if ($permissions['user_update'])
    <!-- Popup(Modal) za statistiku -->
    <div
        aria-labelledby     = "admin_users__modal_stats__label"
        class               = "modal fade"
        id                  = "admin_users__modal_stats"
        role                = "dialog"
        tabindex            = "-1"
    >
        <div class="modal-dialog" role="document">
            <form action="" class="modal-content text-center" id="admin_users__modal_stats__form" method="post" role="form">
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

                    <h4 class="modal-title" id="admin_users__modal_stats__label">Informacije</h4>
                </div>

                <div class="modal-body">
                    <input name="user_id" type="hidden" />

                    <div class="form-group">
                        <img
                            alt             = "badge image"
                            height          = "100"
                            id              = "admin_users__modal_stats__badge_image"
                            src             = "/uploads_user/original/default.png"
                            width           = "100"
                        />

                        <h3 id="admin_users__modal_stats__badge_name"></h3>

                        <progress id="admin_users__modal_stats__badge_progress" min="0" max="100" value="0"></progress>
                    </div>

                    <div class="form-group">
                        <h4>Aktiviran:          <span id="admin_users__modal_stats__activated"> </span></h4>
                        <h4>Registrovan:        <span id="admin_users__modal_stats__registered"></span></h4>
                        <h4>Poslednja poseta:   <span id="admin_users__modal_stats__last_visit"></span></h4>
                        <h4>Vreme provedeno:    <span id="admin_users__modal_stats__time_spent"></span></h4>
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_stats__xp">Skala znanja</label>
                        <input
                            class           = "form-control"
                            id              = "admin_users__modal_stats__xp"
                            max             = "2147483647"
                            maxlength       = "63"
                            min             = "0"
                            name            = "xp"
                            placeholder     = "Skala znanja"
                            required        = "required"
                            type            = "number"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_stats__points">Bodovi</label>
                        <input
                            class           = "form-control"
                            id              = "admin_users__modal_stats__points"
                            max             = "2147483647"
                            maxlength       = "63"
                            min             = "0"
                            name            = "points"
                            placeholder     = "Bodovi"
                            required        = "required"
                            type            = "number"
                        />
                    </div>

                    <div class="form-group">
                        <label for="admin_users__modal_stats__energy_amount">Energija</label>
                        <input
                            class           = "form-control"
                            id              = "admin_users__modal_stats__energy_amount"
                            max             = "65535"
                            maxlength       = "63"
                            min             = "0"
                            name            = "energy_amount"
                            placeholder     = "Energija"
                            required        = "required"
                            type            = "number"
                        />

                        <label for="admin_users__modal_stats__energy_refill">Dopuna</label>
                        <input
                            class           = "form-control"
                            id              = "admin_users__modal_stats__energy_refill"
                            name            = "energy_refill"
                            required        = "required"
                            type            = "text"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input class="btn btn-primary" id="admin_users__modal_stats__save" type="submit" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
@endif
