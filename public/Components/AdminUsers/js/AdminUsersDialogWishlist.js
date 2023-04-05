"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminUsers            = {};
if (typeof Monitor.AdminUsers.Dialogs  === "undefined") Monitor.AdminUsers.Dialogs    = {};

Monitor.AdminUsers.Dialogs.Wishlist = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        user_id: null,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "wrapper":          "#admin_users__list__wishlist",                      // Kompletan modal
        "modal_body":       ".admin_users__list__wishlist_body",
        "table_body":       ".admin_users__list__wishlist_table__body",
        "button_delete":    ".admin_users__dialog_wishlist__delete_wish",
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
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Wishlist objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_delete"),            this.clickDelete.bind(this));
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Wishlist objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserDialogWishlist", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id         = parseInt(event.relatedTarget.dataset.userId, 10);
        this.config.user_id = user_id;
        this.fetchWishlist(user_id);
    },



    clickDelete: function(event) {
        var element = event.currentTarget;
        event.preventDefault();
        var wish_id = parseInt(element.dataset.wish_id, 10);

        this.deleteWishlistItem(wish_id);
    },









    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Wishlist objekat, za ulančavanje funkcija
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
        return Monitor.Main.DOM.getElement("AdminUserDialogWishlist", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUserDialogWishlist", element, query_all, modifier);
    },











    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisniku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Wishlist objekat, za ulančavanje funkcija
     */
    render: function(data) {
        var element = this.getElement("table_body");
        element.innerHTML = '';

        for (var i = 0, l = data.length; i < l; i++) {
            element.innerHTML += `
                        <tr>
                            <td>
                                <a
                                    href   = "${data[i].product.url}"
                                    target = "_blank">${data[i].product.name}
                                </a>
                            </td>
                            <td>
                                <button
                                    type = "submit"
                                    class = "admin_users__dialog_wishlist__delete_wish"
                                    data-wish_id = "${data[i].id}"
                                >
                                    Ukloni
                                </button>
                            </td>
                        </tr>`;
        }

        if (data.length === 0) {
            element.innerHTML = 'Korisnik nema ništa u listi želja';
        }


        return this;
    },


    refreshData: function(data) {
        this.fetchWishlist(this.config.user_id);
    },








    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    user_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Wishlist objekat, za ulančavanje funkcija
     */
    fetchWishlist: function(user_id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "fetchWishlist",
            {
                "user_id": user_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    deleteWishlistItem: function(wish_id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "deleteWishlistItem",
            {
                wish_id:    wish_id,
            },
            this.refreshData.bind(this)
        );
        return this;
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminUsers.Dialogs.Wishlist.init.bind(Monitor.AdminUsers.Dialogs.Wishlist), false);
