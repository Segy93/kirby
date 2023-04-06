"use strict"

if(typeof Monitor               === "undefined") var Monitor           = {};
if(typeof Kirby.AdminArticles === "undefined") Kirby.AdminArticles = {};

/**
 *
 * Modal za izmenu clanka
 *
 */
Kirby.AdminArticlesEdit = {

    config:{ // Konfiguracija komponente
        "editor_excerpt": {},
        "editor_text": {},
    },
    
    elements :{ // Selektori elemenata koje komponenta koristi
        "article_id":   "#admin_articles__edit_id",
        "form":         "#admin_article__edit_form",
        "text":         "#admin_articles__edit_text",
        "image":        ".admin_articles__list_picture",
        "image_input":  "#admin_article__image_edit",
        "excerpt":      "#admin_articles__edit_excerpt",
        "heading":      "#admin_articles__edit_heading",
        "author":       "#admin_articles__edit_author",
        "wrapper":      "#admin_articles__modal_edit",
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
            .initData()
        ;
    },

    /**
     * Inicijalizacija tinyMCE editora
     * @param   {string}    element         Na kom elementu (iz this.elements) ce biti editor
     * @return  {Object}                    Kirby.AdminArticles.Change
     */
    initEditor: function(element) {
        var selector = this.getElementSelector(element);
        var callback = this.setConfig.bind(this);
        Kirby.Main.Editor.initEditor(element, selector, callback);
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
     * @return  {Object}                    Kirby.AdminArticles.Change
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper_list"));


        this.getElement("heading").addEventListener("input", this.blurHeading.bind(this), false);
        this.getElement("form").onsubmit =  this.submitChanges.bind(this);
        document.addEventListener("Kirby.Admin.Articles", this.changeOccurred.bind(this), false);
        return this;
    },

    /**
     * Registrovanje elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminArticles.Change
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminArticlesEdit", this.elements);
        return this;
    },










    /**
     * Zahtevana je komponenta, pa dohvatamo neophodne podatke
     * @param   {Object}    event           jQuery event objekat
     */
    initData: function(event) {
        var form       = this.getElement("form");
        var elements   = form.elements;
        var article_id = parseInt(elements.article_id.value);
        this.fetchArticle(article_id);
        return this;
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

    changeOccurred: function(event) {
        var form       = this.getElement("form");
        var elements   = form.elements;
        var article_id = parseInt(elements.article_id.value);
        this.fetchArticle(article_id);
    },









    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminArticlesEdit", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminArticlesEdit", element, query_all, modifier);
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
     * @return  {Object}                    Kirby.AdminArticles.Create objekat, za ulančavanje funkcija
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
     * @return  {Object}                    Kirby.AdminArticles.Change
     */
    fetchArticle: function(article_id) {
        Kirby.Main.Ajax(
            "AdminArticlesEdit",
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
     * @return  {Object}                    Kirby.AdminTags.Change objekat, za ulančavanje funkcija
     */
    isHeadingTaken: function(heading) {
        Kirby.Main.Ajax(
            "AdminArticlesEdit",
            "isHeadingTaken",
            {
                "heading": heading,
            },
            this.setHeadingValidity.bind(this)
        );

        return this;
    },

    /**
     * Azuriranje clanka
     * @param   {Number}    article_id      ID clanka koji menjamo
     * @param   {string}    heading         Naslov clanka
     * @param   {string}    text            Tekst clanka
     * @param   {string}    excerpt         Isecak
     * @return  {Object}                    Kirby.AdminArticles.Change
     */
    updateArticle: function(article_id, heading, text, excerpt, author_id) {
        var params = {
            "article_id": article_id,
            "heading"   : heading,
            "text"      : text,
            "excerpt"   : excerpt,
        };
        if (author_id !== undefined) params.author_id = author_id;

        Kirby.Main.Ajax(
            "AdminArticlesEdit",
            "updateArticle",
             params,

            function (data) {
                var event   = new CustomEvent("Kirby.Admin.Articles");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
                window.location.href = "/admin/clanci";
            }
        );

        return this;
    },

};

document.addEventListener("DOMContentLoaded", Kirby.AdminArticlesEdit.init.bind(Kirby.AdminArticlesEdit), false);
