"use strict";

if (typeof Kirby                     === "undefined") var Kirby                   = {};
if (typeof Kirby.AdminOrders          === "undefined") Kirby.AdminOrders            = {};
if (typeof Kirby.AdminOrders.Products  === "undefined") Kirby.AdminOrders.Products    = {};

Kirby.AdminOrders.Products.Delete = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        product_id: null,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        button_confirm:   "#admin_order__modal_delete__confirm",  // Forma za izmenu
        wrapper:          "#admin_order__modal_delete",            // Kompletan modal
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
     * @return  {Object}                    
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderProductsDelete", this.elements);
        return this;
    },










    /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteProduct();
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var product_id = parseInt(event.relatedTarget.dataset.orderProductId, 10);
        this.setProductID(product_id);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminOrderProductsDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderProductsDelete", element, query_all, modifier);
    },










    /**
     * Dohvata ID trenutnog proizvoda
     * @return  {Number}                    ID proizvoda
     */
    getProductID: function() {
        return this.config.product_id;
    },

    /**
     * Zadaje ID trenutnog proizvoda
     * @return  {Object}                    
     */
    setProductID: function(product_id) {
        this.config.product_id = product_id;
        return this;
    },










    /**
     * Brise trenutnog proizvoda
     * @return  {Object}                    
     */
    deleteProduct: function() {
        Kirby.Main.Ajax(
            "AdminOrder",
            "deleteOrderProduct",
            {
                product_id: this.getProductID(),
            },
            (data) => {
                var event = new CustomEvent("Kirby.OrderProduct");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminOrders.Products.Delete.init.bind(Kirby.AdminOrders.Products.Delete), false);
