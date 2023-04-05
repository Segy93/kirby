"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminUsers            = {};
if (typeof Monitor.AdminUsers.Dialogs  === "undefined") Monitor.AdminUsers.Dialogs    = {};

Monitor.AdminUsers.Dialogs.Password = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "form":             "#admin_users__modal_password__form",
        "input_password":   "#admin_users__modal_password__input_password",
        "input_repeat":     "#admin_users__modal_password__input_repeat",
        "wrapper":          "#admin_users__modal_password",                  // Kompletan modal
        "success":          ".admin_users__password_success"
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        this.getElement("input_password").addEventListener("blur", this.blurPassword.bind(this), false);
        this.getElement("input_repeat").addEventListener("blur", this.blurRepeat.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserDialogLogins", this.elements);
        return this;
    },










    /**
     * Poslata je forma za reset lozinke
     * @param   {Object}    event           JS event objekat
     */
    submitChanges: function(event) {
        var form = event.target;
        var elements = form.elements;

        var user_id = parseInt(elements.user_id.value, 10);
        var password = elements.password.value;

        this
            .updatePassword(user_id, password)
            // .hideDialog()
        ;

        form.reset();
        this.getElement("success").classList.remove("admin_common_landings_visually_hidden");
        return false;
    },

    /**
     * Polje za unos lozinke je izgubilo fokus
     * @param   {Object}    event           JS event objekat
     */
    blurPassword: function(event) {
        var psw = event.target.value;
        var error = "";

        if      (psw.match(/[a-z]/) === null)   error = "Šifra mora da sadrži malo slovo";
        else if (psw.match(/[A-Z]/) === null)   error = "Šifra mora da sadrži veliko slovo";
        else if (psw.match(/[0-9]/) === null)   error = "Šifra mora da sadrži cifru";
        event.target.setCustomValidity(error);
    },

    /**
     * Polje za potvrdu lozinke je izgubilo fokus
     * @param   {Object}    event           JS event objekat
     */
    blurRepeat: function(event) {
        var password = this.getElement("input_password");
        var repeat = event.target;

        var error = password.value === repeat.value ? "" : "Lozinke se ne poklapaju";
        repeat.setCustomValidity(error);
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id = parseInt(event.relatedTarget.dataset.userId, 10);
        this.getElement("form").elements.user_id.value = user_id;
    },










    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");
        return this;
    },

    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminUserDialogLogins", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUserDialogLogins", element, query_all, modifier);
    },









    /**
     * Promena lozinke za korisnika
     * @param   {Number}    user_id         ID korisnika kome menjamo lozinku
     * @param   {string}    password        Nova lozinka
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    updatePassword: function(user_id, password) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "updatePassword",
            {
                user_id:    user_id,
                password:   password,
            }
        );
        return this;
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminUsers.Dialogs.Password.init.bind(Monitor.AdminUsers.Dialogs.Password), false);
