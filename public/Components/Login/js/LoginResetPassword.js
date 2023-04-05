(function () {
    "use strict";

    /**
     * Elementi koji se koriste u okviru komponente
     */
    const elements = {
        form_reset: ".login_form__form--resetpword",  // Forma za reset lozinke
    };

    /**
     * Vrednosti name atributa za formu
     */
    const names = {
        input_1: 'password',
        input_2: 'password_confirm',
    };










    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     */
    function initListeners() {
        const form_reset = getElementForm();
        if (form_reset === null) {
            return;
        }

        const input_1 = form_reset.elements.namedItem(names.input_1);
        const input_2 = form_reset.elements.namedItem(names.input_2);

        if (input_1 !== null && 'addEventListener' in input_1) {
            input_1.addEventListener("blur", blurPassword_1, false);
        }

        if (input_2 !== null && 'addEventListener' in input_2) {
            input_2.addEventListener("blur", blurPassword_2, false);
        }
    };










    /**
     * Provera jačinu sifre
     *
     * @param  {Event}     event            JavaScript event objekat
     */
    function blurPassword_1(event) {
        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const input_1 = event.currentTarget;

        if (input_1 === null) {
            return;
        }

        const psw = input_1.value;
        let error = "";

        if      (psw.match(/[a-z]/) === null)   error = "Šifra mora da sadrži malo slovo";
        else if (psw.match(/[A-Z]/) === null)   error = "Šifra mora da sadrži veliko slovo";
        else if (psw.match(/[0-9]/) === null)   error = "Šifra mora da sadrži cifru";

        input_1.setCustomValidity(error);
    };

    /**
     * Sklonjen je fokus sa polja za ponavljanje lozinke, proveravamo da li se lozinke poklapaju
     *
     * @param {Event} event Događaj koji se desio
     */
    function blurPassword_2(event) {
        /** @type {?HTMLInputElement} */
        // @ts-ignore
        const input_2 = event.currentTarget;

        if (input_2 === null || input_2.form === null) {
            return;
        }

        const input_1 = input_2.form.elements.namedItem(names.input_1);
        const password_1 = input_1 !== null && 'value' in input_1 ? input_1.value : '';
        const password_2 = input_2.value;

        input_2.setCustomValidity(password_1 !== password_2 ? "Lozinke se ne poklapaju" : "");
    };










    /**
     * Dohvatanje forme za unos nove lozinke
     *
     * @returns {?HTMLFormElement}          Pomenuta forma
     */
    function getElementForm() {
        return document.querySelector(elements.form_reset);
    }









    initListeners();
}());
