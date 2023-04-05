<?php /*Pravljenje kategorija*/ ?>
@if ($permissions['category_static_create'])
    <form
        id="admin_categories__static_create__form"
        action=""
        class=""
        enctype="multypart/form-data"
        method="post"
    >
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_categories__static_input_name">Ime kategorije</label>
            <input
                class="form-control"
                id="admin_categories__static_input__name"
                maxlength="63"
                name="name"
                placeholder="Ime kategorije"
                required="required"
                type="text"
            />
            <input id="admin_categories__static_button__submit" type="submit" class="btn btn-default" value="Napravi">
        </div>
    </form>
@endif