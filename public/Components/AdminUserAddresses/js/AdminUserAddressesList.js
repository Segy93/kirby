"use strict";

if (typeof Monitor             === "undefined") var Monitor           = {};
if (typeof Kirby.AdminUserAddresses  === "undefined") Kirby.AdminUserAddresses    = {};

/**
 * Pretraga i prikaz korisnika,
 * Izmena podataka
 * Statistika
 * Blokiranje naloga
 * Brisanje
 */
Kirby.AdminUserAddresses.List = {
    config: {
        direction:  true,    // Smer dohvatanja podataka (false za unazad, true za unapred)
        search:     "",         // Pretraga adresa
        first_id:   null,     // Prvi dohvaceni ID (koristi se za navigaciju po stranama)
        last_id:    null,      // Poslednji dohvaceni ID (koristi se za navigaciju po stranama)
        per_page:   10,        // Koliko adresa dohvatamo po strani
    },

    elements: {

        form_search:          "#admin_addresses__list__search",       // Forma za pretragu korisnika
        wrapper:              "#admin_addresses__list__content",      // Omotac za sadrzaj tabele
        userId:               ".admin_addresses__user_id",
        button_prev:          "#admin_addresses__list__prev",         // Prethodna strana
        button_next:          "#admin_addresses__list__next",         // Sledeca strana
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function() {},
    },










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JavaScript event objekat
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
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * Prvo idu osluskivaci na wrapera onda idu van wrapera i nakon toga idu sa uslovima
     * @return  {Object}                    Kirby.AdminUser.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));

        this.getElement("form_search").onsubmit = this.search.bind(this);

        // this.getElement("button_prev").addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        // this.getElement("button_next").addEventListener("click",  this.clickPaginationNext.bind(this),        false);

        document.addEventListener("Kirby.UserAddresses", this.changeOccured.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}                    Kirby.AdminUser.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_address__list__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminUser.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminUserAddresses", this.elements);
        return this;
    },










    /**
     * Obavestenje da je korisnik kreiran
     * @param  {Object} event       JavaScript event objekat
     */
    changeOccured: function(event) {
        this.fetchData();
    },

    triggerChangeOccured: function(data, info) {
        var event = new CustomEvent("Kirby.User");
        event.info = info;
        event.data = data;
        document.dispatchEvent(event);
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

    search: function(event){
        var input = event.target.elements.input.value;
        this
            .resetIDs()
            .setSearch(input)
            .fetchData()
        ;
        return false;
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
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
     */
    setDirection: function(direction) {
        this.config.direction = direction;
        return this;
    },

    /**
     * Dobavlja id prve prikazane adrese u paginaciji
     * @return  {Number}                    ID prvo-dohvacene adrese
     */
    getIDFirst: function() {
        return this.config.first_id;
    },

    /**
     * Setuje id prve prikazane adrese u paginaciji
     * @param   {Object}    id              ID prve dohvacene adrese
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIDFirst: function(id) {
        this.config.first_id = id;
        return this;
    },

    /**
     * Dobavlja id poslednjeg prikazane adrese u paginaciji
     * @return  {Number}                    ID poslednje dohvacene adrese
     */
    getIDLast: function() {
        return this.config.last_id;
    },

    /**
     * Setuje id poslednjeg prikaze adrese u paginaciji
     * @param   {Object}    id              ID poslednje dohvacene adrese
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIDLast: function(id) {
        this.config.last_id = id;
        return this;
    },

    /**
     * Vraca na prvu stranu
     * @return  {Object}                    Kirby.AdminUsers.List objekat, za ulancavanje funkcija
     */
    resetIDs: function() {
        return this
            .setIDFirst(null)
            .setIDLast(null)
        ;
    },

    /**
     * Koliko adresa dohvatamo po strani
     * @return  {Number}                    Broj adresa
     */
    getPerPage: function() {
        return this.config.per_page;
    },

    /**
     * ID korisnika od kog cemo dalje dohvatati
     * @return  {Number}                    ID adrese
     */
    getAddressID: function() {
        return this.getDirection() ? this.getIDLast() : this.getIDFirst();
    },

    /**
     * Dohvata vrednost stringa za pretragu
     * @return  {String}                    Trenutno aktuelna pretraga
    */
    getSearch: function() {
        return this.config.search;
    },

    /**
     * Setuje string koji se pretrazuje
     * @param {Object}   search  string koja se pretrazuje
    */
    setSearch: function(search) {
        this.config.search = search;
        return this;
    },









    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminUserAddresses", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminUserAddresses", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     user            Prosledjeni podaci
     */
    render: function(addresses) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            addresses:    addresses,
        });

        return this;
    },







    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Kirby.AdminUser.List objekat, za ulancavanje funkcija
     */
    fetchData: function(address_id) {
        if (typeof address_id === "undefined") address_id = this.getAddressID();
        var user_id = this.getElement("userId").dataset.userId;
        Kirby.Main.Ajax(
            "AdminUserAddresses",
            "fetchData",
            {
                user_id:      user_id,
                search:       this.getSearch(),
                address_id:   this.getAddressID(),
                direction:    this.getDirection(),
                limit:        this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana posle trenutne
            },
            this.onFetchData.bind(this),
        );
        return this;
    },

    onFetchData: function(data) {
        // STRANICENJE

        // var direction = this.getDirection();
        // var per_page = this.getPerPage();

        // var button_prev = this.getElement("button_prev");
        // var button_next = this.getElement('button_next');

        // var id_first = this.getIDFirst();

        // var more = data.length > per_page;

        // if (more) {
        //     if (direction)  data.pop();
        //     else            data.shift();
        // }

        // if (direction) {
        //     if (id_first) button_prev.classList.remove("invisible");
        //     button_next.classList[more ? "remove" : "add"]("invisible");
        // } else {
        //     button_next.classList.remove('invisible');
        //     button_prev.classList[more ? "remove" : "add"]("invisible");
        // }
        // if(data.length > 0) {
        //     this
        //         .setIDFirst(data[0].id)
        //         .setIDLast(data[data.length - 1].id)
        //     ;
        // }
        this.render(data);
    },

};

document.addEventListener("DOMContentLoaded", Kirby.AdminUserAddresses.List.init.bind(Kirby.AdminUserAddresses.List), false);
