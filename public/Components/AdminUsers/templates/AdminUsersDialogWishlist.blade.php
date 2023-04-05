<!-- Popup(Modal) za listanje liste zelja korisnika -->
<div
    aria-labelledby     = "admin_users__list__wishlist__label"
    class               = "modal fade"
    id                  = "admin_users__list__wishlist"
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

                <h4 class="modal-title" id="admin_users__list__wishlist__label">Lista želja</h4>
            </div>

            <div class="modal-body admin_users__list__wishlist_body">
                <table>
                    <thead>
                        <th>Naziv</th>
                        <th>Ukloni</th>
                    </thead>
                    <tbody class = "admin_users__list__wishlist_table__body">
                    </tbody>
                </table>
            </div>

            <div class="modal-footer">
                <button
                    autofocus       = "autofocus"
                    class           = "btn btn-default"
                    data-dismiss    = "modal"
                    type            = "button"
                >
                    Napusti
                </button>
            </div>
        </div>
    </div>
</div>
