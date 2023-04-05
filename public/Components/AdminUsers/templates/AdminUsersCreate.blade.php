<?php /*Forma za kreiranje korisnika */ ?>
@if ($permissions['user_create'])
    <form action="" enctype="multipart/form-data" id="admin_users__create__form" method="post">
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_users__create__username">Korisničko ime</label>
            <input
                class           = "form-control"
                id              = "admin_users__create__username"
                maxlength       = "63"
                name            = "username"
                placeholder     = "Korisničko ime"
                required        = "required"
                type            = "text"
            />

            <label for="admin_users__create__email">Email</label>
            <input
                class           = "form-control"
                id              = "admin_users__create__email"
                maxlength       = "63"
                name            = "email"
                placeholder     = "Email"
                required        = "required"
                type            = "email"
            />

            <label for="admin_users__create__password">Šifra</label>
            <input
                autocomplete    = "new-password"
                class           = "form-control"
                id              = "admin_users__create__password"
                maxlength       = "63"
                minlength       = "6"
                name            = "password"
                placeholder     = "Šifra"
                required        = "required"
                type            = "password"
            />

            <label for="admin_users__create__name">Ime</label>
            <input
                class           = "form-control"
                id              = "admin_users__create__name"
                maxlength       = "63"
                name            = "name"
                placeholder     = "Ime"
                type            = "text"
            />

            <label for="admin_users__create__surname">Prezime</label>
            <input
                class           = "form-control"
                id              = "admin_users__create__surname"
                maxlength       = "127"
                name            = "surname"
                placeholder     = "Prezime"
                type            = "text"
            />

            <input
                class           = "btn btn-default"
                id              = "admin_users__create__submit"
                type            = "submit"
                value           = "Napravi"
            />
        </div>
    </form>
@endif
