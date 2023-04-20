<script src='https://www.google.com/recaptcha/api.js?hl=sr' nonce="{{$_SESSION['token']}}" ></script>

<section class="login_form login_form--{{ $view }}" id="login_form">
    <input
        class   = "login_form__radio login_form__radio--register login_form__tab common_landings__visually_hidden"
        id      = "login_form__radio--register"
        name    = "login_form__radio"
        type    = "radio"
        {{ ($view === 'register') ? 'checked="checked"' : "" }}
    />

    <a class = "login_form__link login_form__link--register" href = "{{ route('register') }}">
        <label class="login_form__radio_label login_form__tab login_form__tab--register" for="login_form__radio--register">
            Registruj se
        </label>
    </a>


    <input
        class   = "login_form__radio login_form__radio--login login_form__tab common_landings__visually_hidden"
        id      = "login_form__radio--login"
        name    = "login_form__radio"
        type    = "radio"
        {{ ($view === 'login') ? 'checked="checked"' : "" }}
    />

    <a class = "login_form__link login_form__link--login" href = "{{ route('login') }}">
        <label class = "login_form__radio_label login_form__tab login_form__tab--login" for="login_form__radio--login">
            Prijava
        </label>
    </a>


    <input
        class   = "login_form__radio login_form__radio--forgot_password login_form__tab common_landings__visually_hidden"
        id      = "login_form__radio--forgot_password"
        name    = "login_form__radio"
        type    = "radio"
        {{ ($view === 'forgot_password') ? 'checked="checked"' : "" }}
    />

    <a class = "login_form__link login_form__link--forgot-password" href = "{{ route('forgot_password') }}">
        <label class="login_form__radio_label login_form__tab login_form__tab--forgot_password" for="login_form__radio--forgot_password">
            Zaboravljena lozinka
        </label>
    </a>

    <input
        class   = "login_form__radio login_form__radio--reset login_form__tab common_landings__display_none"
        id      = "login_form__radio--reset"
        name    = "login_form__radio"
        type    = "radio"
        {{ ($view === 'reset') ? 'checked="checked"' : "" }}
    />

    <p class="login_form__error" role="alert">{{ $error }}</p>

    {{-- Forma za prijavu --}}

    <div class="login_form__form_container login_form__form_container--login">
        <form class="login_form__form login_form__form--login" action="{{ route('login-post') }}" method="post">
            <label class="login_form__input_label login_form__input_label--email" for="login_form__email">
                Email
            </label>
                <input
                    autocomplete    = "email"
                    class           = "login_form__input"
                    id              = "login_form__email"
                    name            = "email"
                    required        = "required"
                    type            = "text"
                />
            <p class="login_form__help login_form__help--email_login">Ukucajte Vaš email</p>

            <label class="login_form__input_label login_form__input_label--password" for="login_form__password">
                Lozinka
            </label>

            <input
                class           = "common_landings__visually_hidden login_form__input_password_toggle"
                id              = "login_form__input_password_toggle"
                type            = "checkbox"
            />

            <input
                autocomplete    = "current-password"
                class           = "login_form__input login_form__input_password"
                id              = "login_form__password"
                name            = "password"
                required        = "required"
                type            = "password"
            />

            <label for = "login_form__input_password_toggle">
                <svg class="login__password_eye">
                    <use xlink:href="#login__password_eye"></use>
                </svg>
            </label>
            <p class="login_form__help login_form__help--username_login">Ukucajte lozinku</p>

            {!! $csrf_field !!}

            <label class="login_form__remember_label" for="login_form__remember">
                <input class="login_form__remember" id="login_form__remember" name="remember" type="checkbox" />
                Zapamti me
            </label>

            <input class="login_form__submit login_form__submit--login" type="submit" value="Prijavi se" />

        </form>
    </div>

    <div class = "login_form__form_container login_form__form_container--register">
        {{-- Forma za registraciju --}}
        <form class="login_form__form login_form__form--register" action="{{ route('register-post') }}" method="post">
            {!! $csrf_field !!}
            <label class="login_form__input_label login_form__input_label--username" for="login_form__input_register_username">
                Korisničko ime
            </label>
            <input
                autocomplete    = "email"
                class           = "login_form__input"
                id              = "login_form__input_register_username"
                name            = "username"
                required        = "required"
                type            = "text"
            />
            <p class="login_form__help login_form__help--username">
                Razmaci su dozvoljeni, a od znakova interpunkcije samo tačka, crtica i donja crta.
            </p>

            <label class="login_form__input_label login_form__input_label--email" for="login_form__input_register_email">
                Email
            </label>
            <input
                autocomplete    = "email"
                class           = "login_form__input"
                id              = "login_form__input_register_email"
                name            = "email"
                required        = "required"
                type            = "email"
            />
            <p class="login_form__help login_form__help--email">
                Upišite validnu email adresu!<br />
                Na istu će biti poslate instrukcije za aktivaciju Vašeg naloga.<br />
                Proverite Vaš email kroz par sekundi.
            </p>

            <label class="login_form__input_label login_form__input_label--password" for="login_form__input_register_password">
                Lozinka
            </label>

            <input
                class           = "common_landings__visually_hidden register_form__input_password_toggle"
                id              = "register_form__input_password_toggle"
                type            = "checkbox"
            />

            <input
                autocomplete    = "new-password"
                class           = "login_form__input login_form__input_password"
                id              = "login_form__input_register_password"
                maxlength       = "{{ $password_max_length }}"
                minlength       = "{{ $password_min_length }}"
                name            = "password"
                required        = "required"
                type            = "password"
            />

            <label for = "register_form__input_password_toggle">
                <svg class="login__password_eye">
                    <use xlink:href="#login__password_eye"></use>
                </svg>
            </label>
            <p class="login_form__help login_form__help--password">Minimalno 6 karaktera. Lozinka mora da sadrži broj, malo i veliko slovo.</p>

            <label class="login_form__input_label login_form__input_label--confirm_password" for="login_form__input_register_confirm_password">
                Lozinka ponovo
            </label>

            <input
                class           = "common_landings__visually_hidden register_form__input_confirm_password_toggle"
                id              = "register_form__input_confirm_password_toggle"
                type            = "checkbox"
            />

            <input
                autocomplete    = "new-password"
                class           = "login_form__input login_form__input_confirm_password"
                id              = "login_form__input_register_confirm_password"
                maxlength       = "{{ $password_max_length }}"
                minlength       = "{{ $password_min_length }}"
                name            = "password_confirm"
                required        = "required"
                type            = "password"
            />
            <label for = "register_form__input_confirm_password_toggle">
                <svg class="login__password_eye">
                    <use xlink:href="#login__password_eye"></use>
                </svg>
            </label>

            <label
                class               = "login_form__accept_label"
                for                 = "login_form__accept"
            >
                <input
                    class               = "login_form__accept"
                    id                  = "login_form__accept"
                    name                = "accept"
                    required            = "required"
                    type                = "checkbox"
                />
                <a href = "/opšti-uslovi">Prihvatam uslove korišćenja</a>
            </label>
            <div
                class        = "g-recaptcha login_form__captcha"
                data-sitekey = "{{ $site_key }}"
                data-theme   = "light"
            >
            </div>

            <input class="login_form__submit login_form__submit--register" type="submit" value="Registracija" />

        </form>
    </div>

    {{-- Zaboravljena lozinka --}}

    <div class="login_form__form_container login_form__form_container--forgot_password">
        <form class="login_form__form login_form__form--forgottenpword" action="{{ route('forgot_password') }}" method="post">
            {!! $csrf_field !!}
            <p class="login_form__input_label_help login_form__input_label_help--email login_form__forgottenpword--text">
                Upišite svoj pravi email, koji ste koristili prilikom <a class="login_form__forgottenpword--link" href="{{ route('register') }}">registracije</a>:
            </p>

            <label class="login_form__input_label login_form__input_label--email" for="login_form__forgot_password">
                Email
            </label>
            <input
                autocomplete    = "email"
                class           = "login_form__input"
                id              = "login_form__forgot_password"
                name            = "email"
                required        = "required"
                type            = "email"
            />
            <p class="login_form__help login_form__help--forgot_password">
                Upišite email koji ste koristili prilikom registracije.<br />
                Na isti će biti poslat email sa uputstvom kako da promenite lozinku.
            </p>
            <div
                class        = "g-recaptcha login_form__captcha"
                data-sitekey = "{{ $site_key }}"
                data-theme   = "light"
            >
            </div>

            <input class="login_form__submit login_form__submit--login" type="submit" value="Pošalji" />

        </form>

    </div>

    {{-- Reset lozinke --}}

    <div class="login_form__form_container login_form__form_container--resetpword">
        <form class="login_form__form login_form__form--resetpword" action="{{ route('reset-password-post') }}" method="post">
            {!! $csrf_field !!}

            <p class="login_form__input_label login_form__input_label--email login_form__forgottenpword--text">
                Upiši i potvrdi svoju novu lozinku
            </p>

            <label class="login_form__input_label login_form__input_label--password" for="login_form__reset_password">
                Lozinka
            </label>

            <input
                autocomplete    = "new-password"
                class           = "login_form__input login_form__input_password"
                id              = "login_form__reset_password"
                maxlength       = "{{ $password_max_length }}"
                minlength       = "{{ $password_min_length }}"
                name            = "password"
                required        = "required"
                type            = "password"
            />

            <input
                id      = "login_form__input_reset_password_toggle"
                class   = "common_landings__visually_hidden login_form__input_reset_password_toggle"
                type    = "checkbox"
            />

            <label class="login_form__label_reset_password_toggle" for = "login_form__input_reset_password_toggle">
                <svg class="login__password_eye">
                    <use xlink:href="#login__password_eye"></use>
                </svg>
            </label>

            <label class="login_form__input_label login_form__input_label--password" for="login_form__reset_password_confirm">
                Potvrdi lozinku
            </label>
            <input
                autocomplete    = "new-password"
                class           = "login_form__input login_form__input_password"
                id              = "login_form__reset_password_confirm"
                maxlength       = "{{ $password_max_length }}"
                minlength       = "{{ $password_min_length }}"
                name            = "password_confirm"
                required        = "required"
                type            = "password"
            />

            <input
                class   = "common_landings__visually_hidden login_form__input_reset_password_confirm_toggle"
                id      = "login_form__input_reset_password_confirm_toggle"
                type    = "checkbox"
            />

            <label class="login_form__label_reset_password_toggle" for = "login_form__input_reset_password_confirm_toggle">
                <svg class="login__password_eye">
                    <use xlink:href="#login__password_eye"></use>
                </svg>
            </label>

            <input class="login_form__submit login_form__submit--register" type="submit" value="Pošalji" />

        </form>
    </div>

    {{-- Poslat mejl za reset lozinke --}}
    @if ($view === 'forgot_success')
        <p class="login_form__forgot_success">
            Na E-mail koji ste uneli prilikom registracije biće vam poslati podaciza prijavu na sajtu <a href="/">kesezakirby.rs</a>. Ako niste mogli vratiti profil na ovaj način, obratite se administraciji sajta. Da biste to uradili, kliknite <a href="mailto:{{ $contact_email }}">ovde</a>.
        </p>
    @endif
</section>


