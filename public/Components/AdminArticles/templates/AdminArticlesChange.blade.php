<div
    area-labelledby = "admin_articles__modal_change__label"
    class           = "modal fade"
    id              = "admin_articles__modal_change"
    role            = "dialog"
    tabindex        = "-1"
>
    <div class="modal-dialog admin_article__change_modal" role="document">
        <form
            action  = ""
            class   = "modal-content"
            id      = "admin_article__change_form"
            role    = "form"
        >
            {!! $csrf_field !!}
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        area-label   = "Close"
                        class        = "close"
                        data-dismiss = "modal"
                        type         = "button"
                    >
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-tittle" id="admin_articles__modal_change__label">
                        Izmena članka
                    </h4>
                </div>

                <div class="modal-body">
                    <input name="article_id" type="hidden" />

                    <?php /*Naslov*/ ?>
                    <label for="admin_articles__create_heading">Naslov</label>
                    <input
                        class       = "form-control"
                        id          = "admin_articles__change_heading"
                        maxlength   = "63"
                        name        = "heading"
                        placeholder = "Naslov"
                        required    = "required"
                        type        = "text"
                    />


                    <?php /*Tekst*/ ?>
                    <label for="admin_articles__change_text">Tekst</label>
                    <textarea
                        class       = "form-control admin_articles__change_text"
                        id          = "admin_articles__change_text"
                        maxlength   = "63"
                        name        = "text"
                        placeholder = "Tekst"
                    >
                    </textarea>

                    <?php /*Isecak*/ ?>
                    <label for="admin_articles__create_excerpt">Isečak</label>
                    <textarea
                        class       = "form-control"
                        id          = "admin_articles__change_excerpt"
                        maxlength   = "63"
                        name        = "excerpt"
                        placeholder = "Isečak"
                    ></textarea>

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

                    <input
                        class       = "btn btn-primary"
                        id          = "admin_articles__modal_change__confirm"
                        type        = "submit"
                        value       = "Sačuvaj"
                    />
                </div>
            </div>
        </form>
    </div>
</div>

