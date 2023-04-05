"use strict";

if (Monitor            === undefined) var Monitor = {};
if (Monitor.AdminUsers === undefined) Monitor.AdminUsers = {};

/**
 * Forma za kreiranje korisnika
 */
Monitor.AdminUsers.Create = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form_create:                  "#admin_users__create__form",                   // Forma za kreiranje novog korisnika
        input_username:               "#admin_users__create__username",               // Polje za unos korisnickog imena
        input_email:                  "#admin_users__create__email",                  // Polje za unos email-a korisnika
        input_password:               "#admin_users__create__password",               // Polje za unos sifre korisnika
        input_name:                   "#admin_users__create__name",                   // Polje za unos imena korisnika
        input_surname:                "#admin_users__create__surname",                // Polje za unos prezimena korisnika
        input_address_of_living:      "#admin_users__create__address_of_living",      // Polje za unos adrese stanovanja
        input_address_of_delivery:    "#admin_users__create__address_of_delivery",    // Polje za unos adrese dostave
        input_mobile_phone:           "#admin_users__create__mobile_phone",           // Polje za unos mobilnog telefona
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
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        this.getElement("input_username")   .addEventListener("blur", this.blurName.bind(this),     false);
        this.getElement("input_email")      .addEventListener("blur", this.blurEmail.bind(this),    false);
        this.getElement("input_password")   .addEventListener("blur", this.blurPassword.bind(this), false);
        this.getElement("form_create").onsubmit = this.formSubmitted.bind(this);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUsersCreate", this.elements);
        return this;
    },








    /**
     * Provera da li korisnik sa datim email-om vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurEmail: function(event) {
        var email = event.target.value;
        if (email.length > 0) this.isEmailTaken(email);
    },

    /**
     * Provera da li korisnik sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        var username = event.target.value;
        if (username.length > 0) this.isUsernameTaken(username);
    },

    /**
     * Provera jacinu sifre
     * @param  {Object}     event           JavaScript event objekat
     */
    blurPassword: function(event) {
        var psw = event.target.value;
        var error = "";

        if      (psw.length < 6)                error = "Minimum password length is 6 characters";
        else if (psw.match(/[a-z]/) === null)   error = "Password has to contain a lowercase letter";
        else if (psw.match(/[A-Z]/) === null)   error = "Password has to contain a uppercase letter";
        else if (psw.match(/[0-9]/) === null)   error = "Password has to contain a number";
        event.target.setCustomValidity(error);
    },

    /**
     * Forma je poslata, preusmeravamo to i šaljemo zahtev AJAX-om
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Boolean}                   false, jer ne želimo da se forma pošalje klasičnim putem
     */
    formSubmitted: function(event) {
        var username            = this.getElement("input_username").value;
        var email               = this.getElement("input_email").value;
        var password            = this.getElement("input_password").value;
        var name                = this.getElement("input_name").value;
        var surname             = this.getElement("input_surname").value;
        var address_of_living   = this.getElement("input_address_of_living").value;
        var address_of_delivery = this.getElement("input_address_of_delivery").value;
        var mobile_phone        = this.getElement("input_mobile_phone").value;

        this.createUser(username, email, password, name, surname, address_of_living, address_of_delivery,  mobile_phone);

        event.target.reset();
        return false;
    },

    /**
     * Novi administrator je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom korisniku
     */
    triggerUserCreated: function(data) {
        var event       = new CustomEvent("Monitor.User");
        event.data      = data;
        event.info      = "Create";
        event.invoker   = "Monitor.AdminUsers.Create";
        document.dispatchEvent(event);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminUsersCreate", element, query_all, modifier);
    },

    /**
     * Zadaje validity za email polje, u zavisnosti da li postoji korisnik s ovim email-om
     * @param   {Boolean}   exists          Da li je email vec zauzet
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    setEmailValidity: function(exists) {
        this.getElement("input_email").setCustomValidity(exists ? "User with this email already exists" : "");
        return this;
    },

    /**
     * Zadaje validity za username polje, u zavisnosti da li postoji korisnik s ovim korisnickim imenom
     * @param   {Boolean}   exists          Da li je username vec zauzet
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    setUsernameValidity: function(exists) {
        this.getElement("input_username").setCustomValidity(exists ? "User with this username already exists" : "");
        return this;
    },










    /**
     * Provera da li vec postoji korisnik s ovim email-om
     * @param   {String}    username        Email koji proveravamo
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    isEmailTaken: function(email) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "isEmailTaken",
            {
                "email": email,
            },
            this.setEmailValidity.bind(this)
        );

        return this;
    },

    /**
     * Provera da li vec postoji korisnik s ovim korisnickim imenom
     * @param   {String}    username        Korisnicko ime koje proveravamo
     * @return  {Object}                    Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    isUsernameTaken: function(username) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "isUsernameTaken",
            {
                "username": username,
            },
            this.setUsernameValidity.bind(this)
        );
        return this;
    },

    /**
     * Kreiranje novog korisnika
     * @param   {String}    username            Korisnicko ime
     * @param   {String}    email               Email korisnika
     * @param   {String}    password            Sifra korisnika
     * @param   {String}    name                Pravo ime korisnika
     * @param   {String}    surname             Prezime korisnika
     * @param   {String}    address_of_living   Adresa stanovanja
     * @param   {String}    address_of_delivery Adresa isporuke
     * @param   {Number}    home_phone          Fiksni telefon
     * @param   {Number}    mobile_phone        Mobilni telefon
     * @param   {File}      profile_picture     Avatar
     * @return  {Object}                        Monitor.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    createUser: function(username, email, password, name, surname, address_of_living, address_of_delivery, mobile_phone) {
        var params = {
            username:             username,
            email:                email,
            password:             password,
            name:                 name,
            surname:              surname,
            address_of_living:    address_of_living,
            address_of_delivery:  address_of_delivery,
            mobile_phone:         mobile_phone,
        };


        Monitor.Main.Ajax(
            "AdminUsers",
            "createUser",
            params,
            this.triggerUserCreated.bind(this),
            {},
            true
        );

        return this;
    }
};

document.addEventListener("DOMContentLoaded", Monitor.AdminUsers.Create.init.bind(Monitor.AdminUsers.Create), false);
