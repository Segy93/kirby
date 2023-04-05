"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminOrder            = {};

Monitor.AdminOrder.StatusHistory = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        wrapper:          "#admin_orders__status_history",                      // Kompletan modal
        body:             ".admin_orders__status_history_body",
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
     * @return  {Object} Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object} Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminOrderStatusHistory", this.elements);
        return this;
    },
    /**
     * Inicijalizacija sablona
     * @return  {Object}  Monitor.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_orders__status_history_tmpl").innerHTML);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var order_id = parseInt(event.relatedTarget.dataset.order_id, 10);
        this.fetchData(order_id);
    },










    /**
     * Zatvara modal
     * @return  {Object} Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
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
        return Monitor.Main.DOM.getElement("AdminOrderStatusHistory", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminOrderStatusHistory", element, query_all, modifier);
    },








    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data   Podaci sa informacijama o korisniku
     * @return  {Object}           Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    render: function(statuses) {
        this.getElement("body").innerHTML = this.templates.main({
            statuses:    statuses,
        });

        return this;
    },










    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    order_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}   Monitor.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    fetchData: function(order_id) {
        Monitor.Main.Ajax(
            "AdminOrder",
            "fetchOrderStatuses",
            {
                order_id: order_id,
            },
            this.render.bind(this)
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminOrder.StatusHistory.init.bind(Monitor.AdminOrder.StatusHistory), false);
