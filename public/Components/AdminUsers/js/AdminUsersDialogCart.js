"use strict";

if (typeof Kirby                     === "undefined") var Kirby                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminUsers            = {};
if (typeof Kirby.AdminUsers.Dialogs  === "undefined") Kirby.AdminUsers.Dialogs    = {};

Kirby.AdminUsers.Dialogs.Cart = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        user_id: null,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "wrapper":          "#admin_users__list__cart",                      // Kompletan modal
        "modal_body":       ".admin_users__list__cart_body",
        "table_body":       ".admin_users__list__cart_table__body",
        "button_delete":    ".admin_users__dialog_cart__delete_cart",
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
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Cart objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));

        $wrapper.on("click",    this.getElementSelector("button_delete"),            this.clickDelete.bind(this));
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Cart objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminUserDialogCart", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id = parseInt(event.relatedTarget.dataset.userId, 10);
        this.config.user_id = user_id;
        this.fetchCart(user_id);
    },

    clickDelete: function(event) {
        var element = event.currentTarget;
        event.preventDefault();
        var product_id = parseInt(element.dataset.product_id, 10);
        var user_id = parseInt(element.dataset.user_id, 10);

        this.deleteCartItem(user_id, product_id);
    },











    /**
     * Zatvara modal
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Cart objekat, za ulančavanje funkcija
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
        return Kirby.Main.Dom.getElement("AdminUserDialogCart", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminUserDialogCart", element, query_all, modifier);
    },











    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisniku
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Cart objekat, za ulančavanje funkcija
     */
    render: function(data) {
        var element = this.getElement("table_body");
        element.innerHTML = "";
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
                                    class = "admin_users__dialog_cart__delete_cart"
                                    data-product_id = "${data[i].product_id}"
                                    data-user_id    = "${this.config.user_id}"
                                >
                                    Ukloni
                                </button>
                            </td>
                        </tr>`;
        }


        if (data.length === 0) {
            element.innerHTML = 'Korisnik nema ništa u korpi';
        }
        return this;
    },

    refreshData: function(data) {
        this.fetchCart(this.config.user_id);
    },










    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    user_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Cart objekat, za ulančavanje funkcija
     */
    fetchCart: function(user_id) {
        Kirby.Main.Ajax(
            "AdminUsers",
            "fetchCart",
            {
                user_id: user_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    deleteCartItem: function(user_id, product_id) {
        Kirby.Main.Ajax(
            "AdminUsers",
            "deleteCartItem",
            {
                user_id:    user_id,
                product_id: product_id,
            },
            this.refreshData.bind(this)
        );
        return this;
    },
};

document.addEventListener('DOMContentLoaded', Kirby.AdminUsers.Dialogs.Cart.init.bind(Kirby.AdminUsers.Dialogs.Cart), false);
