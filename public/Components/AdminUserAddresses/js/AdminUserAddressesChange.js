"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminUsers            = {};
if (typeof Monitor.AdminUsersAddresses === "undefined") Monitor.AdminUsersAddresses   = {};

Monitor.AdminUsersAddresses.Change= {

    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "form":             "#admin_address__modal_edit__form",               // Forma za izmenu
        "wrapper":          "#admin_address__modal_edit",                      // Kompletan modal
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
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminAddress.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserAddressesChange", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var address_id = parseInt(event.relatedTarget.dataset.addressId, 10);
        this.fetchData(address_id);
    },

    /**
     * Klik na "Sacuvaj"
     * @param   {Object}    event           Javascript event objekat
     */
    submitChanges: function(event) {
        var form                = event.target;
        var elements            = form.elements;
        var address_id          = parseInt(elements.user_id.value, 10);

        var contact_name        = elements.contact_name.value;
        var contact_surname     = elements.contact_surname.value;
        var company             = elements.company.value;
        var phone_nr            = elements.phone_nr.value;
        var address             = elements.address.value;
        var postal_code         = elements.postal_code.value;
        var country             = parseInt(elements.country.value, 10);
        var pib                 = parseInt(elements.pib.value, 10);

        this
            .updateAddress(address_id, contact_name, contact_surname, company, phone_nr, address, postal_code, country, pib)
            .hideDialog()
        ;
        form.reset();
        return false;
    },










    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminAdress.Dialogs.Edit objekat, za ulančavanje funkcija
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
        return Monitor.Main.DOM.getElement("AdminUserAddressesChange", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUserAddressesChange", element, query_all, modifier);
    },










    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o adresama
     * @return  {Object}
     */
    render: function(data) {
        var elements = this.getElement("form").elements;

        elements.user_id.value              = data.id;
        elements.contact_name.value         = data.contact_name;
        elements.contact_surname.value      = data.contact_surname;
        elements.company.value              = data.company;
        elements.phone_nr.value             = data.phone_nr;
        elements.address.value              = data.address;
        elements.postal_code.value          = data.postal_code;
        elements.country.value              = data.city;
        elements.pib.value                  = data.pib;

        return this;
    },










    /**
     * Dohvata informacije o adresama
     * @param   {Number}    address_id         ID adrese koju dohvatamo
     * @return  {Object}                    Monitor.AdminAdress.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    fetchData: function(address_id) {
        Monitor.Main.Ajax(
            "AdminUserAddresses",
            "getAddressById",
            {
                address_id: address_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    /**
     * Azurira statistiku korisnika
     * @param   {Number}    address_id         ID korisnika kog azuriramo
     * @return  {Object}                    Monitor.AdminAddress.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    updateAddress: function(address_id, contact_name, contact_surname, company, phone_nr, address, postal_code, country, pib) {
        Monitor.Main.Ajax(
            "AdminUserAddresses",
            "updateAddress",
            {
                address_id:         address_id,
                contact_name:       contact_name,
                contact_surname:    contact_surname,
                company:            company,
                phone_nr:           phone_nr,
                address:            address,
                postal_code:        postal_code,
                country:            country,
                pib:                pib
            },
            (data) => {
                var event = new CustomEvent("Monitor.UserAddresses");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },

};



document.addEventListener('DOMContentLoaded', Monitor.AdminUsersAddresses.Change.init.bind(Monitor.AdminUsersAddresses.Change), false);


