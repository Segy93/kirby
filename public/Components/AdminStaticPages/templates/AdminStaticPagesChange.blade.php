<!-- Popupmodal za izmenu imena !-->
<div
    aria-labelledby = "admin_pages__static_change__label"
    class           = "modal fade"
    id              = "admin_pages__static_modal__change"
    role            = "dialog"
>
    <div class="modal-dialog" role="document">
        <form action="" id="admin_pages__static_change__form" method="post" role="form" class="modal-content">
            {!! $csrf_field !!}
            <input name="page_id" type="hidden" />

            <div class="modal-header">
                <button
                    aria-label      = "Close"
                    class           = "close"
                    data-dismiss    = "modal"
                    type            = "button"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="admin_pages__static_change__label">Promeni</h4>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="admin_pages__static_change__input_name">Novo ime</label>
                    <input
                        autofocus   = "autofocus"
                        class       = "form-control"
                        id          = "admin_pages__static_change__input_name"
                        minlength   = "1"
                        maxlength   = "63"
                        name        = "name"
                        placeholder = "Novo ime"
                        required    = "required"
                        type        = "text"
                        value       = ""
                    />
                </div>
                <div class="form-group">
                    <label for="admin_pages__static_input_category">Kategorija</label>
                    <select
                        id			="admin_pages__static_change__input_category" 
                        name		= "category"
                        required	= "required"
                        class		= "form-control"
                    >
                    @foreach($categories as $category)
                        <option value = "{{$category->id}}">{{$category->name}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="admin_pages__static_change__input_text">Text</label>
                    <input
                        type		= "text"
                        id			= "admin_pages__static_change__input_text"
                        class		= "form-control"
                    />
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                <input class="btn btn-primary" id="admin_pages__static_change__label_save" type="submit" value="Sačuvaj">
            </div>
        </form>
    </div>
</div>
