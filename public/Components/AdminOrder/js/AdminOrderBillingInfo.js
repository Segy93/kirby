"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminOrder            = {};

Kirby.AdminOrder.BillingInfo = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        is_shop: false,
        order_id: 0,
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        form:             "#admin_order__billing_info_form",               // Forma za izmenu
        wrapper:          "#admin_order__billing_info",                      // Kompletan modal
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
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        $wrapper.on("submit", this.getElementSelector("form"), this.formSubmited.bind(this));
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderBillingInfo", this.elements);
        return this;
    },
    /**
     * Inicijalizacija sablona
     * @return  {Object}  Kirby.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_order__address_billing_tmpl").innerHTML);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var address_id = parseInt(event.relatedTarget.previousElementSibling.value, 10);
        var order_id   = parseInt(event.relatedTarget.dataset.orderId, 10);
        this.config.order_id = order_id;
        this.fetchAddress(address_id);
    },

    /**
     * Klik na "Sacuvaj"
     * @param   {Object}    event           Javascript event objekat
     */
    formSubmited: function(event) {
        if (!this.config.is_shop) {     
            var form            = event.target;
            var elements        = form.elements;
            var address_id      = parseInt(form.dataset.address_id, 10);

            var name            = elements.billing_info__name.value;
            var surname         = elements.billing_info__surname.value;
            var company         = elements.billing_info__company.value;
            var billing_address = elements.billing_info__address.value;
            var phone_nr        = elements.billing_info__phone_nr.value;
            var city            = elements.billing_info__city.value;

            event.preventDefault();

            this
                .updateAddress(address_id, name, surname, company, billing_address, phone_nr, city)
                .hideDialog()
            ;
            form.reset();
            return false;
        }
    },










    /**
     * Zatvara modal
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");
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
        return Kirby.Main.Dom.getElement("AdminOrderBillingInfo", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderBillingInfo", element, query_all, modifier);
    },








    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data   Podaci sa informacijama o korisniku
     * @return  {Object}           Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    render: function(address) {
        var is_shop         = address.shop_id !== undefined;
        this.config.is_shop = is_shop;
        this.getElement("wrapper").innerHTML = this.templates.main({
            address:    address,
            is_shop:    is_shop,
        });

        return this;
    },










    /**
     * Azurira statistiku korisnika
     * @param   {Number}    user_id         ID korisnika kog azuriramo
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    updateAddress: function(address_id, name, surname, company, billing_address, phone_nr, city) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "updateAddress",
            {
                address_id: address_id,
                name:       name,
                surname:    surname,
                company:    company,
                address:    billing_address,
                phone_nr:   phone_nr,
                city:       city,
                order_id:   this.config.order_id,
            },
            (changed) => {
                if (changed) {
                    var event = new CustomEvent("Kirby.AdminOrder.Address.Changed");
                    document.dispatchEvent(event);
                }
            }
        );

        return this;
    },

    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    order_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}   Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    fetchAddress: function(address_id) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "fetchAddress",
            {
                address_id: address_id,
            },
            this.render.bind(this)
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminOrder.BillingInfo.init.bind(Kirby.AdminOrder.BillingInfo), false);
