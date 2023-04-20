"use strict";

if (typeof Kirby                     === "undefined") var Kirby                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminUsers            = {};
if (typeof Kirby.AdminUsersAddresses  === "undefined") Kirby.AdminUsersAddresses    = {};

Kirby.AdminUsersAddresses.Delete = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        address_id: null,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        button_confirm:   "#admin_address__modal_delete__confirm",  // Forma za izmenu
        wrapper:          "#admin_address__modal_delete"            // Kompletan modal
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
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminUserAddressesDelete", this.elements);
        return this;
    },










    /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteUser();
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var address_id = parseInt(event.relatedTarget.dataset.addressId, 10);
        this.setAddressID(address_id);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminUserAddressesDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminUserAddressesDelete", element, query_all, modifier);
    },










    /**
     * Dohvata ID trenutnog korisnika
     * @return  {Number}                    ID korisnika
     */
    getAddressID: function() {
        return this.config.address_id;
    },

    /**
     * Zadaje ID trenutnog korisnika
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    setAddressID: function(address_id) {
        this.config.address_id = address_id;
        return this;
    },










    /**
     * Brise trenutnog korisnika
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    deleteUser: function() {
        Kirby.Main.Ajax(
            "AdminUserAddresses",
            "deleteAddress",
            {
                address_id: this.getAddressID(),
            },
            (data) => {
                var event = new CustomEvent("Kirby.UserAddresses");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminUsersAddresses.Delete.init.bind(Kirby.AdminUsersAddresses.Delete), false);
