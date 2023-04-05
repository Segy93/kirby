"use strict";

if(typeof Monitor === "undefined") var Monitor                 = {};
if(typeof Monitor.AdminTags === "undefined") Monitor.AdminTags = {};

Monitor.AdminTags.Delete = {











    config: {
        "tag_id": null,
    },

    elements: {
        "button_confirm":       "#admin_tags__modal_delete__confirm",
        "wrapper":              "#admin_tags__modal_delete",
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
     * @return  {Object}                    Monitor.AdminTags.Delete objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                     Monitor.AdminTags.Delete objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminTagsDelete", this.elements);
        return this;
    },









     /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteTag();
    },

    /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var tag_id = parseInt(event.relatedTarget.dataset.tagId, 10);
        this.setTagID(tag_id);
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminTagsDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminTagsDelete", element, query_all, modifier);
    },










    /**
     * Dohvata ID trenutnog taga
     * @return  {Number}                    ID taga
     */
    getTagID: function() {
        return this.config.tag_id;
    },

    /**
     * Zadaje ID trenutnog taga
     * @return  {Object}                    Monitor.AdminTag.Delete objekat, za ulančavanje funkcija
     */
    setTagID: function(tag_id) {
        this.config.tag_id = tag_id;
        return this;
    },










    /**
     * Brise trenutnog korisnika
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Delete objekat, za ulančavanje funkcija
     */
    deleteTag: function() {
        Monitor.Main.Ajax(
            "AdminTags",
            "deleteTag",
            {
                "tag_id": this.getTagID(),
            },
            function(data) {
                var event = new CustomEvent("Monitor.Admin.Tags");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
}
document.addEventListener('DOMContentLoaded', Monitor.AdminTags.Delete.init.bind(Monitor.AdminTags.Delete), false);
