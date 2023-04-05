<!-- Popup(Modal) za brisanje tagova -->
@if ($permissions['tag_delete'])
<div
    aria-labelledby     = "admin_tags__modal_delete__label"
    class               = "modal fade"
    id                  = "admin_tags__modal_delete"
    role                = "dialog"
    tabindex            = "-1"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    aria-label      = "Close"
                    class           = "close"
                    data-dismiss    = "modal"
                    type            = "button"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="admin_tags__modal_delete__label">Brisanje taga</h4>
            </div>

            <div class="modal-body">
                <p>
                    Ovime ćete obrisati tag.<br />
                    Podatke nije moguće povratiti.<br />
                    Da li želite da nastavite?
                </p>
            </div>

            <div class="modal-footer">
                <button
                    autofocus       = "autofocus"
                    class           = "btn btn-default"
                    data-dismiss    = "modal"
                    type            = "button"
                >
                    Odustani
                </button>

                <button
                    class           = "btn btn-danger"
                    data-dismiss    = "modal"
                    id              = "admin_tags__modal_delete__confirm"
                    type            = "button"
                >
                    Obriši
                </button>
            </div>
        </div>
    </div>
</div>
@endif
