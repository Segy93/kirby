"use strict";

if (typeof Monitor === "undefined") var Monitor = {};
if (typeof Kirby.Administrators === "undefined") Kirby.Administrators = {};

Kirby.Administrators.List = {
    config: {
        usernames:  [],
        emails:     [],
    },

    elements: {
        button_change:        ".admin_admins__edit__button_change", // Dugme za promenu imena admina
        button_delete:        ".admin_admins__edit__button_delete",
        button_remove:        "#admin_admins__edit__delete",
        button_reset:         ".admin_admins__edit__button_reset", // Dugme za resetovanje sifre
        button_save:          "#admin_admins__edit__save", // Dugme u modalu da se sacuva promena imena admina
        checkbox_permission:  ".admin_admins__permission",
        dropdown_roles:       ".admin_admins__dropdown__roles", // Padajuce meni sa izborom uloga
        input_change_name:    "#admin_admins__edit__input_change_name", // Polje za promenu imena admina
        input_change_email:   "#admin_admins__edit__input_change_email", // Polje za promenu email-a admina
        form_edit:            "#admin_admins__form_edit",
        label_email:          ".admin_admins__email", // Listanje email-a admina
        label_name:           ".admin_admins__name", // Listanje admina
        label_password:       ".admin_admins__edit__new_password",
        label_role:           ".admin_admins__role",
        modal_edit:           "#admin_admins__modal",
        wrapper:              "#admin_admins__list", // Omotac za tabelu
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
     * @return  {Object}                    Kirby.Administrators.List objekat, za ulančavanje funkcija
     */
    initListeners: function() {
        var $wrapper = $(this.getElementSelector("wrapper"));
        $wrapper.on("change", this.getElementSelector("dropdown_roles"), this.changeRole.bind(this));
        $wrapper.on("click", this.getElementSelector("button_delete"), this.clickDelete.bind(this));
        $wrapper.on("click", this.getElementSelector("button_reset"), this.clickReset.bind(this));
        $wrapper.on("click", this.getElementSelector("button_change"), this.clickChange.bind(this));

        var input_name = this.getElement("input_change_name");
        if(input_name !== null) input_name.addEventListener("blur", this.blurName.bind(this), false);

        var input_email = this.getElement("input_change_email");
        if(input_email !== null) input_email.addEventListener("blur", this.blurEmail.bind(this), false);

        this.getElement("form_edit").onsubmit = this.clickSave.bind(this);
        this.getElement("button_remove").addEventListener("click", this.clickRemove.bind(this), false);

        document.addEventListener("Kirby.Admin.Role.Create", this.createdRole.bind(this));
        document.addEventListener("Kirby.Admin.Administrator.Create", this.createAdmin.bind(this));

        return this;
    },

    /**
     * Inicijalizacija sablona
     * @return  {Object}                    Kirby.Administrators.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_admins__list__tmpl").innerHTML);
        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.Administrators.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdministratorsList", this.elements);
        return this;
    },










    /**
     * Obavestenje da je uloga kreirana
     * @param  {Object} event       JavaScript event objekat
     */
    createdRole: function(event) {
        this.fetchData();
    },

    /**
     * Obavestenje da je admin kreiran
     * @param  {Object} event       JavaScript event objekat
     */
    createAdmin: function(event) {
        this.fetchData(event.data);
    },

    /**
     * Izbor uloge iz padajuceg menija
     * @param  {Object} event       JavaScript event objekat
     */
    changeRole: function(event){
        var elem = event.target;
        var role_id = parseInt(elem.value, 10);
        var admin_id = parseInt(elem.dataset.admin_id, 10);
        this.changeSave(role_id, admin_id);
    },

    clickReset: function(event){
        var elem = event.target;
        var admin_id = parseInt(elem.dataset.admin_id, 10);
        this.resetPassword(admin_id);
    },

    clickDelete: function(event){
        var elem = event.target;
        var admin_id = parseInt(elem.dataset.admin_id, 10);
        var button_remove = this.getElement("button_remove");
        button_remove.dataset.admin_id = admin_id;
    },

    clickRemove: function(event){
        var button_remove = this.getElement("button_remove");
        var admin_id = parseInt(button_remove.dataset.admin_id, 10);
        this.deleteAdmin(admin_id);
    },

    /**
     * Promena imena admina u modalu koji se otvori kada se klikne na dugme za izmenu imena
     * @param  {Object} event       JavaScript event objekat
     */
    clickChange: function(event) {
        var admin_id    = parseInt(event.target.dataset.admin_id, 10);
        var admin_name  = this.getElement("label_name", false, admin_id).textContent.trim();
        var admin_email = this.getElement("label_email", false, admin_id).textContent.trim();

        var input_change_name               = this.getElement("input_change_name");
        var input_change_email              = this.getElement("input_change_email");
        input_change_name.dataset.initial   = admin_name;
        input_change_email.dataset.initial  = admin_email;
        input_change_name.value             = admin_name;
        input_change_email.value            = admin_email;
        input_change_name.dataset.admin_id  = admin_id;
    },

    clickSave: function(event) {
        var username            = this.getElement("input_change_name").value;
        var email               = this.getElement("input_change_email").value;
        var admin_id            = parseInt(this.getElement("input_change_name").dataset.admin_id, 10);

        this.updateAdmin(admin_id, username, email);
        $(this.getElement("modal_edit")).modal("hide");
        event.target.reset();
        return false;
    },

    /**
     * Provera da li admin sa datim imenom vec postoji kada admin pokusa da kreira novog admina
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        this.validateName();
    },

    /**
     * Provera da li admin sa datim email-om vec postoji kada admin pokusa da kreira novog admina
     * @param  {Object}     event           JavaScript event objekat
     */
    blurEmail: function(event) {
        this.validateEmail();
    },

    /**
     * Cuvanje informacija o postojecim adminima. Koristi se kako bi se sprecilo kreiranje novih sa imenom koje postji.
     * @param  {Object}     data            Podaci o postojecim adminima.
     */
    storeData: function(data){
        this.config.usernames   = data.administrators.map(function(value) { return value.username });
        this.config.emails      = data.administrators.map(function(value) { return value.email });
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
        return Kirby.Main.Dom.getElement("AdministratorsList", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdministratorsList", element, query_all, modifier);
    },

    validateName: function(){
        var elem = this.getElement("input_change_name");
        var error = elem.dataset.initial !== elem.value && this.config.usernames.indexOf(elem.value) !== -1 ? "Admin with this username already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },

    validateEmail: function(){
        var elem = this.getElement("input_change_email");
        var error = elem.dataset.initial !== elem.value && this.config.emails.indexOf(elem.value) !== -1 ? "Admin with this email already exists" : "";
        elem.setCustomValidity(error);
        return this;
    },


    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object}     data            Prosledjeni podaci
     */
    render: function(data, ctx) {
        var show_password = ctx === undefined ? {
            id: 0,
        } : {
            id: ctx.admin_id,
            password: ctx.password
        };
        this.getElement("wrapper").innerHTML = this.templates.main({
            roles : data.roles,
            admins: data.administrators,
            show_password: show_password,
        });

        return this;
   },









    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Kirby.Administratos.List objekat, za ulancavanje funkcija
     */
    fetchData: function() {
    Kirby.Main.Ajax(
            "Administrators",
            "fetchData",
            {},
            function(data) {
                this
                    .storeData(data)
                    .validateName()
                    .validateEmail()
                    .render(data)
                ;
            }.bind(this)
        );
        return this;
    },

    /**
     * Cuvanje promene uloge datom adminu
     * @param  {Number} role_id  ID uloge koju menjamo
     * @param  {Number} admin_id ID admina kome menjamo ulogu
     * @return {Object}                     Kirby.Administratos.List objekat, za ulancavanje funkcija
     */
    changeSave: function(role_id, admin_id) {
        Kirby.Main.Ajax(
            "Administrators",
            "setRole",
            {
                role_id : role_id,
                admin_id: admin_id
            },
            function(){},
            {}
        );
    },

    deleteAdmin: function(admin_id) {
        Kirby.Main.Ajax(
            "Administrators",
            "deleteAdmin",
            {
                admin_id: admin_id,
            },
            function(data) {
                var event_name = data === true ? "Admin.Delete" : "Error";
                var event = new CustomEvent("Kirby." + event_name);

                if (data === true) {
                    this.fetchData();
                    event.data = admin_id;
                } else {
                    event.error_type = "admins";
                    event.error_code = parseInt(data, 10);
                }
                document.dispatchEvent(event);
            }.bind(this)
        );
    },

    resetPassword: function(admin_id) {
        Kirby.Main.Ajax(
            "Administrators",
            "resetPassword",
            {
                admin_id: admin_id,
            },
            function(data, admin_id) {
                this.getElement("button_reset", false, admin_id).style.display = "none";
                var label = this.getElement("label_password", false, admin_id);
                label.textContent = data;
            }.bind(this),
            admin_id
        );
    },

    updateAdmin: function(id, name, email) {
        Kirby.Main.Ajax(
            "Administrators",
            "updateAdmin",
            {
                admin_id    : id,
                admin_name  : name,
                admin_email : email,
            },
            function(data){
                var event_name = data === true ? "Admin.Update" : "Error";
                var event = new CustomEvent("Kirby." + event_name);

                if (data === true) {
                    this.fetchData();
                    event.data = id;
                } else {
                    event.error_type = "admins";
                    event.error_code = parseInt(data, 10);
                }
                document.dispatchEvent(event);
            }.bind(this),
            {}
        );
    },

};

document.addEventListener('DOMContentLoaded', Kirby.Administrators.List.init.bind(Kirby.Administrators.List), false);
