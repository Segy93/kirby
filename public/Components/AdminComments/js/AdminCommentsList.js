"use strict";

if (typeof Monitor               === "undefined") var Monitor               = {};
if (typeof Monitor.AdminComments === "undefined") Monitor.AdminComments     = {};

/**
 *
 * Tabela sa listom clanaka i akcijama nad njima
 *
 */
Monitor.AdminComments.List = {
    config: {                   // Konfiguracija komponente
        direction:      true,   // Smer dohvatanja podataka (false za unazad, true za unapred)
        first_date:     null,   // Prvi dohvaceni DATE (koristi se za navigaciju po stranama)
        last_date:      null,   // Poslednji dohvaceni DATE (koristi se za navigaciju po stranama)
        per_page:       20,     // Koliko clanaka dohvatamo po strani
        status_show:    true,   // Prikazuje sve ako je false ako je true samo neobjavljene
        search:         "",
        comment_type:   "Article",
    },

    elements: {                 // Selektori elemenata koje komponenta koristi
        button_status:        ".admin_comment__status",       // Taster za menjanje statusa komentara
        dropdown_category:    ".admin_comments__list_categories",     // Padajuci meni za promenu kategorije

        button_prev:          "#admin_comments__list__prev",          // Prethodna strana
        button_next:          "#admin_comments__list__next",          // Sledeca strana
        published_toggle:     "#admin_comments__published_toggle",
        search_field:         "#admin_comments__search",
        form_search:          "#admin_comments__search_form",
        comment_type:         "#admin_comments__type",

        wrapper:              "#admin_comments__table_content",        // Element koji sadrzi listu clanaka
    },

    templates: {                // Sabloni koje komponenta koristi
        main: function() {},   // Glavni sablon, za formiranje tabele od podataka
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
     * @return  {Object}                    Monitor.AdminComments.List
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));

        $wrapper.on("click",    this.getElementSelector("button_status"),      this.clickStatusChange.bind(this));


        this.getElement("button_prev").addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        this.getElement("button_next").addEventListener("click",  this.clickPaginationNext.bind(this),        false);
        this.getElement("published_toggle").addEventListener("change", this.changeShownStatus.bind(this),       false);
        this.getElement("form_search").addEventListener("submit",  this.submitSearch.bind(this),    false);
        this.getElement("comment_type").addEventListener("change",  this.changeType.bind(this),    false);

        document.addEventListener("Monitor.Admin.Comments", this.changeOccurred.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona koje komponenta koristi
     * @return  {Object}                    Monitor.AdminComments.List
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_comments__table_temp").innerHTML);
        return this;
    },

    /**
     * Registrovanje elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminComments.List
     */
    registerElements()  {
        Monitor.Main.DOM.register("AdminCommentsList", this.elements);
        return this;
    },










    /**
     * Prilikom globalnog eventa da se desila promena poziva se funkcija da ponovo povuce sve
     * elemente sa novim podacima
     * @param   {Object}    event           jQuery event objekat
     */
    changeOccurred: function(event) {
        this.refreshData();
    },

    /**
     * Klik na taster za objavu clanka
     * @param   {Object}    event           jQuery event objekat
     */
    clickStatusChange: function(event) {
        var status      = parseInt(event.currentTarget.dataset.status, 10);
        var comment_id  = parseInt(event.currentTarget.dataset.commentId, 10);

        var status_next = status === 1 ? 0 : 1;

        this.changeCommentStatus(comment_id, status_next);
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

    changeShownStatus: function(event) {
        event.currentTarget.checked ? this.setStatusShow(true) : this.setStatusShow(false);
        this.refreshData();
    },

    changeType: function(event) {
        var type            = event.currentTarget.value;

        this.setType(type);
        this.refreshData();
    },

    submitSearch: function(event) {
        event.preventDefault();
        var form            = event.currentTarget;
        var elements         = form.elements;
        var type            = elements.type.value;

        var search_query    = elements.search.value;

        this.setType(type);
        this.setSearch(search_query);
        this.refreshData();
    },


    /**
     * Dobavlja id prvog prikazanog korisnika u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog korisnika
     */
    getType: function() {
        return this.config.comment_type;
    },

    /**
     * Setuje date prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setType: function(type) {
        this.config.comment_type = type;
        return this;
    },

    /**
     * Dobavlja id prvog prikazanog korisnika u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog korisnika
     */
    getSearch: function() {
        return this.config.search;
    },

    /**
     * Setuje date prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setSearch: function(search) {
        this.config.search = search;
        return this;
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
     * Dobavlja id prvog prikazanog korisnika u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog korisnika
     */
    getStatusShow: function() {
        return this.config.status_show;
    },

    /**
     * Setuje date prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setStatusShow: function(status) {
        this.config.status_show = status;
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
     * @return  {Boolean}    Da li dohvatamo sledecu (true) ili prethodnu stranu (false)
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
     * @return  {Object}                    Monitor.AdminComments.List
     */
    setFilterCategory: function(category_id) {
        this.config.filter_category = category_filter;
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
     * @return  {Object}                    Monitor.AdminComments.List
     */
    setFilterTag: function(tag_id) {
        this.config.filter_tag = tag_filter;
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
        return date ? date.toISOString().slice(0, 19).replace("T", "") : null;
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminCommentsList", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je
     *                                  query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminCommentsList", element, query_all, modifier);
    },

    /**
     * Rendanje komponenta na osnovu dohvacenih podataka
     * @param   {Object}    data            Niz clanaka koje treba prikazati
     * @return  {Object}                    Monitor.AdminComments.List
     */
    render: function(data) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            comments: data,
        });

        // Inicijalizovanje vredosti padajucih menija za kategoriju
        // (ovde jer se lista generise na serveru)
        var dropdowns = this.getElement("dropdown_category", true);
        for (var i = 0, l = dropdowns.length; i < l; i++) {
            var dropdown = dropdowns[i];
            dropdown.value = dropdown.dataset.categoryId;
        }

        return this;
    },










    /**
     * Dohvatanje clanaka
     * @return  {Object}                    Monitor.AdminComments.List
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminComments",
            "fetchData",
            {
                type:             this.getType(),
                date:             this.getStartDate(),
                direction:        this.getDirection(),
                limit:            this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana
                show_status:      this.getStatusShow(),
                search:           this.getSearch(),

            },
            this.onFetchData.bind(this)
        );

        return this;
    },

    onFetchData: function(data) {
        var direction = this.getDirection();
        var per_page = this.getPerPage();

        var button_prev = this.getElement("button_prev");
        var button_next = this.getElement("button_next");

        var first_date = this.getDateFirst();
        var more = data.length > per_page;

        if (more) {
            if (direction)  data.pop();
            else            data.shift();
        }

        var offset = new Date().getTimezoneOffset();
        if (data[0]) {
            var date_first = new Date(data[0].date.date);
            date_first.setMinutes(date_first.getMinutes() - offset);
            var date_last = new Date(data[data.length - 1].date.date);
            date_last.setMinutes(date_last.getMinutes() - offset);
        }
        if (direction) {
            if (first_date) button_prev.classList.remove("invisible");
            button_next.classList[more ? "remove" : "add"]("invisible");
        } else {
            button_next.classList.remove("invisible");
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
        return this
            .resetDates()
            .setDirection(true)
            .fetchData()
        ;
    },

    /**
     * Na osnovu porsledjenog parametra status salje zahtev menjanje
     *  vidljivosti clanka za korisnike
     * @param  {int} comment_id  id artikla
     * @param  {string} status     PUBLISHED ili DRAFT
     */
    changeCommentStatus: function(comment_id, status) {
        Monitor.Main.Ajax(
            "AdminComments",
            "changeCommentStatus",
            {
                comment_id: comment_id,
                status:     status,
            },
            this.refreshData.bind(this)
        );
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminComments.List.init.bind(Monitor.AdminComments.List), false);
