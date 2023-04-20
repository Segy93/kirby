"use strict";

if (Kirby            === undefined) var Kirby = {};
if (Kirby.AdminUsers === undefined) Kirby.AdminUsers = {};

Kirby.AdminUsers.Stats = {
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
     * @return  {Object}                    Kirby.AdminUsers.Stats objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        document.addEventListener("Kirby.User", this.fetchData.bind(this), false);
        return this;
    },

    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_users__users_info__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminUsers.Stats objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminUserStats", this.elements);
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
        return Kirby.Main.Dom.getElement("AdminUserStats", element, query_all, modifier);
    },








    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisnicima
     * @return  {Object}                    Kirby.AdminUsers.Stats objekat, za ulančavanje funkcija
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
        Kirby.Main.Ajax(
            "AdminUsers",
            "fetchStats",
            {},
            this.render.bind(this)
        );
    },
};

document.addEventListener('DOMContentLoaded', Kirby.AdminUsers.Stats.init.bind(Kirby.AdminUsers.Stats), false);