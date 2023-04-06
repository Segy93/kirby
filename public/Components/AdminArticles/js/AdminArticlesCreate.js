"use strict"

if(typeof Monitor               === "undefined") var Monitor           = {};
if(typeof Kirby.AdminArticles === "undefined") Kirby.AdminArticles = {};

Kirby.AdminArticles.Create = {
    config: {               // Konfiguracija komponente
        editor_text: {},    // Editor teksta
        exitor_excerpt: {}, // Editor isecka
        old_name: "", //Poslednje ime foldera za clanak
        old_category: "", //Poslednja vrednost kategorije
        inital_date: "", //Pocetna vrednost datuma
    },

    elements :{ // Selektori elemenata koje komponenta koristi
        "form":         "#admin_articles__create_form",
        "heading":      "#admin_articles__create_heading",
        "image":        "#admin_articles__create_image",
        "categories":   "#admin_articles__create_categories",
        "date":         "#admin_articles__create_date",
        "text":         "#admin_articles__create_text",
        "tags_label":   ".admin_articles__create_tag__label",
        "tags":         ".admin_articles__create_tag",
        "excerpt":      "#admin_articles__create_excerpt",
        "submit":       "#admin_articles__create__submit",
        "reset":        "#admin_articles__create__reset",
        "author":       "#admin_aricles__create_author",
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JS event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
            .initEditor("text")
            .initEditor("excerpt")
            .initDatePicker()
            .resetDateValue()
        ;
    },

    /**
     * Inicijalizacija biblioteke za izbor datuma
     * @param   {string}    element         Na kom elementu je picker
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
    initDatePicker: function(element) {
        flatpickr(this.getElementSelector("date"), {
            defaultDate: new Date(),
            enableTime: true,
            dateFormat: "Y-m-d H:i:s",
        });
        return this;
    },

    /**
     * Inicijalizacija tinyMCE editora
     * @param   {string}    element         Na kom elementu (iz this.elements) ce biti editor
     * @return  {Object}                    Kirby.AdminArticles.Create
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
        this.config["editor_" + element] .getBody().setAttribute('contenteditable', false);
    },

    /**
     * Inicijalizacija osluskivaca komponente
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
    initListeners: function() {
        var form    = this.getElement("form");
        if (form !== null) form.onsubmit = this.submitForm.bind(this);

        var tags = this.getElement("tags", true);
        for (var i = 0, l = tags.length; i < l; i++) {
            tags[i].addEventListener("change", this.toggleTag.bind(this), false);
        }

        this.getElement("categories").addEventListener("change", this.changedPath.bind(this), false);
        this.getElement("heading").addEventListener("blur", this.changedPath.bind(this),     false);
        this.getElement("reset").addEventListener("click", this.clickReset.bind(this), false);
        document.addEventListener("Kirby.SEO.Form", this.changedSEOState.bind(this), false);

        window.addEventListener("unload", this.unloadPage.bind(this), false);
        window.addEventListener("load", this.loadPage.bind(this),false)

       return this;
    },

    /**
     * Registrovanje elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminArticlesCreate", this.elements);
        return this;
    },












    /**
     * Novi clanak je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom clanku
     */
    createdArticle: function(data) {
        var event = new CustomEvent("Kirby.Admin.Articles");
        event.info = "Create";
        event.data = data;
        document.dispatchEvent(event);

        this.resetForm();
        // Resetovanje izbora tagova
        var labels      = this.getElement("tags_label", true);
        for (var i = 0, l = labels.length; i < l; i++) {
            var label = labels[i];
            label.classList.remove("active");
            label.previousElementSibling.checked = false;
        }

        $(".alert").show().fadeTo(0,500);
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500);
        }, 5000);
    },

    /**
     * Poslata je forma za kreiranje clanaka
     * @param   {Object}    event           JS event objekat
     */
    submitForm: function(event) {
        var heading     = this.getElement("heading");
        var image       = this.getElement("image");
        var categories  = parseInt(this.getElement("categories").value, 10);
        var author      = parseInt(this.getElement("author").value, 10);
        var date_raw    = new Date(this.getElement("date").value);
        var date        = date_raw.toISOString().slice(0, 19).replace("T", " ");
        var text        = this.config.editor_text.getContent();
        var tags        = this.getElement("tags", true);
        var tag_ids     = [];

        for (var i = 0, l = tags.length; i < l; i++) {
            if (tags[i].checked) tag_ids.push(parseInt(tags[i].value, 10));
        }
        var excerpt    = this.config.editor_excerpt.getContent();

        if (heading.checkValidity()) {
            this.createArticle(
                heading.value,
                image.files[0],
                categories,
                date,
                text,
                tag_ids,
                excerpt,
                author
            );
        }


        return false;
    },

    /**
     * Provera da li clanak sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    changedPath: function(event) {
        var heading = this.getElement("heading").value;
        var categories  = parseInt(this.getElement("categories").value, 10);
        if (heading.length > 0) this.isHeadingTaken(heading);
        if(heading.length > 0 && !(isNaN(categories))) {
            this.checkFolder(heading, categories);
            this.config.editor_text.getBody().setAttribute('contenteditable', true);
            this.config.editor_excerpt.getBody().setAttribute('contenteditable', true);
        } else {
            this.config.editor_text.getBody().setAttribute('contenteditable', false);
            this.config.editor_excerpt.getBody().setAttribute('contenteditable', false);
        }
    },

    clickReset : function (event) {
        var item = localStorage.getItem('article_form__data');
        if(item)localStorage.removeItem('article_form__data');
    },

    unloadPage : function(event) {
        var heading     = this.getElement("heading").value;
        var image       = this.getElement("image");
        var categories  = parseInt(this.getElement("categories").value, 10);
        var date_raw    = this.getElement("date").value;
        var text        = this.config.editor_text.getContent();
        var tags        = this.getElement("tags", true);
        var tag_ids     = [];
        for(var i = 0, l = tags.length; i < l; i++) {
            if (tags[i].checked) tag_ids.push(parseInt(tags[i].value, 10));
        }
        var excerpt    = this.config.editor_excerpt.getContent();

        var article_form__data = {};

        if(heading.length > 0)      article_form__data.heading       = heading;
        if(categories > 0)          article_form__data.categories    = categories;
        if(date_raw !== this.config.inital_date)     article_form__data.date_raw      = date_raw;
        if(text.length > 0)         article_form__data.text          = text;
        if(excerpt.length > 0)      article_form__data.excerpt       = excerpt;
        if(tag_ids.length > 0)          article_form__data.tag_ids       = tag_ids;

        localStorage.setItem('article_form__data',JSON.stringify(article_form__data));
    },

    loadPage : function(event) {
        var retrievedObject = localStorage.getItem('article_form__data');
        var formValues      = JSON.parse(retrievedObject);
        var heading         = this.getElement("heading");
        var categories      = this.getElement("categories");
        var date_raw        = this.getElement("date");
        var text            = this.config.editor_text;
        var tags            = this.getElement("tags", true);
        var excerpt         = this.config.editor_excerpt;
        var tag_ids         = [];

        this.config.inital_date = date_raw.value;

        if(formValues){
            if(formValues.heading)      heading.value       = formValues.heading;
            if(formValues.categories)   categories.value    = formValues.categories;
            if(formValues.date_raw)     date_raw.value      = formValues.date_raw;
            if(formValues.text)         text.setContent      (formValues.text);
            if(formValues.excerpt)      excerpt.setContent   (formValues.excerpt);
            //if(formValues.tag_ids)  {
            //    for (var i = 0, l = tags.length; i < l; i++) {
            //        var tag = formValues.tag_ids[i];
            //        var checkbox = this.getElement("tags", false, tag);
            //        checkbox.checked = true;
            //        checkbox.nextElementSibling.classList.add("active");
            //    }
            //}
            if(formValues.heading && formValues.categories) {
                this.config.editor_text.getBody().setAttribute('contenteditable', true);
                this.config.editor_excerpt.getBody().setAttribute('contenteditable', true);
            }
            if (heading.value.length > 0) this.isHeadingTaken(heading.value);
        }
    },

    /**
     * Promenjeno je stanje SEO forme
     * @param   {Object}    event           JS event objekat
     */
    changedSEOState: function(event) {
        this.getElement("submit").setCustomValidity(event.valid ? "" : event.message);
    },

    /**
     * Promenjen je tag za clanak
     * @param   {Object}    event           JS event objekat
     */
    toggleTag: function(event) {
        event.target.nextSibling.classList.toggle("active");
    },


    /**
    * Proverava da naslov nije prazan ako nije salje ajax poziv za kreiranje foldera
    * Ukoliko je old_name prazno kreira novi folder a ukoliko se ili ime ili kategorija promenila poziva se rename
    */
    checkFolder: function(new_name, category) {
        var old_name     = this.config.old_name;
        var old_category = this.config.old_category;
        if(old_name === '') this.createFolder(new_name, category);
        else if(old_name !== new_name || old_category !== category) this.renameFolder(old_name, new_name, category);

        this.config.old_name        = new_name;
        this.config.old_category    = category;
    },








    /**
     * Resetovanje unetog datuma
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
    resetDateValue: function() {
        var picker = this.getElement("date")._flatpickr;
        picker.setDate(new Date());
        return this;
    },

    /**
     * Nakom slanja forme old_name vise se ne prati staro ime foldera jer je clanak vec napravljen.
     * Potrebno je da se napravi novi folder za novi clanak.
     */
    resetOldNameValue: function () {
        this.config.old_name = "";

    },

    /**
     * Dohvata se element da osnovu lokalnog imena
     * @param  {String} element    Lokalno ime elementa definisano u vrhu fajla
     * @param  {Boolean} querry_all da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param  {[type]} modifier   BEM Modifier za selektor
     * @return {Node/NodeList}      Vraca Node objekat ukoliko je querry_all false u suprotnom vraca niz objekata
     */
    getElement: function(element, querry_all, modifier){
        return Kirby.Main.Dom.getElement("AdminArticlesCreate", element , querry_all , modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminArticlesCreate", element, query_all, modifier);
    },

    /**
     * Resetovanje forme za unos
     * @return {[type]} [description]
     */
    resetForm: function() {
        this.getElement("form").reset();
        this.resetOldNameValue();
        return this.resetDateValue();
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
     * Kreiranje clanka
     * @param   {string}    heading         Naslov
     * @param   {File}      image           Slika
     * @param   {Number}    category        ID kategorije
     * @param   {string}    date            Datum
     * @param   {string}    text            Tekst
     * @param   {Array}     tags            Niz ID-jeva tagova
     * @param   {string}    excerpt         Isecak
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
    createArticle: function(heading, image, category, date, text, tags, excerpt, author) {
        Kirby.Main.Ajax(
            "AdminArticles",
            "createArticle",
            {
                "heading"   : heading,
                "image"     : image,
                "category"  : category,
                "date"      : date,
                "text"      : text,
                "tags"      : tags,
                "excerpt"   : excerpt,
                "author"    : author,

            },
            this.createdArticle.bind(this),
            {},
            true
        );

        return this;
    },

    /**
     * Provera da li vec postoji clanak s ovim naslovom
     * @param   {String}    heading         Korisnicko ime koje proveravamo
     * @return  {Object}                    Kirby.AdminUsers.Create objekat, za ulančavanje funkcija
     */
    isHeadingTaken: function(heading) {
        Kirby.Main.Ajax(
            "AdminArticles",
            "isHeadingTaken",
            {
                "heading": heading,
            },
            this.setHeadingValidity.bind(this)
        );
        return this;
    },

    //Mislim da ova metoda neće više biti potrebna jer folder za članke kreira u metodi za kreiranje članaka
    createFolder: function (name, category) {
        // Kirby.Main.Ajax(
        //     "AdminArticles",
        //     "createFolder",
        //     {
        //         "name": name,
        //         "category": category,
        //     }
        // );
    },

    renameFolder: function (old_name, new_name, category) {
        Kirby.Main.Ajax(
            "AdminArticles",
            "updateFolder",
            {
                "old_name": old_name,
                "new_name": new_name,
                "category": category,
            }
        );
    },

    deleteFolder: function (name, category) {
        Kirby.Main.Ajax(
            "AdminArticles",
            "deleteFolder",
            {
                "name": name,
                "name": category,
            }
        );
    },

};

document.addEventListener("DOMContentLoaded", Kirby.AdminArticles.Create.init.bind(Kirby.AdminArticles.Create),false);
