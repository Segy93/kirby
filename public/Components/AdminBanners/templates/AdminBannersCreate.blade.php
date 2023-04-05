<?php /*Forma za kreiranje clanaka*/ ?>
@if ($permissions ['banner_create'])
    <form enctype="multipart/formdata" id="admin_banners__create_form" method="post">
        {!! $csrf_field !!}
        <?php /* Naziv */ ?>
        <label for="admin_banners__create_name">Naziv</label>
        <input
            class       = "form-control"
            id          = "admin_banners__create_name"
            maxlength   = "63"
            name        = "name"
            placeholder = "Ime"
            required    = "required"
            type        = "text"
        />

        <?php /* Slika */ ?>
        <label for="admin_banners__create_image">Slika</label>
        <input
            class       = "form-control"
            id          = "admin_banners__create_image"
            name        = "image"
            required    = "required"
            type        = "file"
        />

        <?php /* Pozicija */ ?>
        <label for="admin_banners__create_page">Izaberite stranu</label>
        <select
            class       = "form-control"
            id          = "admin_banners__create_page"
            required    = "required"
            name        = "page"
        >
            <option value = "" selected>Izaberi...</option>
            @foreach($pages as $page)
                <option value = "{{$page->id}}" data-machine_name = "{{$page->machine_name}}" >{{$page->type}}</option>
            @endforeach
        </select>

        <?php /* Pozicija */ ?>
        <label for="admin_banners__create_position">Izaberite poziciju (Prvo izaberite stranu)</label>
        <select
            class       = "form-control"
            id          = "admin_banners__create_position"
            required    = "required"
            name        = "position"
        >
        </select>

        <div class = "admin_banners__location"></div>
        <?php /* Link */ ?>
        <label for="admin_banners__create_heading">Link ka čemu vodi</label>
        <input
            class       = "form-control"
            id          = "admin_banners__create_name"
            maxlength   = "255"
            name        = "link"
            placeholder = "Link"
            required    = "required"
            type        = "text"
        />

        <?php /* Url */ ?>
        <label for="admin_banners__create_url">Url pojavljivanja nalepiti sa zeljenim filterima čekiranim ako želite na svim stranama unesite /</label>
        <input
            class       = "form-control"
            id          = "admin_banners__create_url"
            maxlength   = "512"
            name        = "url"
            placeholder = "Url"
            value       = "/"
            type        = "text"
        />

        <input
            id          = "admin_banners__create__reset"
            type        = "reset"
            class       = "btn btn-danger"
            value       = "Reset"
        />
        <input
            id          = "admin_banners__create__submit"
            type        = "submit"
            class       = "btn btn-default"
            value       = "Napravi"
        />

        <p class="alert alert-success collapse" role="alert">
            Uspešno ste kreirali baner!!!!
        </p>

    </form>

@endif
