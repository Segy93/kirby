"use strict";

if (typeof Monitor               === "undefined") var Monitor           = {};
if (typeof Monitor.AdminBanners === "undefined") Monitor.AdminBanners = {};

/**
 *
 * Modal za potvrdu brisanja clanaka
 *
 */
Monitor.AdminBanners.Delete = {

    config: { // Konfiguracija komponente
        banner_id: 0, // ID bannera koji ce biti obrisan
    },

    elements: { // Selektori elemenata koje komponenta koristi
        button_confirm:             "#admin_banners__modal_delete__confirm",
        wrapper:                    "#admin_banners__modal_delete",
    },

    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JS event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
        ;
    },

    /**
     * Inicijalizacija osluskivaca komponente
     * @return  {Object}                    Monitor.AdminBanners.Delete
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminArticles.Delete
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminBannersDelete", this.elements);
        return this;
    },








    /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteBanner();
    },

    /**
     * Modal za potvrdu je zahtevan; Pamtimo ID clanka za koji je vezan
     * @param   {Object}    event           jQuery event objekat
     */
    requestedComponent: function(event) {
        var banner_id = parseInt(event.relatedTarget.dataset.bannerId, 10);
        this.setBannerID(banner_id);
    },








    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminBannersDelete", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element     Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all   Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier    BEM modifier za selektor
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminBannersDelete", element, query_all, modifier);
    },








    /**
     * Dohvata ID trenutno aktivnog clanka
     * @return  {Number}                    ID aktivnog clanka
     */
    getBannerID: function() {
        return this.config.banner_id;
    },

    /**
     * Cuva ID trenutno aktivnog clanka
     * @param   {Number}    banner_id      ID clanka
     * @return  {Object}                    Monitor.Adminbanners.Delete
     */
    setBannerID: function(banner_id) {
        this.config.banner_id = banner_id;
        return this;
    },










    /**
     * Brisanje clanka
     * @return  {Object}                    Monitor.AdminArticles.Delete
     */
    deleteBanner: function() {
        Monitor.Main.Ajax(
            "AdminBanners",
            "deleteBanner",
            {
                banner_id: this.getBannerID(),
            },
            (data) => {
                var event  = new CustomEvent("Monitor.Admin.Banners");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminBanners.Delete.init.bind(Monitor.AdminBanners.Delete, false));
