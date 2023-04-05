"use strict";

if (Monitor            === undefined) var Monitor = {};
if (Monitor.AdminUserAddress === undefined) Monitor.AdminUserAddress = {};

/**
 * Forma za kreiranje korisnika
 */
Monitor.AdminUserAddress.Create = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form_create:                  "#admin_addresses__create__form",
        input_contact_name:           "#admin_addresses__create__contact_name",
        input_contact_surname:        "#admin_addresses__create__contact_surname",
        input_company:                "#admin_addresses__create__company",
        input_pib:                    "#admin_addresses__create__pib",
        input_phone:                  "#admin_addresses__create__phone",
        input_address_of_living:      "#admin_addresses__create__address_of_living",
        input_post_code:              "#admin_addresses__create__post_code",
        input_city:                   "#admin_addresses__create_city",
        userId:                       ".admin_addresses__create__user_id",
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
     * @return  {Object}                    Monitor.AdminUserAddress.Create objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        this.getElement("form_create").onsubmit = this.formSubmitted.bind(this);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUserAddress.Create objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserAddressCreate", this.elements);
        return this;
    },


    /**
     * Forma je poslata, preusmeravamo to i šaljemo zahtev AJAX-om
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Boolean}                   false, jer ne želimo da se forma pošalje klasičnim putem
     */
    formSubmitted: function(event) {
        var user_id                     = this.getElement("userId").dataset.userId;
        var input_contact_name          = this.getElement("input_contact_name").value;
        var input_contact_surname       = this.getElement("input_contact_surname").value;
        var input_company               = this.getElement("input_company").value;
        var input_phone                 = this.getElement("input_phone").value;
        var input_address_of_living     = this.getElement("input_address_of_living").value;
        var input_post_code             = this.getElement("input_post_code").value;
        var input_city                  = this.getElement("input_city").value;
        var input_pib                   = this.getElement("input_pib").value;

        this.createAddress(user_id, input_contact_name, input_contact_surname, input_company, input_phone, input_address_of_living, input_post_code, input_city, input_pib);
        event.preventDefault;
        event.target.reset();
        return false;
    },

    /**
     * Novi administrator je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom korisniku
     */
    triggerAddressCreated: function(data) {
        var event       = new CustomEvent("Monitor.UserAddresses");
        event.data      = data;
        event.info      = "Create";
        event.invoker   = "Monitor.AdminUserAddress.Create";
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
        return Monitor.Main.DOM.getElement("AdminUserAddressCreate", element, query_all, modifier);
    },

    /**
     * Zadaje validity za email polje, u zavisnosti da li postoji korisnik s ovim email-om
     * @param   {Boolean}   exists          Da li je email vec zauzet
     * @return  {Object}                    Monitor.AdminUserAddress.Create objekat, za ulančavanje funkcija
     */
    setEmailValidity: function(exists) {
        this.getElement("input_email").setCustomValidity(exists ? "User with this email already exists" : "");
        return this;
    },

    createAddress: function(user_id, input_contact_name, input_contact_surname, input_company, input_phone, input_address_of_living, input_post_code, input_city) {
        var params = {
            user_id:              user_id,
            contact_name:         input_contact_name,
            contact_surname:      input_contact_surname,
            company:              input_company,
            phone:                input_phone,
            address:              input_address_of_living,
            post_code:            input_post_code,
            city:                 input_city,
            pib:                  input_pib,
        };


        Monitor.Main.Ajax(
            "AdminUserAddresses",
            "createAddress",
            params,
            this.triggerAddressCreated.bind(this),
            {},
            true
        );

        return this;
    }
};

document.addEventListener("DOMContentLoaded", Monitor.AdminUserAddress.Create.init.bind(Monitor.AdminUserAddress.Create), false);
