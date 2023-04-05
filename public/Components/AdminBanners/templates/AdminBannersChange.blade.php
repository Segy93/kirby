<div
    area-labelledby = "admin_banners__modal_change__label"
    class           = "modal fade"
    id              = "admin_banners__modal_change"
    role            = "dialog"
    tabindex        = "-1"
>
    <div class="modal-dialog admin_banner__change_modal" role="document">
        <form
            action  = ""
            class   = "modal-content"
            id      = "admin_banner__change_form"
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

                    <h4 class="modal-tittle" id="admin_banners__modal_change__label">
                        Izmena banera
                    </h4>
                </div>

                <div class="modal-body">
                    <input name="banner_id" type="hidden" />

                    <?php /*Naziv*/ ?>
                    <label for="admin_banners__create_name">Naziv</label>
                    <input
                        class       = "form-control"
                        id          = "admin_banners__change_name"
                        maxlength   = "63"
                        name        = "name"
                        placeholder = "Naslov"
                        required    = "required"
                        type        = "text"
                    />

                    <label for="admin_banners__change_page">Strana</label>
                    <select
                        class       = "form-control"
                        id          = "admin_banners__change_page"
                        required    = "required"
                        name        = "page"
                    >
                        @foreach($pages as $page)
                            <option
                                data-machine_name   = "{{$page->machine_name}}"
                                value               = "{{$page->id}}"
                            >
                                {{$page->type}}
                            </option>
                        @endforeach
                    </select>
                    <label for="admin_banners__change_position">Izaberite poziciju (Prvo izaberite stranu)</label>
                    <select
                        class       = "form-control"
                        id          = "admin_banners__change_position"
                        required    = "required"
                        name        = "position"
                    >
                    </select>

                    <?php /*Tekst*/ ?>
                    <label for="admin_banners__change_link">Link</label>
                    <input
                        class       = "form-control"
                        id          = "admin_banners__change_link"
                        maxlength   = "255"
                        name        = "link"
                        placeholder = "Naslov"
                        required    = "required"
                        type        = "text"
                    />

                    <?php /*Isecak*/ ?>
                    <label for="admin_banners__create_url">Url</label>
                    <input
                        class       = "form-control"
                        id          = "admin_banners__change_url"
                        maxlength   = "512"
                        name        = "url"
                        placeholder = "Naslov"
                        required    = "required"
                        type        = "text"
                    />

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
                        id          = "admin_banners__modal_change__confirm"
                        type        = "submit"
                        value       = "Sačuvaj"
                    />
                </div>
            </div>
        </form>
    </div>
</div>

