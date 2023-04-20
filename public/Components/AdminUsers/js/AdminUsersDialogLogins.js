"use strict";

if (typeof Kirby                     === "undefined") var Kirby                   = {};
if (typeof Kirby.AdminUsers          === "undefined") Kirby.AdminUsers            = {};
if (typeof Kirby.AdminUsers.Dialogs  === "undefined") Kirby.AdminUsers.Dialogs    = {};

Kirby.AdminUsers.Dialogs.Logins = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "content":  "#admin_users__modal_logins__content",
        "wrapper":  "#admin_users__modal_logins"            // Kompletan modal
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
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Logins objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_users__modal_logins__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Logins objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminUserDialogLogins", this.elements);
        return this;
    },










    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var user_id = parseInt(event.relatedTarget.dataset.userId, 10);
        this.fetchData(user_id);
    },










    /**
     * Zatvara modal
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Logins objekat, za ulančavanje funkcija
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
        return Kirby.Main.Dom.getElement("AdminUserDialogLogins", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminUserDialogLogins", element, query_all, modifier);
    },










    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param   {Object}    data            Podaci sa informacijama o korisniku
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Logins objekat, za ulančavanje funkcija
     */
    render: function(data) {
        this.getElement("content").innerHTML = this.templates.main({"logins": data});
        return this;
    },










    /**
     * Dohvata informacije o korisniku
     * @param   {Number}    user_id         ID korisnika za koga dohvatamo statistiku
     * @return  {Object}                    Kirby.AdminUsers.Dialogs.Logins objekat, za ulančavanje funkcija
     */
    fetchData: function(user_id) {
        Kirby.Main.Ajax(
            "AdminUsers",
            "fetchUserLogs",
            {
                "user_id": user_id,
            },
            this.render.bind(this)
        );
        return this;
    },
};

document.addEventListener('DOMContentLoaded', Kirby.AdminUsers.Dialogs.Logins.init.bind(Kirby.AdminUsers.Dialogs.Logins), false);
