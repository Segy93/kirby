"use strict";

if (typeof Kirby                     === "undefined") var Kirby                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminOrder            = {};

Kirby.AdminOrder.StatusHistory = {
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
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object} Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderStatusHistory", this.elements);
        return this;
    },
    /**
     * Inicijalizacija sablona
     * @return  {Object}  Kirby.AdminOrders.List objekat, za ulančavanje funkcija
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
        return Kirby.Main.Dom.getElement("AdminOrderStatusHistory", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderStatusHistory", element, query_all, modifier);
    },








    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data   Podaci sa informacijama o korisniku
     * @return  {Object}           Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
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
     * @return  {Object}   Kirby.AdminUsers.Dialogs.Edit objekat, za ulančavanje funkcija
     */
    fetchData: function(order_id) {
        Kirby.Main.Ajax(
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

document.addEventListener("DOMContentLoaded", Kirby.AdminOrder.StatusHistory.init.bind(Kirby.AdminOrder.StatusHistory), false);
