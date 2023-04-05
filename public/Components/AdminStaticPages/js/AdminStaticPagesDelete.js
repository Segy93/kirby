"use strict";

if(typeof Monitor                 === "undefined") var Monitor             = {};
if(typeof Monitor.AdminPagesStatic === "undefined") Monitor.AdminPagesStatic = {};

Monitor.AdminPagesStatic.Delete = {











    config:{
        user_id: null,
    },

    elements:{
        "button_confirm":       "#admin_page__static_modal__delete_confirm",
        "wrapper":              "#admin_page__static_modal__delete",
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
     * @return  {Object}                    Monitor.AdminPagesStatic.Delete objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                     Monitor.AdminPagesStatic.Delete objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminPagesStaticDelete", this.elements);
        return this;
    },









     /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deletePage();
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var page_id = parseInt(event.relatedTarget.dataset.pageId, 10);
        this.setPageID(page_id);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminPagesStaticDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminPagesStaticDelete", element, query_all, modifier);
    },










    /**
     * Dohvata ID trenutnog kategorije
     * @return  {Number}                    ID kategorije
     */
    getPageID: function() {
        return this.config.page_id;
    },

    /**
     * Zadaje ID trenutne kategorije
     * @return  {Object}                    Monitor.AdminPagesStatic.Delete objekat, za ulančavanje funkcija
     */
    setPageID: function(page_id) {
        this.config.page_id = page_id;
        return this;
    },










    /**
     * Brise trenutnog korisnika
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    deletePage: function() {
        Monitor.Main.Ajax(
            "AdminStaticPages",
            "deletePage",
            {
                "page_id": this.getPageID(),
            },
            function(data) {
                var event = new CustomEvent("Monitor.Admin.StaticPages");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
}
document.addEventListener('DOMContentLoaded', Monitor.AdminPagesStatic.Delete.init.bind(Monitor.AdminPagesStatic.Delete), false);
