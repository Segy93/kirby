"use strict";

if (typeof Monitor  === "undefined") var Monitor                        = {};
if (typeof Monitor.AdminComments === "undefined") Monitor.AdminComments = {};

/**
 *
 * Modal za potvrdu brisanja clanaka
 *
 */
Monitor.AdminComments.Delete = {

    config: { // Konfiguracija komponente
        comment_id: 0, // ID clanka koji ce biti obrisan
    },

    elements: { // Selektori elemenata koje komponenta koristi
        button_confirm: "#admin_comments__modal_delete__confirm",
        wrapper:        "#admin_comments__modal_delete",
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
     * @return  {Object}                    Monitor.AdminArticles.Delete
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminComments.Delete
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminCommentsDelete", this.elements);
        return this;
    },








    /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
    clickDelete: function(event) {
        this.deleteComment();
    },

    /**
     * Modal za potvrdu je zahtevan; Pamtimo ID clanka za koji je vezan
     * @param   {Object}    event           jQuery event objekat
     */
    requestedComponent: function(event) {
        var comment_id = parseInt(event.relatedTarget.dataset.commentId, 10);
        this.setCommentID(comment_id);
    },








    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko
    *                                je query_all false, niz Node objekata inace
    */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminCommentsDelete", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false,
    *                                niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminCommentsDelete", element, query_all, modifier);
    },








    /**
     * Dohvata ID trenutno aktivnog clanka
     * @return  {Number}                    ID aktivnog clanka
     */
    getCommentID: function() {
        return this.config.comment_id;
    },

    /**
     * Cuva ID trenutno aktivnog clanka
     * @param   {Number}    comment_id      ID clanka
     * @return  {Object}                    Monitor.AdminComments.Delete
     */
    setCommentID: function(comment_id) {
        this.config.comment_id = comment_id;
        return this;
    },










    /**
     * Brisanje clanka
     * @return  {Object}                    Monitor.AdminComments.Delete
     */
    deleteComment: function() {
        Monitor.Main.Ajax(
            "AdminComments",
            "deleteComment",
            {
                comment_id: this.getCommentID(),
            },
            (data) => {
                var event  = new CustomEvent("Monitor.Admin.Comments");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminComments.Delete.init.bind(Monitor.AdminComments.Delete, false));
