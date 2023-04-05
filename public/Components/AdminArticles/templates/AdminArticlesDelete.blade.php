<div
    area-labelledby = "admin_articles__modal_delete__label"
    class           = "modal fade"
    id              = "admin_articles__modal_delete"
    role            = "dialog"
    tabindex        = "-1"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    area-label   = "Zatvori"
                    class        = "close"
                    data-dismiss = "modal"
                    type         = "button"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-tittle" id="admin_articles__modal_delete__label">
                    Brisanje članka
                </h4>
            </div>

            <div class="modal-body">
                <p>
                    Ovime ćete obrisati članak.<br />
                    Podatke nije moguće povratiti.<br />
                    Da li želite da nastavite?
                </p>
            </div>
            <div class="modal-footer">
                <button
                    autofocus    = "autofocus"
                    class        = "btn btn-default"
                    data-dismiss = "modal"
                    type         = "button"
                >
                    Odustani
                </button>

                <button
                    class        = "btn btn-danger"
                    data-dismiss = "modal"
                    id           = "admin_articles__modal_delete__confirm"
                    type         = "button"
                >
                    Obriši
                </button>
            </div>
        </div>
    </div>
</div>