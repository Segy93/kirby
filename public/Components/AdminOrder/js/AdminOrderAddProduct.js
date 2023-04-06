"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminOrder            = {};

Kirby.AdminOrder.AddProduct = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        order_id: 0,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form:             "#admin_order__product_add",
        wrapper:          "#admin_order__add_product__wrapper",
        model_wrapper:    "#admin_order__add_product",
        input_search:     "#admin_order__find_product",
        quantity:         ".admin_order__find_product__single",
        find_form:        ".admin_order__add_product__find",
    },


    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function() {},
    },








    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function() {
        this
            .registerElements()
            .initTemplates()
            .initListeners()
            .setTemplate()
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @return  {Object}  Kirby.AdminOrder.AddProduct objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("model_wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("wrapper").addEventListener("submit", this.formSubmited.bind(this), false);
        this.getElement("find_form").addEventListener("submit", this.inputedSearch.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}   Kirby.AdminOrder.AddProduct objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderPageAddProduct", this.elements);
        return this;
    },
    /**
     * Inicijalizacija sablona
     * @return  {Object}   Kirby.AdminOrder.AddProduct objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_order__add_products_tmpl").innerHTML);
        return this;
    },

    setTemplate: function() {
        this.getElement("wrapper").innerHTML = this.templates.main();
    },







    /**
    * Poziva funkciju za obradu pretrage nakon unosa u polje za pretragu
    * @param {event} JS-objekat dogadjaja
    */
    inputedSearch: function(event) {
        event.preventDefault();
        var form = event.currentTarget;
        var query = form.elements.admin_order__find_product.value;

        this.findProducts(query, this.config.order_id);
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var order_id = parseInt(event.relatedTarget.dataset.order_id, 10);

        this.config.order_id = order_id;
    },
    /**
     * Klik na "Sacuvaj"
     * @param   {Object}    event           Javascript event objekat
     */
    formSubmited: function(event) {
        if (this.getElement("quantity")) {
            var elements = this.getElement("quantity", true);

            elements.forEach((element) => {
                var value = parseInt(element.value, 10);
                if (value > 0) {
                    var product_id = element.dataset.product_id;
                    var quantity   = value;
                    var order_id   = this.config.order_id;

                    this.addProduct(order_id, product_id, quantity);
                }
            });
        }

        event.preventDefault();
        this.hideDialog();
        return false;
    },










    /**
     * Zatvara modal
     * @return  {Object}  Kirby.AdminOrder.AddProduct objekat, za ulančavanje funkcija
     */
    hideDialog: function() {
        $(this.getElement("model_wrapper")).modal("hide");
        return this;
    },

    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminOrderPageAddProduct", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderPageAddProduct", element, query_all, modifier);
    },









    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    queried_products   Podaci sa trazenim proizvodima
     * @return  {Object}           Kirby.AdminOrder.AddProduct objekat, za ulančavanje funkcija
     */
    render: function(queried_products) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            queried_products:    queried_products,
        });

        return this;
    },







    /**
    * Dodaje proizvod u listu proizvoda za datu narudzbinu
    * @param order_id identifikator za narudzbinu
    * @param product_id identifikator za proizvod
    * @param quantity  kolicina proizvoda
    * @return  Kirby.AdminOrder.AddProduct objekat za ulancavanje funkcija
    */
    addProduct: function(order_id, product_id, quantity) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "addProduct",
            {
                order_id:   order_id,
                product_id: product_id,
                quantity:   quantity,
            },
            (added) => {
                if (added) {
                    var event = new CustomEvent("Kirby.AdminOrder.AddProduct.Added");
                    document.dispatchEvent(event);
                }
            }
        );

        return this;
    },

    /**
    * Poziva metodu za pronalazenje svih proizvoda zadatog upita
    * @param query-upit
    * @return  Kirby.AdminOrder.AddProduct objekat za ulancavanje funkcija
    */
    findProducts: function(query, order_id) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "findProducts",
            {
                query: query,
                order_id: order_id,
            },
            this.render.bind(this)
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminOrder.AddProduct.init.bind(Kirby.AdminOrder.AddProduct), false);
