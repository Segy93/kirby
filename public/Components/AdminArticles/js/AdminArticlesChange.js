"use strict"

if(typeof Monitor               === "undefined") var Monitor           = {};
if(typeof Monitor.AdminArticles === "undefined") Monitor.AdminArticles = {};

/**
 *
 * Modal za izmenu clanka
 *
 */
Monitor.AdminArticles.Change = {

    config:{ // Konfiguracija komponente
        "editor_excerpt": {},
        "editor_text": {},
    },

    elements :{ // Selektori elemenata koje komponenta koristi
        "form":         "#admin_article__change_form",
        "text":         "#admin_articles__change_text",
        "image":        ".admin_articles__list_picture",
        "image_input":  "#admin_article__image_change",
        "excerpt":      "#admin_articles__change_excerpt",
        "heading":      "#admin_articles__change_heading",
        "author":       "#admin_articles__change_author",
        "wrapper":      "#admin_articles__modal_change",
        "wrapper_list": "#admin_articles__list_content",
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JS event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
            .initEditor("excerpt")
            .initEditor("text")
        ;
    },

    /**
     * Inicijalizacija tinyMCE editora
     * @param   {string}    element         Na kom elementu (iz this.elements) ce biti editor
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    initEditor: function(element) {
        var selector = this.getElementSelector(element);
        var callback = this.setConfig.bind(this);
        Monitor.Main.Editor.initEditor(element, selector, callback);
        return this;
    },

    /**
     * Callback funkcija za setovanje editora u config promenljive
     * @param {Object} editor  Objekat inicializovanog editora
     * @param {string} element Naziv elementa
     */
    setConfig: function (editor, element) {
        this.config["editor_" + element] = editor;
    },

    /**
     * Inicijalizacija osluskivaca komponente
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper_list"));

        $wrapper.on("click", this.getElementSelector("image"), this.clickImage.bind(this));
        $wrapper.on("change", this.getElementSelector("image_input"), this.changedImage.bind(this));

        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
        this.getElement("heading").addEventListener("input", this.blurHeading.bind(this), false);
        this.getElement("form").onsubmit =  this.submitChanges.bind(this);

        return this;
    },

    /**
     * Registrovanje elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminArticlesChange", this.elements);
        return this;
    },










    /**
     * Zahtevana je komponenta, pa dohvatamo neophodne podatke
     * @param   {Object}    event           jQuery event objekat
     */
    requestedComponent: function(event) {
        var article_id = parseInt(event.relatedTarget.dataset.articleId, 10);
        this.fetchArticle(article_id);
    },

    /**
     * Sacuvana je forma sa novim podacima
     * @param   {Object}    event           JS event objekat
     * @return  {boolean}                   false, kako bi se AJAX-om slali podaci
     */
    submitChanges: function(event) {
        var form       = event.target;
        var elements   = form.elements;

        var article_id = parseInt(elements.article_id.value);
        var heading    = elements.heading.value;
        var text       = this.config.editor_text.getContent();
        var excerpt    = this.config.editor_excerpt.getContent();

        if (this.getElement("author") !== null) {
            var author_id  = parseInt(elements.author.value, 10);
        }

        this.updateArticle(article_id, heading, text, excerpt, author_id);
        this.hideDialog();
        form.reset();
        return false;
    },

    /**
     * Provera da li tag sa datim imenom vec postoji
     * @param  {Object}     event           JS event objekat
     */
    blurHeading: function(event) {
        var heading = event.target.value;
        if (heading !== event.target.dataset.original && heading.length > 0) {
            this.isHeadingTaken(heading);
        }
    },

    /**
     * Promenjena je slika, cuvamo izmene
     * @param   {Object}    event           JS event objekat
     */
    changedImage: function(event) {
        var elem = event.target;
        var article_id = parseInt(elem.dataset.articleId , 10);
        this.changeImage(article_id, elem.files[0]);
    },

    /**
     * Korisnik je kliknuo na sliku; prosledjujemo dogadjaj na input type=file
     * @param   {Object}    event           JS event objekat
     */
    clickImage: function(event) {
        var input = event.currentTarget.previousSibling;
        if (input) input.click(); // Ako nema dozvolu za izmenu, nece biti input-a
    },










    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminArticlesChange", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminArticlesChange", element, query_all, modifier);
    },

    /**
    * Zatvara modal
    * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
    */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");
        return this;
    },

    /**
     * Prikazivanje komponente
     * @param   {Object}    data            Podaci za render; Sadrzi article kljuc
     */
    render: function(data) {
        var elements    = this.getElement("form").elements;
        var text        = data.article.text;


        elements.article_id.value         = data.article.id;
        elements.heading.value            = data.article.title;
        elements.heading.dataset.original = data.article.title;
        elements.excerpt.value            = data.article.excerpt;

        if (elements.author !== undefined) {
            elements.author.value = data.article.author_id === null ? 0 : data.article.author_id;
        }

        this.config.editor_text.setContent(text);
        this.config.editor_excerpt.setContent(data.article.excerpt);
    },

    /**
     * Zadaje validity za heading polje, u zavisnosti da li postoji clanak s ovim naslovom
     * @param   {Boolean}   exists          Da li je heading vec zauzet
     * @return  {Object}                    Monitor.AdminArticles.Create objekat, za ulančavanje funkcija
     */
    setHeadingValidity: function(exists) {
        this.getElement("heading").setCustomValidity(
            exists ? "Article with this heading already exists" : ""
        );
        return this;
    },

















    /**
     * Dohvatanje informacija o pojedinacnom clanku
     * @param   {Number}    article_id      ID clanka koji dohvatamo
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    fetchArticle: function(article_id) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "fetchArticle",
            {
                "article_id": article_id,
            },
            this.render.bind(this)
        );

        return this;
    },

    /**
     * Provera da li vec postoji tag s ovim korisnickim imenom
     * @param   {String}    username        Ime koje proveravamo
     * @return  {Object}                    Monitor.AdminTags.Change objekat, za ulančavanje funkcija
     */
    isHeadingTaken: function(heading) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "isHeadingTaken",
            {
                "heading": heading,
            },
            this.setHeadingValidity.bind(this)
        );

        return this;
    },

    /**
     * Menjamo sliku clanka
     * @param   {Number}    article_id      ID clanka kome menjamo sliku
     * @param   {File}      image           Nova slika
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    changeImage: function(article_id, image) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "changeImage",
            {
                "article_id": article_id,
                "image"     : image,
            },
            function (data) {
                var event   = new CustomEvent("Monitor.Admin.Articles");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            },
            undefined,
            true
        );

        return this;
    },

    /**
     * Azuriranje clanka
     * @param   {Number}    article_id      ID clanka koji menjamo
     * @param   {string}    heading         Naslov clanka
     * @param   {string}    text            Tekst clanka
     * @param   {string}    excerpt         Isecak
     * @return  {Object}                    Monitor.AdminArticles.Change
     */
    updateArticle: function(article_id, heading, text, excerpt, author_id) {
        var params = {
            "article_id": article_id,
            "heading"   : heading,
            "text"      : text,
            "excerpt"   : excerpt,
        };
        if (author_id !== undefined) params.author_id = author_id;

        Monitor.Main.Ajax(
            "AdminArticles",
            "updateArticle",
             params,

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

document.addEventListener("DOMContentLoaded", Monitor.AdminArticles.Change.init.bind(Monitor.AdminArticles.Change), false);
