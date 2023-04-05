"use strict";

if(typeof Monitor                 === "undefined") var Monitor             = {};
if(typeof Monitor.AdminCategories === "undefined") Monitor.AdminCategories = {};

Monitor.AdminCategories.Delete = {











    config:{
        user_id: null,
    },

    elements:{
        "button_confirm":       "#admin_categories__modal_delete__confirm",
        "wrapper":              "#admin_categories__modal_delete",
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
     * @return  {Object}                    Monitor.AdminCategories.Delete objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                     Monitor.AdminCategories.Delete objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminCategoriesDelete", this.elements);
        return this;
    },









     /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteCategory();
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var category_id = parseInt(event.relatedTarget.dataset.categoryId, 10);
        this.setCategoryID(category_id);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminCategoriesDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminCategoriesDelete", element, query_all, modifier);
    },










    /**
     * Dohvata ID trenutnog kategorije
     * @return  {Number}                    ID kategorije
     */
    getCategoryID: function() {
        return this.config.category_id;
    },

    /**
     * Zadaje ID trenutne kategorije
     * @return  {Object}                    Monitor.AdminCategories.Delete objekat, za ulančavanje funkcija
     */
    setCategoryID: function(category_id) {
        this.config.category_id = category_id;
        return this;
    },










    /**
     * Brise trenutnog korisnika
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    deleteCategory: function() {
        Monitor.Main.Ajax(
            "AdminCategories",
            "deleteCategory",
            {
                "category_id": this.getCategoryID(),
            },
            function(data) {
                var event = new CustomEvent("Monitor.Admin.Categories");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
}
document.addEventListener('DOMContentLoaded', Monitor.AdminCategories.Delete.init.bind(Monitor.AdminCategories.Delete), false);
