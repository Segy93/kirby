<?php /*Forma za kreiranje tagova */ ?>
@if ($permissions['tag_create'])
    <form id="admin_tags__create__form" action="" enctype="multipart/form-data" method="post" class="">
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_tags__create__input">Naziv taga</label>
            <input
                class="form-control"
                id="admin_tags__create__input"
                maxlength="63"
                name="name"
                placeholder="Naziv taga"
                required="required"
                type="text"
            />

            <input id="admin_tags__create__submit" type="submit" class="btn btn-default" value="Napravi">
        </div>
    </form>
@endif