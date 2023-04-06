"use strict";

if (typeof Monitor               === "undefined") var Monitor               = {};
if (typeof Kirby.AdminBanners === "undefined") Kirby.AdminBanners     = {};

/**
 *
 * Tabela sa listom clanaka i akcijama nad njima
 *
 */
Kirby.AdminBanners.List = {
    config: {                   // Konfiguracija komponente
        direction:          true,  // Smer dohvatanja podataka (false za unazad, true za unapred)
        first_id:         null,  // Prvi dohvaceni id (koristi se za navigaciju po stranama)
        last_id:          null,  // Poslednji dohvaceni id(koristi se za navigaciju po stranama)
        per_page:           20,    // Koliko clanaka dohvatamo po strani
        filter_tag:         0,     // ID taga po kome filtriramo (0 ako je iskljucen)
        filter_category:    0,     // ID kategorije po kojoj filtriramo (0 ako je iskljucen)
        search:             null,
        banners:            null,
        pages:              null,
    },

    elements: {                 // Selektori elemenata koje komponenta koristi
        button_publish:       ".admin_banner__button_publish",       // Taster za objavu banera
        button_return:        ".admin_banner__button_return",        // Taster za povlacenje banera
        dropdown_position:    ".admin_banners__list_position",     // Padajuci meni za promenu pozicije
        select_page:          "#admin_banners__list_page",

        button_prev:          "#admin_banners__list__prev",          // Prethodna strana
        button_next:          "#admin_banners__list__next",          // Sledeca strana

        wrapper:              "#admin_banners__list_content",        // Element koji sadrzi listu clanaka
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
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));

        $wrapper.on("click",    this.getElementSelector("button_publish"),      this.clickPublish.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_return"),       this.clickUnpublish.bind(this));
        $wrapper.on("change",   this.getElementSelector("dropdown_position"),   this.changedPosition.bind(this));
        $wrapper.on("change",   this.getElementSelector("select_page"),         this.pageSelected.bind(this));


        this.getElement("button_prev").addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        this.getElement("button_next").addEventListener("click",  this.clickPaginationNext.bind(this),        false);

        document.addEventListener("Kirby.Admin.Banners", this.changeOccurred.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona koje komponenta koristi
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_banners__list_temp").innerHTML);
        return this;
    },

    /**
     * Registrovanje elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    registerElements()  {
        Kirby.Main.Dom.register("AdminBannersList", this.elements);
        return this;
    },










    /**
     * Promenjena je kategorija clanka, cuvamo izmene
     * @param   {Object}    event           jQuery event objekat
     */
    changedPosition: function(event) {
        var element         = event.currentTarget;
        var banner_id       = parseInt(element.dataset.bannerId, 10);
        var position_id     = parseInt(element.value, 10);


        this.changePosition(banner_id, position_id);
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
    clickPublish: function(event) {
        var banner_id = parseInt(event.currentTarget.dataset.bannerId, 10);
        this.changeStatus(banner_id, 1);
    },

    /**
     * Klik na taster za povlacenje clanka
     * @param   {Object}    event           jQuery event objekat
     */
    clickUnpublish: function(event) {
        var banner_id = parseInt(event.currentTarget.dataset.bannerId, 10);
        this.changeStatus(banner_id, 0);
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

    pageSelected(event) {
        var page_id = parseInt(event.currentTarget.value, 10);
        this.getPagePositions(page_id);
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
    getIdFirst: function() {
        return this.config.first_id;
    },

    /**
     * Setuje id prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIdFirst: function(id) {
        this.config.first_id = id;
        return this;
    },

    /**
    * ID korisnika od kog cemo dalje dohvatati
    * @return  {Number}                    ID korisnika
    */
    getUserID: function() {
        return this.getDirection() ? this.getIdLast() : this.getIdFirst();
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
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setSearch: function(search) {
        this.config.search = search;
        return this;
    },


    /**
     * Dobavlja date poslednjeg prikazanog korisnika u paginaciji
     * @return  {Number}                   date poslednje dohvacenog korisnika
     */
    getIdLast: function() {
        return this.config.last_id;
    },

    /**
     * Setuje date poslednjeg prikazanog korisnika u paginaciji
     * @param   {Object}    id              date poslednje dohvacenog korisnika
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIdLast: function(id) {
        this.config.last_id = id;
        return this;
    },

    /**
     * Vraca na prvu stranu
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
     */
    resetIds: function() {
        return this
            .setIdFirst(null)
            .setIdLast(null)
        ;
    },

    /**
     * Dobavlja smer kretanja u listi za paginaciju
     * @return  {Boolean}  Da li dohvatamo sledecu (true) ili prethodnu stranu (false)
     */
    getDirection: function() {
        return this.config.direction;
    },

    /**
     * Smer dohvatanja podataka
     * @param   {Object}    direction       true za napred i false za nazad
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
     */
    setDirection: function(direction) {
        this.config.direction = direction;
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
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element     Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all   Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier    BEM modifier za selektor
    */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminBannersList", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element     Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all   Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier    BEM modifier za selektor
    */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminBannersList", element, query_all, modifier);
    },

    /**
     * Rendanje komponenta na osnovu dohvacenih podataka
     * @param   {Object}    data            Niz clanaka koje treba prikazati
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    render: function(data) {
        this.config.banners = data.banners;
        this.config.pages   = data.pages;
        this.getElement("wrapper").innerHTML = this.templates.main({
            banners:    data.banners,
            pages:      data.pages,
        });


        return this;
    },

    renderPositions: function(data) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            banners:    this.config.banners,
            pages:      this.config.pages,
            positions:  data,
        });


        return this;
    },










    /**
     * Promena kategorije clanka
     * @param   {Number}    banner_id      ID clanka
     * @param   {Number}    category_id     ID kategorije
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    changePosition: function(banner_id, position_id) {
        Kirby.Main.Ajax(
            "AdminBanners",
            "changePosition",
            {
                banner_id:      banner_id,
                position_id:    position_id,
            }
        );

        return this;
    },

    /**
     * Dohvatanje clanaka
     * @return  {Object}                    Kirby.AdminBanners.List
     */
    fetchData: function() {
        Kirby.Main.Ajax(
            "AdminBanners",
            "fetchData",
            {
                direction: this.getDirection(),
                limit:     this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana posle
                search:     this.getSearch(),
                banner_id:  this.getUserID(),

            },
            this.onFetchData.bind(this)
        );

        return this;
    },

    onFetchData: function(data) {
        var direction = this.getDirection();
        var per_page = this.getPerPage();
        var banners = data.banners;

        var button_prev = this.getElement("button_prev");
        var button_next = this.getElement("button_next");

        var id_first = this.getIdFirst();
        var more = banners.length > per_page;

        if (more) {
            if (direction)  banners.pop();
            else            banners.shift();
        }

        if (direction) {
            if (id_first) button_prev.classList.remove("invisible");
            button_next.classList[more ? "remove" : "add"]("invisible");
        } else {
            button_next.classList.remove("invisible");
            button_prev.classList[more ? "remove" : "add"]("invisible");
        }
        if (banners.length > 0) {
            this
                .setIdFirst(banners[0].id)
                .setIdLast(banners[banners.length - 1].id)
            ;
        }
        this.render(data)
    },

    /**
     * Refresh the current page
     * @return {Object}                     Kirby.AdminUsers.List objekat, za ulancavanje funkcija
     */
    refreshData: function() {
        return this
            .resetIds()
            .setDirection(true)
            .fetchData()
        ;
    },

    /**
     * Na osnovu porsledjenog parametra status salje zahtev menjanje
     *  vidljivosti clanka za korisnike
     * @param  {int} banner_id  id artikla
     * @param  {string} status     PUBLISHED ili DRAFT
     */
    changeStatus: function(banner_id, status) {
        Kirby.Main.Ajax(
            "AdminBanners",
            "changeStatus",
            {
                banner_id:  banner_id,
                status:     status,
            },
            this.refreshData.bind(this)
        );
    },

    getPagePositions: function(page_id) {
        Kirby.Main.Ajax(
            "AdminBanners",
            "getPagePositions",
            {
                page_id: page_id,
            },
            this.renderPositions.bind(this)
        );
        return this;
    }
};

document.addEventListener("DOMContentLoaded", Kirby.AdminBanners.List.init.bind(Kirby.AdminBanners.List), false);
