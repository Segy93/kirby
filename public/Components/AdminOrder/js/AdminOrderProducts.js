"use strict";

if (typeof Kirby             === "undefined") var Kirby                 = {};
if (typeof Kirby.AdminOrderPage  === "undefined") Kirby.AdminOrder      = {};

/**
 * Pretraga i prikaz narudzbina,
 * Izmena podataka
 * Statistika
 * Blokiranje naloga
 * Brisanje
 */
Kirby.AdminOrder.Products = {
    config: {
    },

    elements: {
        status_form:          ".admin_order__status",
        dropdown_status:      ".admin_order__products_statuses",     // Padajuci meni za promenu statusa
        status_notify:        ".admin_order__status_sent_notify",
        table:                ".admin_order__products_table",
        wrapper:              "#admin_order__products_content",
        button_delete:        ".admin_order__products_delete",
        quantity_change:      ".admin_order__product_quantity",
        price:                ".admin_order__product_price",
        shipping:             ".admin_order__product_shipping",
        total_price:          ".admin_order__product_price_total",
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function() {},
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initTemplates()
            .fetchData()
            .initListeners()
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * Prvo idu osluskivaci na wrapera onda idu van wrapera i nakon toga idu sa uslovima
     * @return  {Object}                 Kirby.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        var form    = this.getElement("status_form");
        form.addEventListener("submit", this.statusFormSubmited.bind(this), false);
        //$wrapper.on("click", this.getElementSelector("button_delete"), this.clickDeleteProduct.bind(this));
        $wrapper.on("change", this.getElementSelector("quantity_change"), this.quantityChanged.bind(this));
        document.addEventListener("Kirby.AdminOrder.AddProduct.Added", this.productAdded.bind(this), false);
        document.addEventListener("Kirby.OrderProduct", this.productChanged.bind(this), false);
        document.addEventListener("Kirby.Order.Address.Update", this.addressChanged.bind(this));

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}  Kirby.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_order__products_tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object} Kirby.AdminOrders.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderProducts", this.elements);
        return this;
    },

    productAdded: function (event) {
        this.fetchData();
    },

    productChanged: function(event) {
        this.fetchData();
    },

    /**
    * Poslata forma za izmenu statusa
    * @param event js event objekat
    */
    statusFormSubmited: function(event) {
        event.preventDefault();

        var form        = event.currentTarget;
        var elements    = form.elements;
        var notify      = elements.notify.checked;
        this.changeStatus(
            form.dataset.order_id,
            elements.status.value,
            elements.message.value,
            notify
        );

        return false;
    },

    /**
    * Pritisnuto dugme za prisanje proizvoda
    * @param event js event objekat
    */
    clickDeleteProduct: function(event) {
        var order_product_id = event.currentTarget.dataset.order_product_id;

        this.deleteProduct(order_product_id);
    },

    /**
    * Izmenjena kolicina narucenog proizvoda
    * @param event js event objekat
    */
    quantityChanged: function(event) {
        var order_id   = event.currentTarget.dataset.order_id;
        var product_id = event.currentTarget.dataset.product_id;
        var quantity   = event.currentTarget.value;

        this.quantityChange(order_id, product_id, quantity);
    },

    addressChanged: function() {
        this.fetchData();
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element     Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all   Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier    BEM modifier za selektor
     * @return  {Node/NodeList}         Vraca Node objekat ukoliko je
     * query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminOrderProducts", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element    Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all  Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier   BEM modifier za selektor
     * @return  {Node/NodeList}        Vraca Node objekat ukoliko je query_all false,
     * niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderProducts", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     orders            Prosledjeni podaci
     */
    render: function(order) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            order:    order,
        });

        this.getElement("price").innerHTML          = order.total_price_formatted + ' RSD';
        this.getElement("shipping").innerHTML       = order.shipping_fee_formatted + ' RSD';
        this.getElement("total_price").innerHTML    = this.formatPrice(order.total_price + order.shipping_fee);
        return this;
    },

    renderStatus: function(data) {
        var element = this.getElement("status_notify");

        if (data) {
            element.innerHTML = "<p style = 'color:green;' >Uspešno promenjen status</p>";
        }

        setTimeout(function() { 
            element.innerHTML = "";
        }, 5000);
    },










    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}      Kirby.AdminOrder.Products objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        var table = this.getElement("table");
        var order_id = parseInt(table.dataset.orderId, 10);
        Kirby.Main.Ajax(
            "AdminOrder",
            "fetchData",
            {
                order_id: order_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    /**
    * Poziva metodu za brisanje proizvoda iz narudzbine
    * @param  order_product_id identifikator proizvoda u narudzbini
    * @return {Object}      Kirby.AdminOrder.Products objekat, za ulancavanje funkcija
    */
    deleteProduct: function(order_product_id) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "deleteOrderProduct",
            {
                order_product_id: order_product_id
            },
            this.fetchData.bind(this)
        );

        return this;
    },

    /**
    * Poziva metodu za menjanje statusa narudzbine
    * @param {int}      order_id identifikator narudzbine
    * @param {String}   status   novi status naruzbine
    * @param {String}   message  poruka uz promenu statusa
    * @param {Boolean}  notify   true obavestava korisnika false ne obavestava
    * @return {Object}      Kirby.AdminOrder.Products objekat, za ulancavanje funkcija
    */
    changeStatus: function(order_id, status, message, notify) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "changeStatus",
            {
                order_id:   order_id,
                status:     status,
                message:    message,
                notify:     notify,
            },
            this.renderStatus.bind(this)
        );

        return this;
    },

    /**
    * Poziva metodu za izmenu kolicine proizvoda u narudzbini
    * @param {int} order_id     identifikator narudzbine
    * @param {int} product_id   identifikator proizvoda
    * @param {int} quantity     kolicina proizvoda
    * @return {Object}      Kirby.AdminOrder.Products objekat, za ulancavanje funkcija
    */
    quantityChange: function(order_id, product_id, quantity) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "quantityChange",
            {
                order_id:   order_id,
                product_id: product_id,
                quantity:   quantity
            },
            this.fetchData.bind(this)
        );
        return this;
    },

    formatPrice: function(price) {
        if ("Intl" in window) {
            var options = {
                style: "currency",
                minimumFractionDigits: 2,
                currency: "RSD",
            };
            return new Intl.NumberFormat('sr-RS', options).format(price);
        } else {
            return price;
        }
    },


};

document.addEventListener("DOMContentLoaded", Kirby.AdminOrder.Products.init.bind(Kirby.AdminOrder.Products), false);
