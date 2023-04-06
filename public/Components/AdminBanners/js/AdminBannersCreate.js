"use strict";

if (typeof Monitor               === "undefined")   var Monitor           = {};
if (typeof Kirby.AdminBanners === "undefined")    Kirby.AdminBanners   = {};

Kirby.AdminBanners.Create = {
    config: {               // Konfiguracija kompon
        //machine_name: '',
    },

    elements: { // Selektori elemenata koje komponenta koristi
        form:               "#admin_banners__create_form",
        name:               "#admin_banners__create_name",
        reset:              "#admin_banners__create__reset",
        select_page:        "#admin_banners__create_page",
        select_position:    "#admin_banners__create_position",
        location:           ".admin_banners__location",
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
     * @return  {Object}                    Kirby.AdminBanners.Create
     */
    initListeners: function() {
        var form    = this.getElement("form");
        if (form !== null) form.onsubmit = this.submitForm.bind(this);

        this.getElement("name").addEventListener("blur", this.nameChanged.bind(this),     false);
        this.getElement("reset").addEventListener("click", this.resetForm.bind(this), false);
        this.getElement("select_page").addEventListener("change", this.pageSelected.bind(this), false);
        //this.getElement("location").addEventListener("change", this.categoryChanged.bind(this), false);
        // document.addEventListener("Kirby.SEO.Form", this.changedSEOState.bind(this), false);

        return this;
    },

    /**
     * Registrovanje elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminBanners.Create
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminBannersCreate", this.elements);
        return this;
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
    * Poziva se funkcija za poroveru zauzetosti imena
    */
    nameChanged: function() {
        var name = this.getElement("name").value;

        this.isNameTaken(name);
    },

    categoryChanged: function(event) {
        var machine_name = this.config.machine_name;
        var category_id  = $('#admin_banners__selectpicker').val();
        console.log(category_id);
        if(category_id !== "0") {
            this.getCategoryFilters(machine_name, category_id);
        }
    },

    /**
     * Novi clanak je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom clanku
     */
    createdBanner: function(data) {
        var event = new CustomEvent("Kirby.Admin.Banners");
        event.info = "Create";
        event.data = data;
        document.dispatchEvent(event);

        this.resetForm();

        $(".alert").show().fadeTo(0, 500);
        window.setTimeout(() => {
            $(".alert-success").fadeTo(500, 0).slideUp(500);
        }, 5000);
    },

    /**
     * Poslata je forma za kreiranje clanaka
     * @param   {Object}    event           JS event objekat
     */
    submitForm: function(event) {
        var form  = event.currentTarget;
        var elements = form.elements;

        var name        = elements.name;
        var position_id = parseInt(elements.position.value, 10);
        var link        = elements.link.value;
        var url         = elements.url.value;
        var image       = elements.image.files[0];
        event.preventDefault();
        if (name.checkValidity()) {
            this.createBanner(
                position_id,
                name.value,
                link,
                url,
                image,
            );
        }


        return false;
    },

    /**
     * Promenjeno je stanje SEO forme
     * @param   {Object}    event           JS event objekat
     */
    changedSEOState: function(event) {
        this.getElement("submit").setCustomValidity(event.valid ? "" : event.message);
    },

    /**
    * Dohvata se element da osnovu lokalnog imena
    * @param  {String} element    Lokalno ime elementa definisano u vrhu fajla
    * @param  {Boolean} querry_all da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param  {[type]} modifier   BEM Modifier za selektor
    */
    getElement: function(element, querry_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminBannersCreate", element, querry_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminBannersCreate", element, query_all, modifier);
    },

    /**
     * Resetovanje forme za unos
     * @return {[type]} [description]
     */
    resetForm: function() {
        return this.getElement("form").reset();
    },

    /**
     * Zadaje validity za heading polje, u zavisnosti da li postoji clanak s ovim naslovom
     * @param   {Boolean}   exists          Da li je heading vec zauzet
     * @return  {Object}              Kirby.AdminBanners.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("name").setCustomValidity(exists ? "Banner with this name already exists" : "");
        return this;
    },

    renderPositions: function(positions) {
        var element = this.getElement("select_position");
        var html    = "";

        positions.forEach((position) => {
            html += `<option value = ${position.id}>${position.position} | ${position.image_width}x${position.image_height}</option>`;
        });

        element.innerHTML = html;
    },

    // renderFilterData: function(data) {
    //     var element = `
        
    //     <label for="admin_banners__selectpicker">Kategorija</label>
    //     <select
    //         id                      = "admin_banners__selectpicker"
    //         class                   = "form-control selectpicker"
    //         data-header             = "Odaberite kategoriju prikaza"
    //         data-size               = "3"
    //         data-show-subtext       = "true"
    //         data-live-search        = "true"
    //         data-noneSelectedText   = "Nije izabrana kategorija"
    //         >
    //         <option value = "0">Nije izabrana kategorija</option>   
    //     `;
    //     for (var i = 0, l = data.length; i < l; i++) {
    //         element += `<option data-subtext="${data[i].name}" value = ${data[i].id} > ${data[i].name} </option>`;
    //     }
    //     element += `</select>`;

    //     var location = this.getElement('location');
    //     location.innerHTML = element;

    //     var select = $('#admin_banners__selectpicker');
    //     select.selectpicker();
    // },

    // renderCategoryFilterData: function(data) {
    //     var filters = data;

    //     for (var i = 0, l = filters.length; i < l; i++) {

    //     }
    // },









    /**
     * Kreiranje clanka
     * @param   {string}    heading         Naslov
     * @param   {File}      image           Slika
     * @param   {Number}    category        ID kategorije
     * @param   {string}    date            Datum
     * @param   {string}    text            Tekst
     * @param   {Array}     tags            Niz ID-jeva tagova
     * @param   {string}    excerpt         Isecak
     * @return  {Object}                    Kirby.AdminBanners.Create
     */
    createBanner: function(position_id, name, link, url, image) {
        Kirby.Main.Ajax(
            "AdminBanners",
            "createBanner",
            {
                position_id:    position_id,
                name:           name,
                link:           link,
                url:            url,
                image:          image,

            },
            this.createdBanner.bind(this),
            {},
            true
        );

        return this;
    },

    /**
    * Provera da li vec postoji clanak s ovim naslovom
    * @param   {String}    heading     Naziv banera koje proveravamo
    * @return  {Object}                Kirby.AdminBanners.Create objekat, za ulančavanje funkcija
    */
    isNameTaken: function(name) {
        Kirby.Main.Ajax(
            "AdminBanners",
            "isNameTaken",
            {
                name: name,
            },
            this.setNameValidity.bind(this)
        );
        return this;
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
    },

    // getFilterData: function(page_id) {
    //     Kirby.Main.Ajax(
    //         "AdminBanners",
    //         "getFilterData",
    //         {
    //             page_id: page_id,
    //         },
    //         this.renderFilterData.bind(this)
    //     );
    //     return this;
    // },

    // getCategoryFilters: function(machine_name, category_id) {
    //     Kirby.Main.Ajax(
    //         "AdminBanners",
    //         "getCategoryFilters",
    //         {
    //             machine_name: machine_name,
    //             category_id:  category_id,
    //         },
    //         this.renderCategoryFilterData.bind(this)
    //     );
    //     return this;
    // }

};

document.addEventListener("DOMContentLoaded", Kirby.AdminBanners.Create.init.bind(Kirby.AdminBanners.Create), false);
