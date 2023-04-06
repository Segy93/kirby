"use strict";

if (typeof Monitor  === "undefined") var Monitor                        = {};
if (typeof Kirby.AdminComments === "undefined") Kirby.AdminComments = {};

/**
 *
 * Modal za potvrdu brisanja clanaka
 *
 */
Kirby.AdminComments.Delete = {

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
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
    initListeners: function() {
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
        return this;
    },

    /**
     * Registracija elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminComments.Delete
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminCommentsDelete", this.elements);
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
        return Kirby.Main.Dom.getElement("AdminCommentsDelete", element, query_all, modifier);
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
        return Kirby.Main.Dom.getElementSelector("AdminCommentsDelete", element, query_all, modifier);
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
     * @return  {Object}                    Kirby.AdminComments.Delete
     */
    setCommentID: function(comment_id) {
        this.config.comment_id = comment_id;
        return this;
    },










    /**
     * Brisanje clanka
     * @return  {Object}                    Kirby.AdminComments.Delete
     */
    deleteComment: function() {
        Kirby.Main.Ajax(
            "AdminComments",
            "deleteComment",
            {
                comment_id: this.getCommentID(),
            },
            (data) => {
                var event  = new CustomEvent("Kirby.Admin.Comments");
                event.info = "Delete";
                event.data = data;
                document.dispatchEvent(event);
            }
        );
        return this;
    },
};

document.addEventListener("DOMContentLoaded", Kirby.AdminComments.Delete.init.bind(Kirby.AdminComments.Delete, false));
