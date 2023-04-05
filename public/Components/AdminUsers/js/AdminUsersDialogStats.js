"use strict";

if (typeof Monitor                     === "undefined") var Monitor                   = {};
if (typeof Monitor.AdminUsers          === "undefined") Monitor.AdminUsers            = {};
if (typeof Monitor.AdminUsers.Dialogs  === "undefined") Monitor.AdminUsers.Dialogs    = {};

Monitor.AdminUsers.Dialogs.Stats = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "badge_image":      "#admin_users__modal_stats__badge_image",       // Slicica nivoa
        "badge_name":       "#admin_users__modal_stats__badge_name",        // Naziv nivoa
        "badge_progress":   "#admin_users__modal_stats__badge_progress",    // Koliko je presao u okviru nivoa


        "info_active":      "#admin_users__modal_stats__activated",         // Da li je korisnik potvrdio mejl
        "info_registered":  "#admin_users__modal_stats__registered",        // Kada je registrovan
        "info_last_visit":  "#admin_users__modal_stats__last_visit",        // Kada je poslednji put posetio
        "info_time_spent":  "#admin_users__modal_stats__time_spent",        // Koliko je vremena proveo na sajtu


        "form":             "#admin_users__modal_stats__form",              // Forma za izmenu
        "title":            "#admin_users__modal_stats__label",             // Naslov
        "wrapper":          "#admin_users__modal_stats"                     // Kompletan modal
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
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserDialogStats", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id = parseInt(event.relatedTarget.dataset.userId, 10);
        this.fetchStats(user_id);
    },

    /**
     * Klik na "Sacuvaj"
     * @param   {Object}    event           Javascript event objekat
     */
    submitChanges: function(event) {
        var form            = event.target;
        var elements        = form.elements;
        var user_id         = parseInt(elements.user_id.value, 10);
        var xp              = parseInt(elements.xp.value, 10);
        var points          = parseFloat(elements.points.value, 10);
        var energy_amount   = parseInt(elements.energy_amount.value, 10);
        var energy_refill   = elements.energy_refill.value;
        this
            .updateUser(user_id, xp, points, energy_amount, energy_refill)
            .hideDialog()
        ;
        form.reset();
        return false;
    },










    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
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
        return Monitor.Main.DOM.getElement("AdminUserDialogStats", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUserDialogStats", element, query_all, modifier);
    },










    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisniku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
     */
    render: function(data) {
        this.getElement("title").textContent = data.username;


        this.getElement("badge_image").src              = "/uploads_admin/original/" + data.badge_image;
        this.getElement("badge_name").textContent       = data.badge_name;
        this.getElement("badge_progress").value         = data.badge_progress;


        this.getElement("info_active").textContent      = data.activated ? "Da" : "Ne";
        this.getElement("info_registered").textContent  = data.registered.date;
        this.getElement("info_last_visit").textContent  = data.last_visit;
        this.getElement("info_time_spent").textContent  = data.time_spent;


        var elements = this.getElement("form").elements;
        elements.energy_amount.value   = data.energy_amount;
        elements.energy_refill.value   = data.energy_refill;
        elements.points.value          = data.points;
        elements.user_id.value         = data.user_id;
        elements.xp.value              = data.xp;

        return this;
    },










    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    user_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
     */
    fetchStats: function(user_id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "fetchStatsUser",
            {
                "user_id": user_id,
            },
            this.render.bind(this)
        );
        return this;
    },

    /**
     * Azurira statistiku korisnika
     * @param   {Number}    user_id         ID korisnika kog azuriramo
     * @param   {Number}    xp              Kolicina iskustva
     * @param   {Number}    points          Koliko poena ima
     * @param   {Number}    energy_amount   Koliko energije ima
     * @param   {String}    energy_refill   MySQL datetime string
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Stats objekat, za ulančavanje funkcija
     */
    updateUser: function(user_id, xp, points, energy_amount, energy_refill) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "updateStats",
            {
                "user_id": user_id,
                "xp": xp,
                "points": points,
                "energy_amount": energy_amount,
                "energy_refill": energy_refill,
            },
            function(data) {
                var event = new CustomEvent("Monitor.User");
                event.info = "Update.Stats";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminUsers.Dialogs.Stats.init.bind(Monitor.AdminUsers.Dialogs.Stats), false);
