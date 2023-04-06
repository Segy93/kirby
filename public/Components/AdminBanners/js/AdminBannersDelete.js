"use strict";

if (typeof Monitor               === "undefined") var Monitor           = {};
if (typeof Kirby.AdminBanners === "undefined") Kirby.AdminBanners = {};

/**
 *
 * Modal za potvrdu brisanja clanaka
 *
 */
Kirby.AdminBanners.Delete = {

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
     * @return  {Object}                    Kirby.AdminBanners.Delete
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminBannersDelete", this.elements);
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
        return Kirby.Main.Dom.getElement("AdminBannersDelete", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element     Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all   Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier    BEM modifier za selektor
    */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminBannersDelete", element, query_all, modifier);
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
     * @return  {Object}                    Kirby.Adminbanners.Delete
     */
    setBannerID: function(banner_id) {
        this.config.banner_id = banner_id;
        return this;
    },










    /**
     * Brisanje clanka
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
    deleteBanner: function() {
        Kirby.Main.Ajax(
            "AdminBanners",
            "deleteBanner",
            {
                banner_id: this.getBannerID(),
            },
            (data) => {
                var event  = new CustomEvent("Kirby.Admin.Banners");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminBanners.Delete.init.bind(Kirby.AdminBanners.Delete, false));
