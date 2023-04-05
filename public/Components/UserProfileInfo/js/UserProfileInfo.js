(function () {
    "use strict";

    var config = {
        callback_submit: null,
        processing_password: false,
    };

    var elements = {
        details:            "#user_profile__details",
        image_change:       "#user_profile__info_image__change",
        image:              ".user_profile__info_picture",
        info_edit:          ".user_profile__info_edit",
        info_view:          ".user_profile__info_view",
        info_cancel:        ".user_profile__info_edit_button--cancel",
        info_form:          "#user_profile__info_edit__form",
        email_activation:   "#user_profile__email_activation",
        fieldset:           ".user_profile__fieldset",
        fieldset_edit:      ".user_profile__fieldset_edit",
        spinner:            "#user_profile__email_activation_spinner",
        message:            "#user_profile__email_activation_message",
        password_message:   "#user_profile__change_password_message",
        password_view:      ".user_profile__password_view",
        password_edit:      ".user_profile__edit_password",
        password_old:       "#password_old",
        password_cancel:    ".user_profile__edit_password_button-cancel",
        password_form:      "#user_profile__password_edit__form",
    };

    var templates = {
        main: function() {},
    };










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initTemplates();
        initListeners();
    };

    /**
     * inicijalizuje osluskivace
     */
    var initListeners = function() {
        getElement("image").addEventListener("click", imageClicked, false);
        getElement("image_change").addEventListener("change", imageChanged, false);
        getElement("details").addEventListener("click", clickWithin, false);
        getElement("info_form").addEventListener("submit", formSubmitted, false);

        var email_activation = getElement("email_activation");

        //Kada je korisnik vec aktivirao nalog element email_activation ce biti null

        if (email_activation !== null) {
            email_activation.addEventListener("click", emailActivation, false);
        }
        getElement("password_view").addEventListener("click", passwordView, false);
        getElement("password_cancel").addEventListener("click", cancelPasswordClicked, false);
        getElement("password_form").addEventListener("submit", passwordSubmitted, false);
        var password_form = getElement("password_form");
        if (password_form !== null) {
            elements["input_password_1"] = password_form.elements.password;
            elements["input_password_old"] = password_form.elements.password_old;
            password_form.elements.password_old.addEventListener("input", blurPasswordOld, false);
            password_form.elements.password.addEventListener("input", blurPassword1, false);
            password_form.elements.password_confirm.addEventListener("input", blurPassword2, false);
        }
    };

    var initTemplates = function() {
        templates.main = _.template(document.getElementById("user_profile__info_tmpl").innerHTML);
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Monitor.Main.DOM.register("UserProfileInfo", elements);
    };











    var clickWithin = function(event) {
        var selector_view_button = getElementSelector("fieldset");
        var selector_cancel_button = getElementSelector("info_cancel");
        var info_form = getElement("info_form");
        if (event.target.closest(selector_cancel_button) !== null) {
            cancelInfoClicked();
            info_form.reset();
        } else if (event.target.closest(selector_view_button) !== null) {
            viewInfoClicked();
        }
    };

    /**
     * Provera jacinu sifre
     * @param  {Object}     event           JavaScript event objekat
     */
    var blurPasswordOld = function(event) {
        var psw = event.target.value;
        var error = "";

        if      (psw.length < 6)                error = "Minimalna dužina šifre je 6 karaktera";
        else if (psw.match(/[a-z]/) === null)   error = "Šifra mora da sadrži malo slovo";
        else if (psw.match(/[A-Z]/) === null)   error = "Šifra mora da sadrži veliko slovo";
        else if (psw.match(/[0-9]/) === null)   error = "Šifra mora da sadrži cifru";
        else checkPassword(psw)
        event.target.setCustomValidity(error);
    }

    var blurPassword1 = function(event) {
        var psw = event.target.value;
        var password_old = elements.input_password_old.value;
        var error = "";

        if      (psw.length < 6)                error = "Minimalna dužina šifre je 6 karaktera";
        else if (psw.match(/[a-z]/) === null)   error = "Šifra mora da sadrži malo slovo";
        else if (psw.match(/[A-Z]/) === null)   error = "Šifra mora da sadrži veliko slovo";
        else if (psw.match(/[0-9]/) === null)   error = "Šifra mora da sadrži cifru";
        else if (psw === password_old)          error = "Trenutna i nova lozinka su iste";
        event.target.setCustomValidity(error);
    };

    var blurPassword2 = function(event) {
        var password_old = elements.input_password_old.value;
        var password_1 = elements.input_password_1.value;
        var password_2 = event.target.value;
        event.target.setCustomValidity(password_1 !== password_2 ? "Lozinke se ne poklapaju" : "");
        if (password_old === password_1) event.target.setCustomValidity("Trenutna i nova lozinka su iste");
    };





    var formSubmitted = function(event) {
        event.preventDefault();
        var form         = event.currentTarget;
        var element      = form.elements;

        var user_id = parseInt(form.dataset.userId, 10);

        var username    = element.username.value;
        var name        = element.name.value;
        var surname     = element.surname.value;
        var phone       = element.phone_nr.value;

        changeUserInfo(user_id, username, name, surname, phone);
    };


    var passwordSubmitted = function(user_id, password_old, password, password_confirm) {
        event.preventDefault();
        var form        = event.currentTarget;
        var elements    = form.elements;

        var user_id = parseInt(form.dataset.userId, 10);

        var password_old = elements.password_old.value;

        var password = elements.password.value;

        var password_confirm = elements.password_confirm.value;

        changePassword(user_id, password_old, password, password_confirm);
    };

    var cancelInfoClicked = function() {
        var element_edit    = getElement("fieldset_edit");
        var element_view    = getElement("fieldset", true);
        element_edit.classList.add("user_profile__hidden");
        element_view.forEach(function(element) {
            element.classList.remove("user_profile__hidden");
        });
    };


    var viewInfoClicked = function() {
        var element_view    = getElement("fieldset", true);
        var element_edit    = getElement("fieldset_edit");
        element_view.forEach(function(element) {
            element.classList.add("user_profile__hidden");
        });
        element_edit.classList.remove("user_profile__hidden");
    };

    var passwordView = function()  {
        var element         = event.currentTarget;
        var elements_edit   = getElement("password_edit", true);
        elements_edit.forEach(function(element_edit) {
            element_edit.classList.remove("user_profile__hidden");
        });
        element.classList.add("user_profile__hidden");
    };

    var cancelPasswordClicked = function()  {
        var elements_edit    = getElement("password_edit", true);
        var element_view    = getElement("password_view");
        var element_form    = getElement("password_form");

        elements_edit.forEach(function(element_edit) {
            element_edit.classList.add("user_profile__hidden");
        });
        element_view.classList.remove("user_profile__hidden");
        element_form.reset();
    };

    var imageChanged = function(event) {
        var image = event.currentTarget.files[0];
        var user_id = event.currentTarget.dataset.userId;
        userImageChange(user_id, image);
    };

    var imageClicked = function(event) {
        getElement("image_change").click();
    };










    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("UserProfileInfo", element, query_all, modifier, parent);
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("UserProfileInfo", element, query_all, modifier);
    };


    var render = function(data, product_id) {
        var container = getElement("details");
        var html = templates.main({
            user: data,
        });

        container.innerHTML = html;
    };


    var updatedImage = function(data) {
        getElement("image").src = data.profile_picture_full;
    };





    var userImageChange = function(user_id, image) {
        Monitor.Main.Ajax(
            "UserProfile",
            "userImageChange",
            {
                user_id:    user_id,
                image:      image,
            },
            updatedImage,
            undefined,
            true
        );
    };

    var changeUserInfo = function(user_id, username, name, surname, phone_nr) {
        Monitor.Main.Ajax(
            "UserProfile",
            "userChange",
            {
                user_id:    user_id,
                username:   username,
                name:       name,
                surname:    surname,
                phone_nr:   phone_nr,
            },
            render
        );
    };


    var changePassword = function(user_id, password_old, password, password_confirm) {
        var callback = function() {
            var password_message = getElement("password_message");
            Monitor.Main.Ajax(
                "UserProfile",
                "changePassword",
                {
                    user_id:            user_id,
                    password_old:       password_old,
                    password:           password,
                    password_confirm:   password_confirm,
                },
                function(response) {
                    var element_form = getElement("password_form");
                    var element_edit = getElement("password_edit");
                    var element_view = getElement("password_view");

                    password_message.classList.add("active");
                    element_edit.classList.add("user_profile__hidden");
                    element_view.classList.remove("user_profile__hidden");
                    element_form.reset();
                }
            );
        };

        if (config.processing_password === false) {
            config.callback_submit = null;
            callback();
        } else {
            config.callback_submit = callback;
        }
    };

    var checkPassword = function(password_old) {
        if (config.processing_password === false) {
            config.processing_password = true;
            Monitor.Main.Ajax(
                "UserProfile",
                "checkPassword",
                {
                    password_old: password_old,
                },
                function(valid) {
                    config.processing_password = false;
                    var validity = valid ? "" : "Trenutna lozinka nije dobra!";
                    getElement("password_old").setCustomValidity(validity);
                    if (config.callback_submit !== null) config.callback_submit();
                }
            );
        }
    };


    var emailActivation = function() {
        var spinner = getElement("spinner");
        spinner.classList.add("active");
        var message = getElement("message");

        Monitor.Main.Ajax(
            "UserProfile",
            "sendEmail",
            {},
            function(response) {
                spinner.classList.remove("active");
                message.classList.add("active");
            }
        );
    };






    document.addEventListener("DOMContentLoaded", init);
}());
