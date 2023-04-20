"use strict";

if (typeof Kirby === "undefined") var Kirby = {};
if (typeof Kirby.AdminRoles === "undefined") Kirby.AdminRoles = {};

Kirby.AdminRoles.List = {
    /**
     *
     * Konfiguracija komponente
     *
     */










    config: { // konfiguracioni parametri komponente
        name: [],
    },

    elements: { // Selektori za elemente koji ce biti korišćeni u komponenti
        "button_change":        ".admin_roles__edit__button_change", // Dugme za promenu imena uloge
        "button_delete":        ".admin_roles__edit__button_delete",
        "button_remove":        "#admin_roles__edit__delete",
        "button_save":          "#admin_roles__edit__save", // Dugme u modalu da se sacuva promena imena uloge
        "checkbox_permission":  ".admin_roles__permission", // Checkbox za dodeljivanje dozvola ulogama
        "input_change":         "#admin_roles__edit__input_change", // Polje za promenu imena uloge
        "label_name":           ".admin_roles__description", // Listanje uloga
        "wrapper":              "#admin_roles__list", // Omotac za tabelu
        "modal_edit":           "#admin_roles__modal",
        "form_edit":            "#admin_roles__form_edit",
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function(){},
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
     * @return  {Object}                    Kirby.AdminRoles.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $wrapper.on("click", this.getElementSelector("button_delete"), this.clickRemove.bind(this));
        $wrapper.on("click", this.getElementSelector("button_change"), this.clickChange.bind(this));
        $wrapper.on("click", this.getElementSelector("checkbox_permission"), this.clickToggle.bind(this));

        var input_name = this.getElement("input_change");
        if(input_name !== null) input_name.addEventListener("blur", this.blurName.bind(this), false);

        this.getElement("form_edit").onsubmit = this.clickSave.bind(this);
        this.getElement("button_remove").addEventListener("click", this.clickDelete.bind(this), false);

        document.addEventListener("Kirby.Admin.Role.Create", this.createdRole.bind(this));

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}                    Kirby.AdminRoles.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_roles__list__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminRoles.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminRolesList", this.elements);
        return this;
    },










    clickRemove: function(event) {
        var role_id = parseInt(event.target.dataset.role_id, 10);
        var button_remove = this.getElement("button_remove");
        button_remove.dataset.role_id = role_id;
    },

    clickDelete: function(event) {
        var button_remove = this.getElement("button_remove");
        var role_id = parseInt(button_remove.dataset.role_id, 10);
        this.deleteRole(role_id);
    },

    /**
     * Obavestenje da je uloga kreirana
     * @param  {Object} event       JavaScript event objekat
     */
    createdRole: function(event) {
        this.fetchData();
    },

    /**
     * Stikliranje dozvola i dodeljivanje odredjenoj ulozi
     * @param  {Object} event       JavaScript event objekat
     */
    clickToggle: function(event) {
        var elem = event.target;
        var data = elem.dataset;

        var role_id = parseInt(data.role_id, 10);
        var permission_id = parseInt(data.permission_id, 10);
        var state = elem.checked;

        this.toggleRolePermission(role_id, permission_id, state);
    },

    /**
     * Promena imena uloge u modalu koji se otvori kada se klikne na dugme za izmenu imena
     * @param  {Object} event       JavaScript event objekat
     */
    clickChange: function(event) {
        var role_id             = parseInt(event.target.dataset.role_id, 10);
        var role_description    = this.getElement("label_name", false, role_id).textContent.trim();

        var input_change                = this.getElement("input_change");
        input_change.dataset.initial    = role_description;
        input_change.value              = role_description;
        input_change.dataset.role_id    = role_id;
    },

    /**
     * Cuvanje promene imena uloge
     * @param  {Object} event       JavaScript event objekat
     */
    clickSave: function(event) {
        var input_change        = this.getElement("input_change");
        var role_id             = parseInt(input_change.dataset.role_id, 10);
        var role_description    = input_change.value;

        this.changeText(role_id, role_description);
        $(this.getElement("modal_edit")).modal("hide");
        event.target.reset();
        return false;
    },

    /**
     * Provera da li uloga sa datim imenom vec postoji kada admin pokusa da kreira novu ulogu
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        this.validateName();
    },

    /**
     * Cuvanje informacija o postojecim ulogama. Koristi se kako bi se sprecilo kreiranje novih sa imenom koje postji.
     * @param  {Object}     data            Podaci o postojecim predmetima.
     */
    storeData: function(data){
        this.config.names   = data.roles.map(function(value) { return value.description });
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
        return Kirby.Main.Dom.getElement("AdminRolesList", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminRolesList", element, query_all, modifier);
    },

    validateName: function() {
        var elem = this.getElement("input_change");
        var error = elem.dataset.initial !== elem.value && this.config.names.indexOf(elem.value) !== -1 ? "Role with this description already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     data            Prosledjeni podaci
     */
    render: function(data) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            "roles": data.roles,
            "permissions": data.permissions,
        });
        return this;
    },








    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Kirby.AdminRoles.List objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Kirby.Main.Ajax(
            "AdminRoles",
            "fetchData",
            {
                "a": 2
            },
            function(data) {
               this
                    .storeData(data)
                    .validateName()
                    .render(data)
                ;
            }.bind(this)
        );
    },

    /**
     * Cekiranje dozvola u okviru uloga
     * @param  {Number}     role_id         ID uloge
     * @param  {Number}     permission_id   ID dozvole
     * @param  {Boolean}    state           Stanje, da li je cekirano ili ne
     * @return {Object}                     Kirby.AdminRoles.List objekat, za ulancavanje funkcija
     */
    toggleRolePermission: function(role_id, permission_id, state) {
        Kirby.Main.Ajax(
            "AdminRoles",
            "toggleRolePermission",
            {
                "role_id": role_id,
                "permission_id": permission_id,
                "state": state,
            }
        );
    },

    deleteRole: function(role_id){
        Kirby.Main.Ajax(
            "AdminRoles",
            "deleteRole",
            {
                "role_id": role_id
            },
            function(data) {
                if (data === true) {
                    var event = new CustomEvent("Kirby.Role.Delete");
                    event.data = role_id;
                    this.fetchData();
                } else {
                    var event = new CustomEvent("Kirby.Error");
                    event.error_type = "roles";
                    event.error_code = parseInt(data, 10);
                }
                document.dispatchEvent(event);
            }.bind(this)
        );
    },

    /**
     * Promena imena uloga
     * @param  {Number}     role_id         ID uloge
     * @param  {String}     descrption      Opis uloge
     * @return {Object}                     Kirby.AdminRoles.List objekat, za ulancavanje funkcija
     */
    changeText: function(role_id, description) {
        Kirby.Main.Ajax(
            "AdminRoles",
            "changeText",
            {
                "role_id": role_id,
                "description": description,
            },
            function(data) {
                var event_name = data === true ? "Role.Delete" : "Error";
                var event = new CustomEvent("Kirby." + event_name);

                if (data === true) {
                    this.fetchData();
                    event.data = role_id;
                } else {
                    event.error_type = "roles";
                    event.error_code = parseInt(data, 10);
                }
                document.dispatchEvent(event);
            }.bind(this)
        );
    }
};

document.addEventListener('DOMContentLoaded', Kirby.AdminRoles.List.init.bind(Kirby.AdminRoles.List), false);
