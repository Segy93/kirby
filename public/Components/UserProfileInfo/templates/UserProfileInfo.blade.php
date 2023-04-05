<div class="user_profile">
    <h3 class = "user_profile__title">
        Profilna slika
    </h3>
    <form
        data-user-id = "{{$user->id}}"
        enctype="multipart/form-data"
        id = "user_profile__info_edit__form"
        method = "post"
    >
        {!! $csrf_field !!}
        <input
            class           = "user_profile__hidden"
            data-user-id    = "{{$user->id}}"
            id              = "user_profile__info_image__change"
            type            = "file"
        />

        <img
            class           = "user_profile__info_picture"
            src             = "{{$user->profile_picture_full}}"
            width           = "100"
            alt             = "Profilna slika"
        />
        <h3 class = "user_profile__title">
            Osnovni podaci
        </h3>
        <div id="user_profile__details">
            <fieldset class="user_profile__fieldset">
                <ul class = "user_profile__info_view">
                    <li>
                        <label class="user_profile__info_label">
                            Korisničko ime:
                        </label>
                    </li>
                    <li>
                        <label class="user_profile__info_label">
                            {{$user->username}}
                        </label>
                    </li>
                </ul>
                <ul class = "user_profile__info_view">
                    <li>
                        <label class="user_profile__info_label">
                            Email:
                        </label>
                    </li>
                    <li>
                        <label class="user_profile__info_label">
                            {{$user->email}}
                        </label>
                    </li>
                </ul>
                <ul class = "user_profile__info_view">
                    <li>
                        <label class="user_profile__info_label">
                            Ime:
                        </label>
                    </li>
                    <li>
                        <label class="user_profile__info_label">
                            {{$user->name}}
                        </label>
                    </li>
                </ul>
                <ul class = "user_profile__info_view">
                    <li>
                        <label class="user_profile__info_label">
                            Prezime:
                        </label>
                    </li>
                    <li>
                        <label class="user_profile__info_label">
                            {{$user->surname}}
                        </label>
                    </li>
                </ul>
                <ul class = "user_profile__info_view">
                    <li>
                        <label class="user_profile__info_label">
                            Broj telefona:
                        </label>
                    </li>
                    <li>
                        <label class="user_profile__info_label">
                            {{$user->phone_nr}}
                        </label>
                    </li>
                </ul>
                <ul class = "user_profile__info_view">
                    <li>
                    <input
                            class   = "user_profile__info_edit_button"
                            type    = "button"
                            value   = "Izmeni"
                        />
                    </li>
                </ul>
                
            </fieldset>
            <fieldset class = "user_profile__fieldset_edit user_profile__hidden">
                <ul class = "user_profile__info_edit">
                    <li>
                        <label
                            class   = "user_profile__info_label"
                            for     = "user_profile__info_edit_username"
                        >
                            Korisničko ime
                        </label>
                    </li>

                    <li>
                        <input
                            autocomplete = "username"
                            class        = "user_profile__info_edit_text"
                            id           = "user_profile__info_edit_username"
                            maxlength    = "63"
                            name         = "username"
                            placeholder  = "Korisničko ime"
                            required     = "required"
                            type         = "text"
                            value        = "{{$user->username}}"
                        />
                    </li>
                </ul>
                <ul class = "user_profile__info_edit">
                    <li>
                        <label
                            class   = "user_profile__info_label"
                            for     = "user_profile__info_edit_name"
                        >
                            Ime
                        </label>
                    </li>

                    <li>
                        <input
                            autocomplete = "given-name"
                            class        = "user_profile__info_edit_text"
                            id           = "user_profile__info_edit_name"
                            maxlength    = "63"
                            name         = "name"
                            placeholder  = "Ime"
                            type         = "text"
                            value        = "{{$user->name}}"
                        />
                    </li>
                </ul>
                <ul class = "user_profile__info_edit">
                    <li>
                        <label
                            class   = "user_profile__info_label"
                            for     = "user_profile__info_edit_surname"
                        >
                            Prezime
                        </label>
                    </li>

                    <li>
                        <input
                            autocomplete = "family-name"
                            class        = "user_profile__info_edit_text"
                            id           = "user_profile__info_edit_surname"
                            maxlength    = "63"
                            name         = "surname"
                            placeholder  = "Prezime"
                            type         = "text"
                            value        = "{{$user->surname}}"
                        />
                    </li>
                </ul>
                <ul class = "user_profile__info_edit">
                    <li>
                        <label
                            class   = "user_profile__info_label"
                            for     = "user_profile__info_edit_phone"
                        >
                            Telefon
                        </label>
                    </li>

                    <li>
                        <input
                            autocomplete = "tel-local"
                            class        = "user_profile__info_edit_text"
                            id           = "user_profile__info_edit_phone"
                            maxlength    = "31"
                            name         = "phone_nr"
                            pattern      = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                            placeholder  = "Telefon"
                            title        = "Telefon nije u dobrom formatu! Dozvoljeni su brojevi, razmaci, do 3 crtice, najviše po 1 + i /!"
                            type         = "tel"
                            value        = "{{$user->phone_nr}}"
                        />
                    </li>
                </ul>
                <ul class = "user_profile__info_edit">
                    <li>
                        <input
                            class   = "user_profile__info_edit_button user_profile__info_edit_button--save"
                            type    = "submit"
                            value   = "Sačuvaj"
                        />
                        <input
                            class   = "user_profile__info_edit_button user_profile__info_edit_button--cancel"
                            type    = "button"
                            value   = "Odustani"
                        />
                        
                    </li>
                </ul>
            </fieldset>
        </div>
    </form>

    <h3 class = "user_profile__title">
        Promena lozinke
    </h3>

    <input
        id      = "user_profile__password_view"
        class   = "user_profile__password_view"
        type    = "button"
        value   = "Promeni lozinku"
    />

    <form
        data-user-id = "{{$user->id}}"
        enctype="multipart/form-data"
        id = "user_profile__password_edit__form"
        method = "post"
    >
        {!! $csrf_field !!}
        <ul class = "user_profile__edit_password user_profile__hidden">
            <li>
                <label for = "password_old">
                    Trenutna lozinka
                </label>
            </li>
            <li>
                <input
                    autocomplete = "current-password"
                    class        = "user_profile__edit_password_text"
                    id           = "password_old"
                    name         = "password_old"
                    placeholder  = "Trenutna lozinka"
                    required     = "required"
                    type         = "password"
                />
            </li>
        </ul>
        <ul class = "user_profile__edit_password user_profile__hidden">
            <li>
                <label for = "password">
                    Nova lozinka
                </label>
            </li>
            <li>
                <input
                    autocomplete = "new-password"
                    class        = "user_profile__edit_password_text"
                    id           = "password"
                    name         = "password"
                    placeholder  = "Nova lozinka"
                    required     = "required"
                    type         = "password"
                />
            </li>
        </ul>
        <ul class = "user_profile__edit_password user_profile__hidden">
            <li>
                <label for = "password_confirm">
                    Nova lozinka ponovo
                </label>
            </li>
            <li>
                <input
                    autocomplete = "new-password"
                    class        = "user_profile__edit_password_text"
                    id           = "password_confirm"
                    name         = "password_confirm"
                    placeholder  = "Nova lozinka ponovo"
                    required     = "required"
                    type         = "password"
                />
            </li>
        </ul>
        <ul class = "user_profile__edit_password user_profile__hidden">
            <li>
                <input
                    class  = "user_profile__edit_password_button user_profile__edit_password_button-save"
                    type    = "submit"
                    value   = "Sačuvaj"
                />

                <input
                    class   = "user_profile__edit_password_button user_profile__edit_password_button-cancel"
                    type    = "button"
                    value   = "Odustani"
                />
            </li>
        </ul>

        <ul class = "user_profile__info_password">
            <li>
                <div
                    class   = "user_profile__change_password_message"
                    id      = "user_profile__change_password_message"
                >
                    Uspešno ste promenili lozinku.
                </div>
            </li>
        </ul>
    </form>

    @if ($user->isActivated())
    <h3 class = "user_profile__title">
        Aktivacija email-a
    </h3>
        <ul class = "user_profile__info_mail">
            <li>
                <button
                        id              = "user_profile__email_activation"
                        class           = "user_profile__email_activation"
                        data-user-id    = "{{$user->id}}"
                        type            = "button"
                    >
                    Email aktivacija
                </button>
            </li>
            <li>
                <img
                    alt     = "spinner"
                    class   = "user_profile__email_activation_spinner"
                    id      = "user_profile__email_activation_spinner"
                    src     = "spinner.gif"
                />
            </li>
            <li>
                <div
                    class   = "user_profile__email_activation_message"
                    id      = "user_profile__email_activation_message"
                >
                    Aktivacioni email uspešno poslat.
                </div>
            </li>
        </ul>
    @endif
