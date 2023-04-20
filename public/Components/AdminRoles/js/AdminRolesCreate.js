"use strict";

if (Kirby === undefined) window.Kirby = {};
if (Kirby.AdminRoles === undefined) window.Kirby.AdminRoles = {};

Kirby.AdminRoles.Create = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        names: [],
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "form_create": "#admin_roles__create__form", // Forma za kreiranje nove uloge
        "input_create": "#admin_roles__create__input", // Polje za unos imena uloge
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
     * @return  {Object}                    Kirby.AdminRoles.Create objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var form_create = this.getElement("form_create");
        if(form_create !== null) form_create.onsubmit = this.formSubmitted.bind(this);

        var input_create = this.getElement("input_create");
        if(input_create !== null) input_create.addEventListener("blur", this.blurName.bind(this), false);

        document.addEventListener("Kirby.Role.Update", this.changedRole.bind(this), false);
        document.addEventListener("Kirby.Role.Delete", this.changedRole.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminRoles.Create objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminRolesCreate", this.elements);
        return this;
    },










    /**
     * Provera da li uloga sa datim imenom vec postoji kada admin pokusa da kreira novu ulogu
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        this.validateName();
        var elem = event.target;
        var error = this.config.names.indexOf(elem.value) !== -1 ? "Role with this description already exists" : "";
        elem.setCustomValidity(error);
    },

    /**
     * Forma je poslata, preusmeravamo to i šaljemo zahtev AJAX-om
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Boolean}                   false, jer ne želimo da se forma pošalje klasičnim putem
     */
    formSubmitted: function(event) {
        var name = this.getElement("input_create").value;

        this.createRole(name);

        event.target.reset();
        return false;
    },

    /**
     * Nova uloga je kreirana, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom predmetu
     */
    roleCreated: function(data) {
        var event = new CustomEvent("Kirby.Admin.Role.Create");
        event.data = data;
        document.dispatchEvent(event);
        this.fetchData();
    },

    /**
     * Uloga je obrisan, dohvatamo podatke
     * @param  {Object}     event           JavaScript event objekat
     */
    changedRole: function(event) {
        this.fetchData();
    },

    /**
     * Cuvanje informacija o postojecim ulogama. Koristi se kako bi se sprecilo kreiranje novih sa imenom koje postji.
     * @param  {Object}     data            Podaci o postojecim predmetima.
     */
    storeData: function(data){
        this.config.names   = data.roles.map(function(value) { return value.description });
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
        return Kirby.Main.Dom.getElement("AdminRolesCreate", element, query_all, modifier);
    },

    validateName: function() {
        var elem = this.getElement("input_create");
        var error = this.config.names.indexOf(elem.value) !== -1 ? "Role with this description already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },










    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti,nakon cega ih cuva
     * @return {Object}                     Kirby.AdminRoles.Create objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Kirby.Main.Ajax(
            "AdminRoles",
            "fetchData",
            {},
            function(data) {
                this
                    .storeData(data)
                    .validateName()
                ;
            }.bind(this)
        );
    },

    /**
     * Kreiranje nove uloge
     * @param   {String}    name            Ime predmeta
     * @return {Object}                     Kirby.AdminRoles.Create objekat, za ulancavanje funkcija
    **/
    createRole: function(name) {
        Kirby.Main.Ajax(
            "AdminRoles",
            "createRole",
            {
                "name": name,
            },
            this.roleCreated.bind(this),
            {}
        );
    }

};

document.addEventListener('DOMContentLoaded', Kirby.AdminRoles.Create.init.bind(Kirby.AdminRoles.Create), false);
