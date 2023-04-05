"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminUsers            = {};
if (typeof Monitor.AdminUsers.Dialogs  === "undefined") Monitor.AdminUsers.Dialogs    = {};

Monitor.AdminUsers.Dialogs.Edit = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form:             "#admin_users__modal_edit__form",               // Forma za izmenu
        wrapper:          "#admin_users__modal_edit",                      // Kompletan modal
        input_name:       "#admin_users__modal_edit__username",
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
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        this.getElement("input_name").addEventListener("blur", this.blurName.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserDialogEdit", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id = parseInt(event.relatedTarget.dataset.userId, 10);
        this.fetchUser(user_id);
    },

    /**
     * Klik na "Sacuvaj"
     * @param   {Object}    event           Javascript event objekat
     */
    submitChanges: function(event) {
        var form            = event.target;
        var elements        = form.elements;
        var user_id         = parseInt(elements.user_id.value, 10);

        var username            = elements.username.value;
        var email               = elements.email.value;
        var name                = elements.name.value;
        var surname             = elements.surname.value;
        // var address_of_living   = elements.address_of_living.value;
        // var address_of_delivery = elements.address_of_delivery.value;
        var mobile              = elements.mobile_phone.value;

        this
            .updateUser(user_id, username, email, name, surname, /* address_of_living, address_of_delivery, */mobile)
            .hideDialog()
        ;
        form.reset();
        return false;
    },
    /**
     * Provera da li tag sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        var username = event.target.value;
        if (username !== event.target.dataset.original && username.length > 0) this.isUsernameTaken(username);
    },










    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
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
        return Monitor.Main.DOM.getElement("AdminUserDialogEdit", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUserDialogEdit", element, query_all, modifier);
    },
    /**
     * Zadaje validity za name polje, u zavisnosti da li postoji korisnik s ovim korisnickim imenom
     * @param   {Boolean}   exists          Da li je username vec zauzet
     * @return  {Object}                    Monitor.AdminTags.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("input_name").setCustomValidity(exists ? "User with this username already exists" : "");
        return this;
    },










    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisniku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    render: function(data) {
        var elements = this.getElement("form").elements;

        elements.user_id.value              = data.id;
        elements.username.value             = data.username;
        elements.username.dataset.original  = data.username;
        elements.email.value                = data.email;
        elements.name.value                 = data.name;
        elements.surname.value              = data.surname;
        // elements.address_of_living.value    = data.address_home;
        // elements.address_of_delivery.value  = data.address_delivery;
        elements.mobile_phone.value         = data.phone_nr;

        return this;
    },










    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    user_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    fetchUser: function(user_id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "fetchUser",
            {
                user_id: user_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    /**
     * Azurira statistiku korisnika
     * @param   {Number}    user_id         ID korisnika kog azuriramo
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    updateUser: function(user_id, username, email, name, surname, /* address_of_living, address_of_delivery, */ phone, mobile) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "updateInfo",
            {
                user_id:        user_id,
                username:       username,
                email:          email,
                name:           name,
                surname:        surname,
                // "address_of_living": address_of_living,
                // "address_of_delivery": address_of_delivery,
                home_phone:     phone,
                mobile_phone:   mobile,
            },
            (data) => {
                var event = new CustomEvent("Monitor.User");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },

    /**
    * Provera da li vec postoji tag s ovim korisnickim imenom
    * @param   {String}    username        Ime koje proveravamo
    * @return  {Object}                    Monitor.AdminTags.Change objekat, za ulančavanje funkcija
    */
    isUsernameTaken: function(username) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "isUsernameTaken",
            {
                "username": username,
            },
            this.setNameValidity.bind(this)
        );
        return this;
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminUsers.Dialogs.Edit.init.bind(Monitor.AdminUsers.Dialogs.Edit), false);
