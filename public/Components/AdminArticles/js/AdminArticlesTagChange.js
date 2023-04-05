"use strict"

if (typeof Monitor === "undefined") var Monitor = {};
if (typeof Monitor.AdminArticles === "undefined") Monitor.AdminArticles = {};

Monitor.AdminArticles.TagChange = {
    config: { // Konfiguracija komponente
        "article_id": 0, // ID clanka kome menjamo tagove
    },

    elements: { // Selektori elemenata koje komponenta koristi
        "label":    ".admin_articles__change_tag__label",
        "checkbox": ".admin_articles__change_tag",
        "wrapper":  "#admin_articles__modal_tag",
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
     * @return  {Object}                    Monitor.AdminArticles.TagChange
     */
    initListeners: function() {
        // Pojedinacni checkbox za svaki tag
        var checkboxes = this.getElement("checkbox", true);
        for (var i = 0, l = checkboxes.length; i < l; i++) {
            checkboxes[i].addEventListener("change", this.toggleTag.bind(this), false);
        }

        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        return this;
    },

    /**
     * Registrovanje elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminArticles.TagChange
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminArticlesChangeTag", this.elements);
        return this;
    },








    /**
     * Komponenta je zahtevana da se prikaze, dohvatamo potrebne podatke
     * @param   {object}    event           jQuery event objekat
     */
    requestedComponent: function (event) {
        var article_id = parseInt(event.relatedTarget.dataset.articleId, 10);
        this.config.article_id = article_id;

        this.fetchUsedTags(article_id);
    },

    /**
     * Promenjen je tag clanka
     * @param   {Object}    event           JS event objekat
     */
    toggleTag: function (event) {
        var checkbox    = event.target;
        var label       = checkbox.nextElementSibling;
        var article_id  = this.config.article_id;

        label.classList[checkbox.checked ? "add" : "remove"]("active");

        this.changeArticleTag(
            article_id,
            parseInt(checkbox.value, 10),
            checkbox.checked
        );
    },








    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminArticlesChangeTag", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminArticlesChangeTag", element, query_all, modifier);
    },

    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminArticles.TagChange
     */
    hideDialog: function() {
        var form    = this.getElement('form');
        var labels  = this.getElement("label", true);

        for (var i = 0; i < labels.length; i++) {
            labels[i].classList.remove("active");
        }

        form.reset();

        $(this.getElement("wrapper")).modal("hide");

        return this;
    },

    /**
     * Prikazivanje komponente
     * @param   {Object}    data            Podaci, sa kljucevima:
     *                                      tags: Niz tagova clanka
     */
    render: function(data) {
        var tags        = data.tags;
        var checkboxes  = this.getElement("checkbox", true);

        // Resetovanje forme
        for (var i = 0, l = checkboxes.length; i < l; i++) {
            var checkbox = checkboxes[i];
            checkbox.checked = false;
            checkbox.nextElementSibling.classList.remove("active");
        }

        // Cekiranje aktivnih tagova
        for (var i = 0, l = tags.length; i < l; i++) {
            var tag = tags[i];
            var checkbox = this.getElement("checkbox", false, tag.id);

            checkbox.checked = true;
            checkbox.nextElementSibling.classList.add("active");
        }
    },









    /**
     * Dohvata tagove koje clanak ima
     * @param   {Number}    article_id      ID clanka
     * @return  {Object}                    Monitor.AdminArticles.TagChange
     */
    fetchUsedTags: function(article_id) {
        Monitor.Main.Ajax (
            "AdminArticles",
            "fetchUsedTags",
            {
                "article_id": article_id,
            },
            this.render.bind(this)
        );

        return this;
    },

    /**
     * Kacenje ili sklanjanje taga sa clanka
     * @param   {Number}    article_id      ID clanka
     * @param   {Number}    tag_id          ID taga
     * @param   {Boolean}   state           Stanje (true za kacenje i false za sklanjanje)
     * @return  {Object}                    Monitor.AdminArticles.TagChange
     */
    changeArticleTag: function(article_id, tag_id, state) {
        Monitor.Main.Ajax (
            "AdminArticles",
            "changeTag",
            {
                "article_id": article_id,
                "tag_id": tag_id,
                "state": state,
            },
            function (data) {
                var event   = new CustomEvent("Monitor.Admin.Articles");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },

};

document.addEventListener("DOMContentLoaded", Monitor.AdminArticles.TagChange.init.bind(Monitor.AdminArticles.TagChange), false);
