"use strict";

if (Monitor === undefined) var Monitor = {};
if (Monitor.Administrators === undefined) Monitor.Administrators = {};

Monitor.Administrators.Create = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        usernames: [],
        emails: [],
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form_create:      "#admin_admins__create__form",  // Forma za kreiranje novog administratora
        input_create:     "#admin_admins__create__input", // Polje za unos imena administratora
        dropdown_create:  "#admin_admins__create__role",  // Padajući meni za izbor uloge novom administratoru
        input_email:      "#admin_admins__input__email",  // Polje za unos email-a administratora
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
            .fetchData()
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Object}                    Monitor.Administrators.Create objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var form_create = this.getElement("form_create");
        if(form_create !== null) form_create.onsubmit = this.formSubmitted.bind(this);

        var input_email = this.getElement("input_email");
        if(input_email !== null) input_email.addEventListener("blur", this.blurEmail.bind(this), false);

        var input_create = this.getElement("input_create");
        if(input_create !== null) input_create.addEventListener("blur", this.blurName.bind(this), false);

        document.addEventListener("Monitor.Admin.Update", this.changedAdmin.bind(this), false);
        document.addEventListener("Monitor.Admin.Delete", this.changedAdmin.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.Administrators.Create objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdministratorsCreate", this.elements);
        return this;
    },








    /**
     * Provera da li admin sa datim email-om vec postoji kada admin pokusa da kreira novog admina
     * @param  {Object}     event           JavaScript event objekat
     */
    blurEmail: function(event) {
        this.validateEmail();
    },

    /**
     * Provera da li admin sa datim imenom vec postoji kada admin pokusa da kreira novog admina
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        this.validateName();
    },

    /**
     * Forma je poslata, preusmeravamo to i šaljemo zahtev AJAX-om
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Boolean}                   false, jer ne želimo da se forma pošalje klasičnim putem
     */
    formSubmitted: function(event) {
        var name  = this.getElement("input_create").value;
        var email = this.getElement("input_email").value;
        var role  = parseInt(this.getElement("dropdown_create").value, 10);

        this.createAdmin(name, email, role);

        event.target.reset();
        return false;
    },

    /**
     * Novi administrator je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom administratoru
     */
    adminCreated: function(data) {
        var event  = new CustomEvent("Monitor.Admin.Administrator.Create");
        event.data = data;
        document.dispatchEvent(event);
        this.fetchData();
    },

    /**
     * Admin je obrisan, dohvatamo podatke
     * @param  {Object}     event           JavaScript event objekat
     */
    changedAdmin: function(event) {
        this.fetchData();
    },

    /**
     * Cuvanje informacija o postojecim adminima. Koristi se kako bi se sprecilo kreiranje novih sa imenom koje postji.
     * @param  {Object}     data            Podaci o postojecim adminima.
     */
    storeData: function(data){
        this.config.usernames   = data.administrators.map(function(value) { return value.username });
        this.config.emails      = data.administrators.map(function(value) { return value.email });
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
        return Monitor.Main.DOM.getElement("AdministratorsCreate", element, query_all, modifier);
    },

    validateName: function() {
        var elem = this.getElement("input_create");
        var error = this.config.usernames.indexOf(elem.value) !== -1 ? "Admin with this username already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },

    validateEmail: function() {
        var elem = this.getElement("input_email");
        var error = this.config.emails.indexOf(elem.value) !== -1 ? "Admin with this email already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },










    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti,nakon cega ih cuva
     * @return {Object}                     Monitor.Administrators.Create objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "Administrators",
            "fetchData",
            {},
            function(data) {
                this
                    .storeData(data)
                    .validateName()
                    .validateEmail()
                ;
            }.bind(this)

        );
    },

    /**
     * Kreiranje novog administratora
     * @param   {String}    name            Ime administratora
     * @param   {Number}    role_id         ID uloge kojoj će administrator pripadati
     * @param   {String}    email           Email administratora
     * @return  {Object}                    Monitor.Administrators.Create objekat, za ulančavanje funkcija
     */
    createAdmin: function(name, email, role_id) {
        Monitor.Main.Ajax(
            "Administrators",
            "createAdmin",
            {
                name :  name,
                email:  email,
                role :  role_id,
            },
            this.adminCreated.bind(this),
            {}
        );
    }
};

document.addEventListener('DOMContentLoaded', Monitor.Administrators.Create.init.bind(Monitor.Administrators.Create), false);
