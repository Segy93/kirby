"use strict";

if (typeof Monitor             === "undefined") var Monitor           = {};
if (typeof Monitor.AdminUsers  === "undefined") Monitor.AdminUsers    = {};

/**
 * Pretraga i prikaz korisnika,
 * Izmena podataka
 * Statistika
 * Blokiranje naloga
 * Brisanje
 */
Monitor.AdminUsers.List = {
    config: {
        direction:  true,    // Smer dohvatanja podataka (false za unazad, true za unapred)
        search:     "",         // Pretraga korisnika
        first_id:   null,     // Prvi dohvaceni ID (koristi se za navigaciju po stranama)
        last_id:    null,      // Poslednji dohvaceni ID (koristi se za navigaciju po stranama)
        per_page:   10,        // Koliko korisnika dohvatamo po strani
    },

    elements: {

        form_search:          "#admin_users__list__search",       // Forma za pretragu korisnika

        button_ban_temporary:   ".admin_users__list__ban_temporary", // Privremena blokada naloga
        button_ban_permanent:   ".admin_users__list__ban_permanent", // Trajna blokada naloga
        button_send_mail:        ".admin_users__list__activation",   // Ponovno slanje aktivacionog maila
        button_change_password: ".admin_users__list__password_email",


        avatar_input:         ".admin_users__list__avatar_input", // <input /> za sliku
        avatar_image:         ".admin_users__list__avatar",       // Avatar korrisnika

        wrapper:              "#admin_users__list__content",      // Omotac za sadrzaj tabele

        button_prev:          "#admin_users__list__prev",         // Prethodna strana
        button_next:          "#admin_users__list__next",         // Sledeca strana
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
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));

        $wrapper.on("click",    this.getElementSelector("avatar_image"),            this.clickImage.bind(this));
        $wrapper.on("change",   this.getElementSelector("avatar_input"),            this.changedImage.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_ban_permanent"),    this.clickChangeStatus.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_ban_temporary"),    this.clickBanTemporary.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_send_mail"),        this.clickSendMail.bind(this));
        $wrapper.on("click",    this.getElementSelector("button_change_password"),  this.clickSendPassword.bind(this));

        this.getElement("form_search").onsubmit = this.search.bind(this);

        /* this.getElement("export_to_csv").addEventListener("click",  this.clickExportToCSV.bind(this), false); */
        this.getElement("button_prev").addEventListener("click",  this.clickPaginationPrevious.bind(this),    false);
        this.getElement("button_next").addEventListener("click",  this.clickPaginationNext.bind(this),        false);

        document.addEventListener("Monitor.User", this.changeOccured.bind(this), false);

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_users__list__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminUsers", this.elements);
        return this;
    },










    /**
     * Klik na sliku gadjajuci polje za odabir slike.
     * @param  {Object} event       JavaScript event objekat
     */
    clickImage: function(event) {
        var input = event.currentTarget.previousSibling;
        if (input) input.click(); // Ako nema dozvolu za izmenu, nece biti input-a
    },

    /**
     * Promena slike
     * @param  {Object} event       JavaScript event objekat
     */
    changedImage: function(event) {
        var user_id = parseInt(event.target.dataset.userId, 10);
        this.updateImage(user_id, event.target.files[0]);
    },

    /**
     * Obavestenje da je korisnik kreiran
     * @param  {Object} event       JavaScript event objekat
     */
    changeOccured: function(event) {
        var refresh_on = ["Update.Status", "Update.Image", "Update", "Ban"];
        var fetch_on = ["Create", "Delete"];

        var id_first = this.getIDFirst();
        var id_last = this.getIDLast();

        if (fetch_on.indexOf(event.info) !== -1) {
            this
                .resetIDs()
                .fetchData()
            ;
        } else if (refresh_on.indexOf(event.info) !== -1) {
            if (typeof event.data.id === "undefined" || (event.data.id >= id_last && event.data.id <= id_first)) {
                this.refreshData();
            }
        }
    },

    triggerChangeOccured: function(data, info) {
        var event = new CustomEvent("Monitor.User");
        event.info = info;
        event.data = data;
        document.dispatchEvent(event);
    },

    /**
     * Banuje korisnika na odredjeni period
     * @param  {Object} event       JavaScript event objekat
     */
    clickChangeStatus: function(event) {
        var data    = event.currentTarget.dataset;
        var user_id = parseInt(data.userId, 10);
        var status = data.status === "true" ? 1 : 0;
        this.updateStatus(user_id, status);
    },

    /**
     * Banuje korisnika za stalno
     * @param  {Object} event       JavaScript event objekat
     */
    clickBanTemporary: function(event) {
        event.preventDefault();
        var data    = event.currentTarget.dataset;
        var user_id = parseInt(data.userId, 10);
        var length  = parseInt(data.banLength, 10);
        this.banUser(user_id, length);
    },

    clickSendMail: function(event) {
        var data    = event.currentTarget.dataset;
        var user_id = parseInt(data.userId, 10);
        this.sendMail(user_id);
    },

    clickSendPassword: function(event) {
        var data    = event.currentTarget.dataset;
        var user_id = parseInt(data.userId, 10);

        this.changePassword(user_id);
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
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    setDirection: function(direction) {
        this.config.direction = direction;
        return this;
    },

    /**
     * Dobavlja id prvog prikazanog korisnika u paginaciji
     * @return  {Number}                    ID prvo-dohvacenog korisnika
     */
    getIDFirst: function() {
        return this.config.first_id;
    },

    /**
     * Setuje id prvog prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID prvog dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIDFirst: function(id) {
        this.config.first_id = id;
        return this;
    },

    /**
     * Dobavlja id poslednjeg prikazanog korisnika u paginaciji
     * @return  {Number}                    ID poslednje dohvacenog korisnika
     */
    getIDLast: function() {
        return this.config.last_id;
    },

    /**
     * Setuje id poslednjeg prikazanog korisnika u paginaciji
     * @param   {Object}    id              ID poslednje dohvacenog korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
    */
    setIDLast: function(id) {
        this.config.last_id = id;
        return this;
    },

    /**
     * Vraca na prvu stranu
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    resetIDs: function() {
        return this
            .setIDFirst(null)
            .setIDLast(null)
        ;
    },

    /**
     * Koliko korisnika dohvatamo po strani
     * @return  {Number}                    Broj korisnika
     */
    getPerPage: function() {
        return this.config.per_page;
    },

    /**
     * ID korisnika od kog cemo dalje dohvatati
     * @return  {Number}                    ID korisnika
     */
    getUserID: function() {
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
        return Monitor.Main.DOM.getElement("AdminUsers", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminUsers", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     users            Prosledjeni podaci
     */
    render: function(users) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            "users":    users,
        });

        return this;
    },

    buttonRender: function(data, params) {
        if(params.id) {
            this.getElement(params.element, false, params.id).innerHTML = params.message;
        } else {
            this.getElement(params.element).innerHTML = params.message;
        }
    },










    /**
     * Izvoz korisnika u CSV fajl
     * @return {Object}                     Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    exportToCSV: function() {
        Monitor.Main.Ajax(
            "AdminUsers",
            "exportToCSV",
            {
                "search": this.getSearch(),
            },
            undefined,
            undefined,
            true
        );

        return this;
    },

    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    fetchData: function(user_id) {
        if (typeof user_id === "undefined") user_id = this.getUserID();

        Monitor.Main.Ajax(
            "AdminUsers",
            "fetchData",
            {
                search:       this.getSearch(),
                user_id:      this.getUserID(),
                direction:    this.getDirection(),
                limit:        this.getPerPage() + 1, // Jedan vise, da znamo ima li jos strana posle trenutne
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
            button_next.classList.remove('invisible');
            button_prev.classList[more ? "remove" : "add"]("invisible");
        }
        if(data.length > 0) {
            this
                .setIDFirst(data[0].id)
                .setIDLast(data[data.length - 1].id)
            ;
        }
        this.render(data);
    },

    /**
     * Refresh the current page
     * @return {Object}                     Monitor.AdminUsers.List objekat, za ulancavanje funkcija
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


    /**
     * Promena statusa korisnika (true ako mu je nalog blokiran, false da je aktivan)
     * @param   {Number}    user_id         ID korisnika kome menjamo status
     * @param   {Boolean}   status          Status korisnika
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    updateStatus: function(user_id, status) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "updateStatus",
            {
                user_id:    user_id,
                status:     status,
            },
            this.triggerChangeOccured,
            "Update.Status"
        );

        return this;
    },

    /**
     * Blokiranje naloga korisniku na odredjeno vreme
     * @param   {Number}    user_id         ID korisnika koga blokiramo
     * @param   {Number}    length          Trajanje blokade, u danima
     * @return  {Object}                    Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    banUser: function(user_id, length) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "banTemporarily",
            {
                user_id:    user_id,
                length:     length,
            },
            this.triggerChangeOccured,
            "Ban"
        );

        return this;
    },

    sendMail: function(id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "sendMail",
            {
                user_id: id,
            },
            this.buttonRender.bind(this),
            {
                element:    "button_send_mail",
                message:    "Aktivacioni mail poslat",
                id:         id
            }
        );
    },


    changePassword: function(id) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "changePassword",
            {
                user_id:    id,
            },
            this.buttonRender.bind(this),
            {
                element: "button_change_password",
                message: "Email za izmenu lozinke poslat",
                id:      id
            }
        )
        ;
    },

    /**
     * Promena slike korisnika
     * @param  {Number}     id              ID korisnika kome menjamo sliku
     * @param  {File}       picture         Nova slika
     * @return {Object}                     Monitor.AdminUsers.List objekat, za ulancavanje funkcija
     */
    updateImage: function(id, picture) {
        Monitor.Main.Ajax(
            "AdminUsers",
            "updateImage",
            {
                user_id:            id,
                profile_picture:    picture,
            },
            this.triggerChangeOccured,
            "Update.Image",
            true
        );

        return this;
    },
};

document.addEventListener("DOMContentLoaded", Monitor.AdminUsers.List.init.bind(Monitor.AdminUsers.List), false);