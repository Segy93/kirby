"use strict";

if (typeof Monitor               === "undefined") var Monitor           = {};
if (typeof Monitor.AdminBanners  === "undefined") Monitor.AdminBanners  = {};

/**
 *
 * Modal za izmenu clanka
 *
 */
Monitor.AdminBanners.Change = {

    config: {
        position_id: null,
    },

    elements: { // Selektori elemenata koje komponenta koristi
        form:               "#admin_banner__change_form",
        name:               "#admin_banners__change_name",
        image:              ".admin_banners__list_picture",
        image_input:        "#admin_banners__image_change",
        link:               "#admin_banners__change_link",
        url:                "#admin_banners__change_url",
        wrapper:            "#admin_banners__modal_change",
        wrapper_list:       "#admin_banners__list_content",
        select_page:        "#admin_banners__change_page",
        select_position:    "#admin_banners__change_position",
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
     * @return  {Object}                    Monitor.AdminBanners.Change
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper_list"));

        $wrapper.on("click", this.getElementSelector("image"), this.clickImage.bind(this));
        $wrapper.on("change", this.getElementSelector("image_input"), this.changedImage.bind(this));
        $wrapper.on("input", this.getElementSelector("name"), this.blurName.bind(this));
        this.getElement("wrapper").addEventListener("submit", this.submitChanges.bind(this), false);
        this.getElement("select_page").addEventListener("change", this.pageSelected.bind(this), false);

        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));

        return this;
    },

    /**
     * Registrovanje elemenata za Monitor.Main.DOM
     * @return  {Object}                    Monitor.AdminBanners.Change
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminBannersChange", this.elements);
        return this;
    },










    /**
     * Zahtevana je komponenta, pa dohvatamo neophodne podatke
     * @param   {Object}    event           jQuery event objekat
     */
    requestedComponent: function(event) {
        var banner_id = parseInt(event.relatedTarget.dataset.bannerId, 10);
        this.fetchBanner(banner_id);
    },

    pageSelected(event) {
        var element         = event.currentTarget;
        var page_id         = parseInt(element.value, 10);
        var machine_name    = element.options[ element.selectedIndex ].dataset.machine_name;
        this.config.machine_name = machine_name;
        if (page_id === 0) {
            this.getElement("select_position").innerHTML = "";
        } else {
            this.getPagePositions(page_id);
            //this.getFilterData(page_id);
        }
    },
    /**
     * Sacuvana je forma sa novim podacima
     * @param   {Object}    event           JS event objekat
     * @return  {boolean}                   false, kako bi se AJAX-om slali podaci
     */
    submitChanges: function(event) {
        var form       = event.target;
        var elements   = form.elements;

        var banner_id  = parseInt(elements.banner_id.value, 10);
        var name       = elements.name.value;
        var link       = elements.link.value;
        var url        = elements.url.value;
        var position   = elements.position.value;
        event.preventDefault();
        this.updateBanner(banner_id, name, link, url, position);
        this.hideDialog();
        form.reset();
        return false;
    },

    /**
     * Provera da li tag sa datim imenom vec postoji
     * @param  {Object}     event           JS event objekat
     */
    blurName: function(event) {
        var name = event.target.value;
        if (name !== event.target.dataset.original && name.length > 0) {
            this.isnameTaken(name);
        }
    },

    /**
     * Promenjena je slika, cuvamo izmene
     * @param   {Object}    event           JS event objekat
     */
    changedImage: function(event) {
        var elem = event.target;
        var banner_id = parseInt(elem.dataset.bannerId, 10);
        this.changeImage(banner_id, elem.files[0]);
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
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminBannersChange", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminBannersChange", element, query_all, modifier);
    },

    /**
    * Zatvara modal
    * @return  {Object}         Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
    */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");
        return this;
    },

    /**
     * Prikazivanje komponente
     * @param   {Object}    data            Podaci za render; Sadrzi banner kljuc
     */
    render: function(data) {
        var elements    = this.getElement("form").elements;

        elements.banner_id.value   = data.id;
        elements.name.value        = data.title;
        elements.link.value        = data.link;
        elements.url.value         = data.urls;
        elements.page.value        = data.position.page_type.id;
        this.config.position_id    = data.position.id;
        this.getPagePositions(data.position.page_type.id);
    },

    renderPositions: function(positions) {
        var element = this.getElement("select_position");
        var html    = "";

        positions.forEach((position) => {
            if (position.id === this.config.position_id) {
                html += `<option selected value = ${position.id}>${position.position} | ${position.image_width}x${position.image_height}</option>`;
            } else {
                html += `<option value = ${position.id}>${position.position} | ${position.image_width}x${position.image_height}</option>`;
            }
        });

        element.innerHTML = html;
    },

    /**
    * Zadaje validity za name polje, u zavisnosti da li postoji clanak s ovim naslovom
    * @param   {Boolean}   exists   Da li je name vec zauzet
    * @return  {Object}             Monitor.AdminBanners.Create objekat, za ulančavanje funkcija
    */
    setNameValidity: function(exists) {
        var element = this.getElement("name");
        var message = "Banner with this name already exists";
        element.setCustomValidity(exists ? message : "");
        return this;
    },










    /**
     * Dohvatanje informacija o pojedinacnom clanku
     * @param   {Number}    banner_id      ID clanka koji dohvatamo
     * @return  {Object}                    Monitor.AdminBanners.Change
     */
    fetchBanner: function(banner_id) {
        Monitor.Main.Ajax(
            "AdminBanners",
            "fetchBanner",
            {
                banner_id: banner_id,
            },
            this.render.bind(this)
        );

        return this;
    },

    /**
     * Provera da li vec postoji tag s ovim korisnickim imenom
     * @param   {String}    username Ime koje proveravamo
     * @return  {Object}             Monitor.AdminTags.Change objekat, za ulančavanje funkcija
     */
    isNameTaken: function(name) {
        Monitor.Main.Ajax(
            "AdminBanners",
            "isNameTaken",
            {
                name: name,
            },
            this.setNameValidity.bind(this)
        );

        return this;
    },

    /**
     * Menjamo sliku clanka
     * @param   {Number}    banner_id      ID clanka kome menjamo sliku
     * @param   {File}      image           Nova slika
     * @return  {Object}                    Monitor.AdminBanners.Change
     */
    changeImage: function(banner_id, image) {
        Monitor.Main.Ajax(
            "AdminBanners",
            "changeImage",
            {
                banner_id:  banner_id,
                image:      image,
            },
            (data) => {
                var event   = new CustomEvent("Monitor.Admin.Banners");
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
     * @param   {Number}    banner_id      ID clanka koji menjamo
     * @param   {string}    name         Naslov clanka
     * @param   {string}    text            Tekst clanka
     * @param   {string}    excerpt         Isecak
     * @return  {Object}                    Monitor.AdminBanners.Change
     */
    updateBanner: function(banner_id, name, link, url, position) {
        var params = {
            banner_id:  banner_id,
            name:       name,
            link:       link,
            url:        url,
            position:   position,
        };

        Monitor.Main.Ajax(
            "AdminBanners",
            "updateBanner",
            params,

            (data) => {
                var event   = new CustomEvent("Monitor.Admin.Banners");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            }
        );

        return this;
    },

    getPagePositions: function(page_id) {
        Monitor.Main.Ajax(
            "AdminBanners",
            "getPagePositions",
            {
                page_id: page_id,
            },
            this.renderPositions.bind(this)
        );
        return this;
    },

};

document.addEventListener("DOMContentLoaded", Monitor.AdminBanners.Change.init.bind(Monitor.AdminBanners.Change), false);
