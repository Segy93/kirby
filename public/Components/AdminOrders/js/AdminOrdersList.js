"use strict";

if (typeof Monitor             === "undefined") var Monitor           = {};
if (typeof Monitor.AdminOrders  === "undefined") Monitor.AdminOrders    = {};

/**
 * Pretraga i prikaz narudzbina,
 * Izmena podataka
 * Statistika
 * Blokiranje naloga
 * Brisanje
 */
Monitor.AdminOrders.List = {
    config: {
        direction:      true,    // Smer dohvatanja podataka (false za unazad, true za unapred)
        search:         "",         // Pretraga narudzbina
        filter_status:  null,   // Filtriranje po statusu
        first_id:       null,     // Prvi dohvaceni ID (koristi se za navigaciju po stranama)
        last_id:        null,      // Poslednji dohvaceni ID (koristi se za navigaciju po stranama)
        per_page:       20,        // Koliko narudzbina dohvatamo po strani
    },

    elements: {
        form_search:          "#admin_orders__list__search",       // Forma za pretragu narudzbina
        wrapper:              "#admin_orders__list__content",      // Omotac za sadrzaj tabele
        button_prev:          "#admin_orders__list__prev",         // Prethodna strana
        button_next:          "#admin_orders__list__next",         // Sledeca strana
        dropdown_status:      ".admin_orders__list_statuses",     // Padajuci meni za promenu statusa
        search_input:         ".admin_orders__list__search_input",
        filter_status:        ".admin_orders__list__filter_statuses",
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function() {},
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
     */
    init: function() {
        this
            .registerElements()
            .initTemplates()
            .initListeners()
            .isSearchSet()
            .fetchData()
        ;
    },

    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * Prvo idu osluskivaci na wrapera onda idu van wrapera i nakon toga idu sa uslovima
     * @return  {Object}    Monitor.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        this.getElement("form_search").onsubmit = this.search.bind(this);
        this.getElement("button_prev").addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        this.getElement("button_next").addEventListener("click",  this.clickPaginationNext.bind(this),        false);
        this.getElement("filter_status").addEventListener("change", this.filterStatus.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object} Monitor.AdminOrders.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_orders__list__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}   Monitor.AdminOrders.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminOrders", this.elements);
        return this;
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
     * Gemerise CSV fajl sa informacijama o korisnicima i preuzima ga
     * @param   {Object}    event           JavaScript event objekat
     */
    clickExportToCSV: function(event) {
        this.exportToCSV();
    },

    /**
     * Poslata forma za pretragu
     * @param   {Object}    event           JS event objekat
     * @return  {Boolean}                   false, da spreci standardni POST forme
     */
    search: function(event) {
        var input = event.target.elements.input.value;
        this
            .resetIDs()
            .setSearch(input)
            .fetchData()
        ;
        return false;
    },

    filterStatus: function(event) {
        var selected_value = event.currentTarget.value;
        var filter = null;
        if (selected_value !== "") {
            filter = parseInt(selected_value, 10);
        }
            this
                .resetIDs()
                .setFilterStatus(filter)
                .fetchData()
            ;
            return false;
    },

    isSearchSet: function () {
        var input = this.getElement("search_input");
        if (input.value !== "") {
            this.setSearch(input.value);
        }
        return this;
    },










    /**
     * Dobavlja smer kretanja u listi za paginaciju
     * @return  {Boolean} sDa li dohvatamo sledecu (true) ili prethodnu stranu (false)
     */
    getDirection: function() {
        return this.config.direction;
    },

    /**
     * Smer dohvatanja podataka
     * @param   {Object}    direction      true za napred i false za nazad
     * @return  {Object}                   Monitor.AdminOrders.List objekat, za ulancavanje funkcija
     */
    setDirection: function(direction) {
        this.config.direction = direction;
        return this;
    },

    /**
     * Dobavlja id prvog prikazanog narudzbina u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog narudzbina
     */
    getIDFirst: function() {
        return this.config.first_id;
    },

    /**
     * Setuje id prvog prikazanog narudzbina u paginaciji
     * @param   {Object}    id        ID prvog dohvacenog narudzbina
     * @return  {Object}              Monitor.AdminOrders.List objekat, za ulancavanje funkcija
    */
    setIDFirst: function(id) {
        this.config.first_id = id;
        return this;
    },

    /**
     * Dobavlja id poslednjeg prikazanog narudzbina u paginaciji
     * @return  {Number}                    ID poslednje dohvacenog narudzbina
     */
    getIDLast: function() {
        return this.config.last_id;
    },

    /**
     * Setuje id poslednjeg prikazanog narudzbina u paginaciji
     * @param   {Object}    id             ID poslednje dohvacenog narudzbina
     * @return  {Object}                   Monitor.AdminOrders.List objekat, za ulancavanje funkcija
    */
    setIDLast: function(id) {
        this.config.last_id = id;
        return this;
    },

    /**
     * Vraca na prvu stranu
     * @return  {Object}                   Monitor.AdminOrders.List objekat, za ulancavanje funkcija
     */
    resetIDs: function() {
        return this
            .setIDFirst(null)
            .setIDLast(null)
        ;
    },

    /**
     * Koliko narudzbina dohvatamo po strani
     * @return  {Number}                    Broj narudzbina
     */
    getPerPage: function() {
        return this.config.per_page;
    },

    /**
     * ID narudzbina od kog cemo dalje dohvatati
     * @return  {Number}                    ID narudzbina
     */
    getOrderID: function() {
        return this.getDirection() ? this.getIDLast() : this.getIDFirst();
    },

    /**
     * Dohvata vrednost stringa za pretragu
     * @return  {String}                    Trenutno aktuelna pretraga
    */
    getSearch: function() {
        return this.config.search;
    },

    getFilterStatus: function() {
        return this.config.filter_status;
    },

    /**
     * Setuje string koji se pretrazuje
     * @param {Object}   search  string koja se pretrazuje
    */
    setSearch: function(search) {
        this.config.search = search;
        return this;
    },

    setFilterStatus: function(filter) {
        this.config.filter_status = filter;
        return this;
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminOrders", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminOrders", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     orders            Prosledjeni podaci
     */
    render: function(orders, statuses) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            orders:    orders,
            statuses: statuses,
        });

        return this;
    },










    /**
    * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
    * @return {Object}                    Monitor.AdminOrders.List objekat, za ulancavanje funkcija
    */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminOrders",
            "fetchData",
            {
                search:         this.getSearch(),
                filter_status:  this.getFilterStatus(),
                order_id:       this.getOrderID(),
                direction:      this.getDirection(),
                limit:          this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana posle
            },
            this.onFetchData.bind(this)
        );
        return this;
    },

    onFetchData: function(data_raw) {
        var statuses = data_raw.statuses;
        var data = data_raw.orders;
        var direction = this.getDirection();
        var per_page = this.getPerPage();

        var button_prev = this.getElement("button_prev");
        var button_next = this.getElement("button_next");

        var id_first = this.getIDFirst();

        var more = data.length > per_page;

        if (more) {
            if (direction)  data.pop();
            else            data.shift();
        }

        if (direction) {
            if (id_first) button_prev.classList.remove("invisible");
            button_next.classList[more ? "remove" : "add"]("invisible");
        } else {
            button_next.classList.remove("invisible");
            button_prev.classList[more ? "remove" : "add"]("invisible");
        }
        if (data.length > 0) {
            this
                .setIDFirst(data[0].id)
                .setIDLast(data[data.length - 1].id)
                .render(data, statuses)
            ;
        } else {
            this.render(data, statuses);
        }
    },

    /**
    * Refresh the current page
    * @return {Object}                     Monitor.AdminOrders.List objekat, za ulancavanje funkcija
    */
    refreshData: function() {
        var id_first = this.getIDFirst();
        return this
            .setDirection(true)
            .resetIDs()
            .setIDLast(id_first + 1)
            .fetchData()
        ;
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminOrders.List.init.bind(Monitor.AdminOrders.List), false);
