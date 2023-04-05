"use strict";

if (Monitor            === undefined) var Monitor = {};
if (Monitor.AdminUsers === undefined) Monitor.AdminUsers = {};

Monitor.AdminUsers.Stats = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
            "users_info":                       "#admin_users__stats",
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function(){},
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initTemplates()
            .initListeners()
            .fetchData()

        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @return  {Object}                    Monitor.AdminUsers.Stats objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        document.addEventListener("Monitor.User", this.fetchData.bind(this), false);
        return this;
    },

    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_users__users_info__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.Stats objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUserStats", this.elements);
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
        return Monitor.Main.DOM.getElement("AdminUserStats", element, query_all, modifier);
    },








    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisnicima
     * @return  {Object}                    Monitor.AdminUsers.Stats objekat, za ulančavanje funkcija
     */
    render: function(data) {
        this.getElement("users_info").innerHTML = this.templates.main({
            "nrUsers":          data.nrUsers,
            "nrUsersCurrent":   data.nrUsersCurrent,
            "nrUsersBanned":    data.nrUsersBanned,
        });

        return this;
    },










    /**
     * Dohvata informacije o korisnicima koliko ih je ima registrovanih, banovanih i aktivno
     * @return array data
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminUsers",
            "fetchStats",
            {},
            this.render.bind(this)
        );
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminUsers.Stats.init.bind(Monitor.AdminUsers.Stats), false);