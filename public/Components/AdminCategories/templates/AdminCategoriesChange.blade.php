<!-- Popupmodal za izmenu imena !-->
<div
    aria-labelledby = "admin_categories__change_label"
    class           = "modal fade"
    id              = "admin_categories__modal_change"
    role            = "dialog"
>
    <div class="modal-dialog" role="document">
            <form action="" id="admin_categories__change_form" method="post" role="form" class="modal-content">
                {!! $csrf_field !!}
                <input name="category_id" type="hidden" />

                <div class="modal-header">
                    <button
                        aria-label      = "Close"
                        class           = "close"
                        data-dismiss    = "modal"
                        type            = "button"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="admin_categories__change_label">Promeni</h4>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_categories__change__input">Novo ime</label>
                        <input
                            autofocus   = "autofocus"
                            class       = "form-control"
                            id          = "admin_categories__change__input"
                            minlength   = "1"
                            maxlength   = "63"
                            name        = "name"
                            placeholder = "Novo ime"
                            required    = "required"
                            type        = "text"
                            value       = ""
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input class="btn btn-primary" id="admin_categories__change_label__save" type="submit" value="Sačuvaj">
                </div>
            </form>
    </div>
</div>
