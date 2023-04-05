"use strict"

if (typeof Monitor === "undefined") var Monitor = {};
if (typeof Monitor.AdminTags === "undefined") Monitor.AdminTags = {};

Monitor.AdminTags.Create = {










    /**
     *
     * Konfiguracija komponente
     *
     */
    config: { // konfiguracioni parametri komponente
        names: [],
        seo_valid: false,
        seo_message: "",
    },

    elements:{  /*Kofiguracija elemenata koji ce biti korisceni*/
        "form_input":       "#admin_tags__create__input",
        "form_create":      "#admin_tags__create__form",
        "submit":           "#admin_tags__create__submit",
    },











    /*
    * Inicializacija komponente
    * @param {Object} event         Javascript event objekat
    */
    init: function(event) {
        this
            .registerElements()
            .initListeners()
        ;
    },












    initListeners: function() {
        this.getElement("form_create").onsubmit = this.submittedForm.bind(this);
        this.getElement("form_input").addEventListener("blur", this.blurName.bind(this), false);
        this.getElement("submit").addEventListener("click", this.clickSubmit.bind(this), false);
        document.addEventListener("Monitor.SEO.Form", this.changedSEOState.bind(this), false);
        document.addEventListener("Monitor.Admin.SEO.Create", this.fetchData.bind(this), false);
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminTags.Create objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminTagsCreate", this.elements);
        return this;
    },












    changedSEOState: function(event) {
        this.config.seo_valid = event.valid;
        this.config.seo_message = event.message;
    },

    clickSubmit: function(event) {
        event.target.setCustomValidity(this.config.seo_valid ? "" : this.config.seo_message);
    },

    /**
     * Funkcija za proveru postojanja imena medju tagovima
     * @param (Object) event     Javascript event objekat
     */
    blurName: function(event){
        this.validateName();
    },

    /**
     * Podaci su uneti i salje se ajax zahtev
     * @param   (Object)    event           Javascript objekat
     * @return  {boolean}                   jer ne zelimo da se posalje klasicnim putem
     */
    submittedForm: function(event){
        var name = this.getElement("form_input").value;

        this.createTag(name);

        event.target.reset();
        return false;
    },

    /**
     * Novi tag je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom predmetu
     */
    tagCreated: function(data) {
        var event  = new CustomEvent("Monitor.Admin.Tags");
        event.info = "Create";
        event.data = data;
        document.dispatchEvent(event);
    },

    /**
     * Cuvanje informacija o postojecim tagovima. Koristi se kako bi se sprecilo kreiranje novih sa imenom koje postji.
     * @param  {Object}     data            Podaci o postojecim tagovima.
     */
    storeData: function(data){
        this.config.names   = data.tags.map(function(value) { return value.name });
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
        return Monitor.Main.DOM.getElement("AdminTagsCreate", element, query_all, modifier);
    },

    validateName: function(event) {
        var elem = this.getElement('form_input');
        var error = this.config.names.indexOf(elem.value) !== -1 ? "Tag with this name already exists" :"";
        elem.setCustomValidity(error);
        return this;
    },










    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti,nakon cega ih cuva
     * @return {Object}                     Monitor.AdminTags.Create objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminTags",
            "fetchData",
            {},
            function(data) {
                this
                    .storeData(data)
                    .validateName()
                ;
            }.bind(this)
        );
    },

    /**
     * Kreiranje nove znacke
     * @param   {String}    name            Ime taga
     * @return {Object}                     Monitor.AdminTags.Create objekat, za ulancavanje funkcija
    **/
    createTag: function(name, image) {
        Monitor.Main.Ajax(
            "AdminTags",
            "createTag",
            {
                "name":     name,
            },
            this.tagCreated.bind(this)
        );

    }
};

document.addEventListener('DOMContentLoaded', Monitor.AdminTags.Create.init.bind(Monitor.AdminTags.Create), false);
