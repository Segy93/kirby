(function () {
    "use strict";

    const elements = { // Selektori za elemente koji ce biti korišćeni u komponenti
        form_create:                    ".login_form__form--register",  // Forma za kreiranje novog korisnika
        link:                           ".login_form__link",            // Registruj se-Prijava-Zaboravljena lozinka linkovi
        show_password_login:            ".login_form__input_password_toggle",
        show_password_register_confirm: ".register_form__input_confirm_password_toggle",
        show_password_register:         ".register_form__input_password_toggle",
        show_password_reset_confirm:    ".login_form__input_reset_password_confirm_toggle",
        show_password_reset:            ".login_form__input_reset_password_toggle",
        wrapper:                        '.login_form',                  // Okvir oko cele komponente
    };

    /**
     * Vrednosti name atributa za formu
     */
     const names = {
        email: 'email',
        password_confirm: 'password_confirm',
        password: 'password',
        username: 'username',
    };









    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     */
    function initListeners() {
        const form_create = getElementForm();

        if (form_create !== null) {
            const input_username = form_create.elements.namedItem(names.username);
            if (input_username !== null && 'addEventListener' in input_username) {
                input_username.addEventListener('input', blurUsername, false);
            }

            const input_email = form_create.elements.namedItem(names.email);
            if (input_email !== null && 'addEventListener' in input_email) {
                input_email.addEventListener('input', blurEmail, false);
            }

            const input_password = form_create.elements.namedItem(names.password);
            if (input_password !== null && 'addEventListener' in input_password) {
                input_password.addEventListener('input', blurPassword, false);
            }

            const input_password_confirm = form_create.elements.namedItem(names.password_confirm);
            if (input_password_confirm !== null && 'addEventListener' in input_password_confirm) {
                input_password_confirm.addEventListener('input', blurPasswordConfirm, false);
            }
        }

        const links = getElementsLinks();
        links.forEach(link => link.addEventListener("click", clickLink, false));

        const checkbox_password_login = getElementCheckboxShowPasswordLogin();
        if (checkbox_password_login !== null) {
            checkbox_password_login.addEventListener("change", togglePassword, false);
        }

        const checkbox_password_register = getElementCheckboxShowPasswordRegister();
        if (checkbox_password_register !== null) {
            checkbox_password_register.addEventListener("change", togglePassword, false);
        }

        const checkbox_password_register_confirm = getElementCheckboxShowPasswordRegisterConfirm();
        if (checkbox_password_register_confirm !== null) {
            checkbox_password_register_confirm.addEventListener("change", togglePassword, false);
        }

        const checkbox_password_reset = getElementCheckboxShowPasswordReset();
        if (checkbox_password_reset !== null) {
            checkbox_password_reset.addEventListener("change", togglePassword, false);
        }

        const checkbox_password_confirm = getElementCheckboxShowPasswordResetConfirm();
        if (checkbox_password_confirm !== null) {
            checkbox_password_confirm.addEventListener("change", togglePassword, false);
        }

        window.addEventListener("popstate", statePop, false);
    };

    /**
     * Registracija elemenata u upotrebi od strane komponente
     */
    function registerElements() {
        // @ts-ignore
        Kirby.Main.Dom.register("Login", elements);
    };










    /**
     * Sklonjen je fokus sa email polja, pa proveravamo da li je mejl zauzet
     *
     * @param   {Event}     event           Događaj koji se desio
     */
     function blurEmail(event) {
        /** @type {HTMLInputElement} */
        // @ts-ignore
        const input = event.currentTarget;
        const text = input.value;

        // Ako server sporo odgovara,
        // da korisnik ne dobija gresku dok cekamo odgovor na proveru
        input.setCustomValidity("");
        checkEmailTaken(text);
    };

    /**
     * Sklonjen je fokus sa polja za korisničko ime, pa proveravamo da li je zauzeto
     *
     * @param   {Event}     event           Događaj koji se desio
     */
     function blurUsername(event) {
        /** @type {HTMLInputElement} */
        // @ts-ignore
        const input = event.currentTarget;
        const text = input.value;

        // Ako server sporo odgovara,
        // da korisnik ne dobija gresku dok cekamo odgovor na proveru
        input.setCustomValidity("");
        checkUsernameTaken(text);
    };

    /**
     * Provera jačinu šifre
     *
     * @param   {Event}     event           Događaj koji se desio
     */
     function blurPassword(event) {
        /** @type {HTMLInputElement} */
        // @ts-ignore
        const input = event.currentTarget;
        const psw = input.value;

        let error = "";

        if      (psw.match(/[a-z]/) === null)   error = "Šifra mora da sadrži malo slovo";
        else if (psw.match(/[A-Z]/) === null)   error = "Šifra mora da sadrži veliko slovo";
        else if (psw.match(/[0-9]/) === null)   error = "Šifra mora da sadrži cifru";

        input.setCustomValidity(error);
    };

    /**
     * Sklonjen je fokus sa polja za potvrdu lozinke
     *
     * @param   {Event}     event           Događaj koji se desio
     */
    function blurPasswordConfirm(event) {
        const form = getElementForm();
        /** @type {HTMLInputElement} */
        // @ts-ignore
        const input_confirm = event.currentTarget;

        if (form === null) {
            return;
        }

        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const input = form.elements.namedItem(names.password);
        const psw = input === null ? '' : input.value;

        const pswconfirm = input_confirm.value;

        let error = "";
        if (pswconfirm !== psw) {
            error = "Lozinke se ne podudaraju";
        }

        input_confirm.setCustomValidity(error);
    }

    /**
     * Korisnik je kliknuo da prikaže/sakrije lozinku
     *
     * @param   {Event}     event           Događaj koji se desio
     */
     function togglePassword(event) {
        /** @type {HTMLInputElement} */
        // @ts-ignore
        const current_target = event.currentTarget;

        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const next_sibling = current_target.nextSibling;

        if (next_sibling === null) {
            return;
        }

        if (next_sibling.type === "password" && current_target.checked) {
            next_sibling.type = "text";
        } else {
            next_sibling.type = "password";
        }
    }

    /**
     * Korisnik je kliknuo na link, radimo JS rutiranje
     *
     * @param   {Event}     event           Događaj koji se desio
     */
    function clickLink(event) {
        event.preventDefault();

        /** @type {HTMLAnchorElement} */
        // @ts-ignore
        const link = event.currentTarget;
        const href = link.getAttribute('href');

        history.pushState({}, document.title, href);

        document.dispatchEvent(new CustomEvent("Kirby.PushState"));

        /** @type {?HTMLLabelElement} */
        // @ts-ignore
        const label = link.firstChild;

        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const input = label.control;

        if (input !== null) {
            input.checked = true;
        }
    };

    /**
     * Korisnik je uradio napred/nazad navigaciju,
     * pa selektujemo ispravan tab
     */
    function statePop() {
        const wrapper = getElementWrapper();

        if (wrapper === null) {
            return;
        }

        const href = window.location.href;
        const link = wrapper.querySelector(`[href="${href}"]`);

        /** @type {?HTMLLabelElement} */
        // @ts-ignore
        const label = link === null ? null : link.firstChild;

        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const input = label === null ? null : label.control;

        if (input !== null) {
            input.checked = true;
        }
    };










    /**
     * Dohvatanje checkbox-a za prikaz lozinke kod prijave
     *
     * @returns {?HTMLInputElement}         Pomenut checkbox
     */
    function getElementCheckboxShowPasswordLogin() {
        return document.querySelector(elements.show_password_login);
    }

    /**
     * Dohvatanje checkbox-a za prikaz lozinke kod registracije (1. input)
     *
     * @returns {?HTMLInputElement}         Pomenut checkbox
     */
    function getElementCheckboxShowPasswordRegister() {
        return document.querySelector(elements.show_password_register);
    }

    /**
     * Dohvatanje checkbox-a za prikaz lozinke kod registracije (2. input)
     *
     * @returns {?HTMLInputElement}         Pomenut checkbox
     */
    function getElementCheckboxShowPasswordRegisterConfirm() {
        return document.querySelector(elements.show_password_register_confirm);
    }

    /**
     * Dohvatanje checkbox-a za prikaz lozinke kod resetovanja (1. input)
     *
     * @returns {?HTMLInputElement}         Pomenut checkbox
     */
     function getElementCheckboxShowPasswordReset() {
        return document.querySelector(elements.show_password_reset);
    }

    /**
     * Dohvatanje checkbox-a za prikaz lozinke kod resetovanja (2. input)
     *
     * @returns {?HTMLInputElement}         Pomenut checkbox
     */
     function getElementCheckboxShowPasswordResetConfirm() {
        return document.querySelector(elements.show_password_reset_confirm);
    }

    /**
     * Dohvatanje forme za unos nove lozinke
     *
     * @returns {?HTMLFormElement}          Pomenuta forma
     */
    function getElementForm() {
        return document.querySelector(elements.form_create);
    }

    /**
     * Dohvatanje registracija-prijava-zaboravljena lozinka linkova
     *
     * @returns {NodeListOf<HTMLAnchorElement>} Pomenuti linkovi
     */
    function getElementsLinks() {
        return document.querySelectorAll(elements.link);
    }

    /**
     * Dohvata element koji obuhvata celu komponentu
     *
     * @returns {?HTMLElement}              Pomenuti element
     */
    function getElementWrapper() {
        return document.querySelector(elements.wrapper);
    }










    /**
     * Provera da li je dati mejl zauzet
     *
     * @param   {String}    email           Mejl koji proveravamo
     */
    function checkEmailTaken(email) {
        // @ts-ignore
        Kirby.Main.Ajax(
            "Login",
            "checkEmailTaken",
            {
                email,
            },
            (/** @type {Boolean} */isTaken) => {
                const form_create = getElementForm();

                if (form_create === null) {
                    return;
                }

                /** @type {?HTMLInputElement} */
                // @ts-ignore
                const input = form_create.elements.namedItem(names.email);

                if (input === null || input.value === email) {
                    return;
                }

                input.setCustomValidity(isTaken ? "Ova email adresa je zauzeta. Molimo pokušajte ponovo." : "");
            }
        );
    };

    /**
     * Provera da li je dato korisničko ime zauzeto
     *
     * @param   {String}    username        Mejl koji proveravamo
     */
     function checkUsernameTaken(username) {
        // @ts-ignore
        Kirby.Main.Ajax(
            "Login",
            "checkUsernameTaken",
            {
                username,
            },
            (/** @type {Boolean} */isTaken) => {
                const form_create = getElementForm();

                if (form_create === null) {
                    return;
                }

                /** @type {?HTMLInputElement} */
                // @ts-ignore
                const input = form_create.elements.namedItem(names.username);

                if (input === null || input.value === username) {
                    return;
                }

                input.setCustomValidity(isTaken ? "Ovo korisničko ime je zauzeto. Molimo pokušajte ponovo." : "");
            }
        );
    };









    registerElements();
    initListeners();
}());
