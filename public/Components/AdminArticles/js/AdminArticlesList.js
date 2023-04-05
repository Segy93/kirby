"use strict"

if(typeof Monitor               === "undefined") var Monitor               = {};
if(typeof Monitor.AdminArticles === "undefined") Monitor.AdminArticles     = {};

/**
 *
 * Tabela sa listom clanaka i akcijama nad njima
 *
 */
Monitor.AdminArticles.List = {
    config: {                   // Konfiguracija komponente
        direction: true,        // Smer dohvatanja podataka (false za unazad, true za unapred)
        first_date: null,       // Prvi dohvaceni DATE (koristi se za navigaciju po stranama)
        last_date: null,        // Poslednji dohvaceni DATE (koristi se za navigaciju po stranama)
        per_page: 20,           // Koliko clanaka dohvatamo po strani
        filter_tag: 0,          // ID taga po kome filtriramo (0 ako je iskljucen)
        filter_category: 0,     // ID kategorije po kojoj filtriramo (0 ako je iskljucen)
        awaitSeo: true,
        awaitArticle: true,
    },

    elements :{                 // Selektori elemenata koje komponenta koristi
        "button_publish":       ".admin_article__button_publish",       // Taster za objavu clanka
        "button_return":        ".admin_article__button_return",        // Taster za povlacenje clanka
        "dropdown_category":    ".admin_articles__list_categories",     // Padajuci meni za promenu kategorije
        "change_date":          ".admin_articles__list_date",           // Polje za promenu datuma

        "button_prev":          "#admin_articles__list__prev",          // Prethodna strana
        "button_next":          "#admin_articles__list__next",          // Sledeca strana
        "filter_tag":           ".admin_articles__filter_tag",          // Tasteri za filtriranje po tagovima
        "filter_category":      ".admin_articles__filter_category",     // Tasteri za filtriranje po kategorijama

        "wrapper":              "#admin_articles__list_content",        // Element koji sadrzi listu clanaka
    },

    templates: {                // Sabloni koje komponenta koristi
        main: function(){},   // Glavni sablon, za formiranje tabele od podataka
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JS event objekat
     */
    init: function(event) {
        this
            .registerElements()
            .initTemplates()
            .initListeners()
            .fetchData()
        ;
    },

    /**
     * Inicijalizacija osluskivaca za komponentu
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));

        $wrapper.on("click",    this.getElementSelector("button_publish"),      this.clickPublish.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_return"),       this.clickUnpublish.bind(this));
        $wrapper.on("change",   this.getElementSelector("dropdown_category"),   this.changedCategory.bind(this));
        $wrapper.on("change",   this.getElementSelector("change_date"),         this.changedDate.bind(this));

        $(document).on("change", this.getElementSelector("filter_tag"), this.changeFilterTag.bind(this));
        $(document).on("change", this.getElementSelector("filter_category"), this.changeFilterCategory.bind(this));

        this.getElement("button_prev")  .addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        this.getElement("button_next")  .addEventListener("click",  this.clickPaginationNext.bind(this),        false);

        document.addEventListener("Monitor.Admin.Articles", this.changeOccurred.bind(this), false);
        document.addEventListener("Monitor.Admin.SEO.Create", this.refreshData.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona koje komponenta koristi
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_articles__list_temp").innerHTML);
        return this;
    },

    /**
     * Registrovanje elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    registerElements()  {
        Monitor.Main.DOM.register("AdminArticlesList", this.elements);
        return this;
    },










    /**
     * Promenjena je kategorija clanka, cuvamo izmene
     * @param   {Object}    event           jQuery event objekat
     */
    changedCategory: function(event) {
        var element = event.currentTarget;
        var article_id      = parseInt(element.dataset.articleId, 10);
        var category_id     = parseInt(element.dataset.categoryId, 10);
        var new_category_id = parseInt(element.value, 10);


        this.changeCategory(article_id, new_category_id);
    },

    /**
     * Promenjen je datum clanka, cuvamo izmene
     * @param   {Object}    event           jQuery event objekat
     */
    changedDate: function(event) {
        var element = event.currentTarget;
        var article_id      = parseInt(element.dataset.articleId, 10);
        var date_raw        = new Date(element.value);
        var date            = date_raw.toISOString().slice(0, 19).replace('T', ' ');

        this.changeDate(article_id, date);
    },

    /**
     * Promenjen je filter za kategorije, dohvatamo odgovarajuce podatke
     * @param   {Object}    event           jQuery event objekat
     */
    changeFilterCategory: function(event) {
        var category_id = parseInt(event.currentTarget.value, 10);

        this
            .resetDates()
            .setFilterCategory(category_id)
            .fetchData()
        ;
    },

    /**
     * Promenjen je filter za tagove, dohvatamo odgovarajuce podatke
     * @param   {Object}    event           jQuery event objekat
     */
    changeFilterTag: function(event) {
        var tag_id = parseInt(event.currentTarget.value, 10);

        this
            .resetDates()
            .setFilterTag(tag_id)
            .fetchData()
        ;
    },

    /**
     * Prilikom globalnog eventa da se desila promena poziva se funkcija da ponovo povuce sve
     * elemente sa novim podacima
     * @param   {Object}    event           jQuery event objekat
     */
    changeOccurred: function(event) {
        event.type === 'Monitor.Admin.Articles' ? this.config.awaitArticle = false : this.config.awaitSeo = false;
        if (event.type === 'Monitor.Admin.Articles' && event.info !== 'Create') {
            this.config.awaitSeo = false;
        }
        if (this.config.awaitArticle === false && this.config.awaitSeo === false) {
            this.config.awaitArticle    = true;
            this.config.awaitSeo        = true;
            if (event.info !== 'Create') {
                this.refreshData();
            }
        }
    },

    /**
     * Klik na taster za objavu clanka
     * @param   {Object}    event           jQuery event objekat
     */
    clickPublish: function(event) {
        var article_id = parseInt(event.currentTarget.dataset.articleId, 10);
        this.changeStatus(article_id, 0);
    },

    /**
     * Klik na taster za povlacenje clanka
     * @param   {Object}    event           jQuery event objekat
     */
    clickUnpublish: function(event) {
        var article_id = parseInt(event.currentTarget.dataset.articleId, 10);
        this.changeStatus(article_id, 1);
    },

    /**
     * Klik na prethodnu stranu
     * Pamtimo smer i dohvatamo podatke
     * @param   {Object}    event           JavaScript event objekat
     */
    clickPaginationPrevious: function(event) {
        this
            .setDirection(false)
            .fetchData()
        ;
    },

    /**
     * Setuje smer okretanja liste u paginaciji
     * Pamtimo smer i dohvatamo podatke
     * @param   {Object}    event           JavaScript event objekat
     */
    clickPaginationNext: function(event) {
        this
            .setDirection(true)
            .fetchData()
        ;
    },










    /**
     * Dobavlja id prvog prikazanog korisnika u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog korisnika
     */
    getDateFirst: function() {
        return this.config.first_date;
    },

    /**
     * Setuje date prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setDateFirst: function(date) {
        this.config.first_date = date;
        return this;
    },

    /**
     * Dobavlja date poslednjeg prikazanog korisnika u paginaciji
     * @return  {Number}                   date poslednje dohvacenog korisnika
     */
    getDateLast: function() {
        return this.config.last_date;
    },

    /**
     * Setuje date poslednjeg prikazanog korisnika u paginaciji
     * @param   {Object}    id              date poslednje dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setDateLast: function(date) {
        this.config.last_date = date;
        return this;
    },

    /**
     * Vraca na prvu stranu
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    resetDates: function() {
        return this
            .setDateFirst(null)
            .setDateLast(null)
        ;
    },

    /**
     * Dobavlja smer kretanja u listi za paginaciju
     * @return  {Boolean}                   Da li dohvatamo sledecu (true) ili prethodnu stranu (false)
     */
    getDirection: function() {
        return this.config.direction;
    },

    /**
     * Smer dohvatanja podataka
     * @param   {Object}    direction       true za napred i false za nazad
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    setDirection: function(direction) {
        this.config.direction = direction;
        return this;
    },

    /**
     * Dohvata trenutni filter po kategoriji (0 ako je iskljucen)
     * @return  {Number}                    ID kategorije po kom filtriramo
     */
    getFilterCategory: function() {
        return this.config.filter_category;
    },

    /**
     * Zadaje trenutni filter po kategoriju (0 ako je iskljucen)
     * @param   {Number}    category_id     ID kategorije po kom filtriramo
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    setFilterCategory: function(category_id) {
        this.config.filter_category = category_id;
        return this;
    },

    /**
     * Dohvata trenutni filter po tagu (0 ako je iskljucen)
     * @return  {Number}                    ID taga po kom filtriramo
     */
    getFilterTag: function() {
        return this.config.filter_tag;
    },

    /**
     * Zadaje trenutni filter po tagu (0 ako je iskljucen)
     * @param   {Number}    tag_id          ID taga po kom filtriramo
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    setFilterTag: function(tag_id) {
        this.config.filter_tag = tag_id;
        return this;
    },

    /**
     * Koliko clanaka dohvatamo po strani
     * @return  {Number}                    Broj clanaka
     */
    getPerPage: function() {
        return this.config.per_page;
    },

    /**
     * Datum od kog cemo dohvatati clanke
     * @return  {string}                    Datum u MySQL formatu
     */
    getStartDate: function() {
        var date = this.getDirection() ? this.getDateLast() : this.getDateFirst();
        return date  !== null? date.toISOString().slice(0, 19).replace('T', ' ') : null;
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminArticlesList", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminArticlesList", element, query_all, modifier);
    },

    /**
     * Rendanje komponenta na osnovu dohvacenih podataka
     * @param   {Object}    data            Niz clanaka koje treba prikazati
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    render: function(data) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            "articles" : data,
        });

        // Inicijalizovanje vredosti padajucih menija za kategoriju
        // (ovde jer se lista generise na serveru)
        var dropdowns = this.getElement("dropdown_category", true);
        for (var i = 0, l = dropdowns.length; i < l; i++) {
            var dropdown = dropdowns[i];
            dropdown.value = dropdown.dataset.categoryId;
        };
        // Inicijalizacija izbora datuma
        if (data.length > 0) {
            flatpickr(this.getElementSelector("change_date"), {
                enableTime: true
            });
        }

        return this;
    },










    /**
     * Promena kategorije clanka
     * @param   {Number}    article_id      ID clanka
     * @param   {Number}    category_id     ID kategorije
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    changeCategory: function(article_id, category_id) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "changeCategory",
            {
                "article_id" : article_id,
                "category_id": category_id,
            }
        );

        return this;
    },

    /**
     * Promena datuma clanka
     * @param   {Number}    article_id      ID clanka
     * @param   {string}    date            Datum clanka
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    changeDate: function(article_id, date) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "changeDate",
            {
                "article_id" : article_id,
                "date"       : date,
            }
        );

        return this;
    },

    /**
     * Dohvatanje clanaka
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    fetchData: function() {
        console.log('fetchData');
        Monitor.Main.Ajax(
            "AdminArticles",
            "fetchData",
            {
                "filter_tag":       this.getFilterTag(),
                "filter_category":  this.getFilterCategory(),
                "date":             this.getStartDate(),
                "direction":        this.getDirection(),
                "limit":            this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana posle trenutne

            },
            this.onFetchData.bind(this)
        );

        return this;
    },

    onFetchData: function(data) {
        var direction = this.getDirection();
        var per_page = this.getPerPage();

        var button_prev = this.getElement("button_prev");
        var button_next = this.getElement('button_next');

        var first_date = this.getDateFirst();
        var more = data.length > per_page;
        var length = data.length;

        if (more) {
            if (direction)  data.pop();
            else            data.shift();
        }

        var offset = new Date().getTimezoneOffset();
        if (data[0]) {
            var date_first = new Date(data[0].date);
            date_first.setMinutes(date_first.getMinutes() - offset);
            var date_last = new Date(data[data.length - 1].date);
            date_last.setMinutes(date_last.getMinutes() - offset);
        }
        if (direction) {
            if (first_date) button_prev.classList.remove("invisible");
            button_next.classList[more ? "remove" : "add"]("invisible");
        } else {
            button_next.classList.remove('invisible');
            button_prev.classList[more ? "remove" : "add"]("invisible");
        }

        this
            .setDateFirst(date_first)
            .setDateLast(date_last)
            .render(data)
        ;
    },

    /**
     * Refresh the current page
     * @return {Object}                     Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    refreshData: function() {
        console.log("REFRESH DATA");
        return this
            .resetDates()
            .setDirection(true)
            .fetchData()
        ;
    },

    /**
     * Na osnovu porsledjenog parametra status salje zahtev menjanje
     *  vidljivosti clanka za korisnike
     * @param  {int} article_id  id artikla
     * @param  {string} status     PUBLISHED ili DRAFT
     */
    changeStatus: function(article_id, status) {
        Monitor.Main.Ajax(
            "AdminArticles",
            "changeStatus",
            {
                "article_id": article_id,
                "status": status,
            },
            this.refreshData.bind(this)
        );
    },
};

document.addEventListener('DOMContentLoaded', Monitor.AdminArticles.List.init.bind(Monitor.AdminArticles.List), false);