</div>

<script type="text/html" id="user_profile__info_tmpl">
    <fieldset class="user_profile__fieldset">
        <ul class = "user_profile__info_view">
            <li>
                <label class="user_profile__info_label">
                    Korisničko ime:
                </label>
            </li>
            <li>
                <label class="user_profile__info_label">
                    <%= user.username %>
                </label>
            </li>
        </ul>
        <ul class = "user_profile__info_view">
            <li>
                <label class="user_profile__info_label">
                    Email:
                </label>
            </li>
            <li>
                <label class="user_profile__info_label">
                    <%= user.email %>
                </label>
            </li>
        </ul>
        <ul class = "user_profile__info_view">
            <li>
                <label class="user_profile__info_label">
                    Ime:
                </label>
            </li>
            <li>
                <label class="user_profile__info_label">
                    <%= user.name %>
                </label>
            </li>
        </ul>
        <ul class = "user_profile__info_view">
            <li>
                <label class="user_profile__info_label">
                    Prezime:
                </label>
            </li>
            <li>
                <label class="user_profile__info_label">
                    <%= user.surname %>
                </label>
            </li>
        </ul>
        <ul class = "user_profile__info_view">
            <li>
                <label class="user_profile__info_label">
                    Broj telefona:
                </label>
            </li>
            <li>
                <label class="user_profile__info_label">
                    <%= user.phone_nr %>
                </label>
            </li>
        </ul>
        <ul class = "user_profile__info_view">
            <li>
            <input
                    class   = "user_profile__info_edit_button"
                    type    = "button"
                    value   = "Izmeni"
                />
            </li>
        </ul>
        
    </fieldset>
    <fieldset class = "user_profile__fieldset_edit user_profile__hidden">
        <ul class = "user_profile__info_edit">
            <li>
                <label
                    class   = "user_profile__info_label"
                    for     = "user_profile__info_edit_username"
                >
                    Korisničko ime
                </label>
            </li>

            <li>
                <input
                    autocomplete = "username"
                    class        = "user_profile__info_edit_text"
                    id           = "user_profile__info_edit_username"
                    maxlength    = "63"
                    name         = "username"
                    placeholder  = "Korisničko ime"
                    required     = "required"
                    type         = "text"
                    value        = "<%= user.username %>"
                />
            </li>
        </ul>
        <ul class = "user_profile__info_edit">
            <li>
                <label
                    class   = "user_profile__info_label"
                    for     = "user_profile__info_edit_name"
                >
                    Ime
                </label>
            </li>

            <li>
                <input
                    autocomplete = "given-name"
                    class        = "user_profile__info_edit_text"
                    id           = "user_profile__info_edit_name"
                    maxlength    = "63"
                    name         = "name"
                    placeholder  = "Ime"
                    type         = "text"
                    value        = "<%= user.name %>"
                />
            </li>
        </ul>
        <ul class = "user_profile__info_edit">
            <li>
                <label
                    class   = "user_profile__info_label"
                    for     = "user_profile__info_edit_surname"
                >
                    Prezime
                </label>
            </li>

            <li>
                <input
                    autocomplete = "family-name"
                    class        = "user_profile__info_edit_text"
                    id           = "user_profile__info_edit_surname"
                    maxlength    = "63"
                    name         = "surname"
                    placeholder  = "Prezime"
                    type         = "text"
                    value        = "<%= user.surname %>"
                />
            </li>
        </ul>
        <ul class = "user_profile__info_edit">
            <li>
                <label
                    class   = "user_profile__info_label"
                    for     = "user_profile__info_edit_phone"
                >
                    Telefon
                </label>
            </li>

            <li>
                <input
                    autocomplete = "tel-local"
                    class        = "user_profile__info_edit_text"
                    id           = "user_profile__info_edit_phone"
                    maxlength    = "31"
                    name         = "phone_nr"
                    pattern      = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                    placeholder  = "Telefon"
                    title        = "Telefon nije u dobrom formatu! Dozvoljeni su brojevi, razmaci, do 3 crtice, najviše po 1 + i /!"
                    type         = "tel"
                    value        = "<%= user.phone_nr %>"
                />
            </li>
        </ul>
        <ul class = "user_profile__info_edit">
            <li>
                <input
                    class   = "user_profile__info_edit_button user_profile__info_edit_button--save"
                    type    = "submit"
                    value   = "Sačuvaj"
                />
                <input
                    class   = "user_profile__info_edit_button user_profile__info_edit_button--cancel "
                    type    = "button"
                    value   = "Odustani"
                />
            </li>
        </ul>
    </fieldset>
</script>
