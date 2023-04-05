"use strict";

if(typeof Monitor           === "undefined") var  Monitor      = {};
if(typeof Monitor.AdminTags === "undefined") Monitor.AdminTags = {};


Monitor.AdminTags.List = {

    config: {

    },

    elements: { // Elementi u komponenti
        "wrapper":        "#admin_tags__list_content", // Element u koji se iscrtava sablon
        "tag_change":     ".admin_tags__list_change", // Taster za promenu informacija
        "tag_delete":     ".admin_tags__list_delete", //Taster za brisanje taga
    },

    templates: { // Sabloni koji ce biti korisceni u komponenti
        main: function(){},
    },









    init:function(event){
        this
            .registerElements()
            .initTemplates()
            .initListeners()
            .fetchData()
            ;
    },

    initListeners:function(){
        document.addEventListener("Monitor.Admin.Tags", this.changeOccured.bind(this), false);
        document.addEventListener("Monitor.Admin.SEO.Create", this.fetchData.bind(this), false);
        return this;
    },

     initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_tags__list_tmpl").innerHTML);
        return this;
    },

    registerElements:function(){
        Monitor.Main.DOM.register("AdminTagsList", this.elements);
        return this;
    },









    changeOccured:function(event){
        var refresh_on = ["Update"];
        var fetch_on = ["Create", "Delete"];
        if (event.info !== "Create") {
            this.fetchData();
        }

    },

    triggerChangeOccured: function(data, info) {
        var event = new CustomEvent("Monitor.Admin.Tags");
        event.info = "Update";
        event.data = data;
        document.dispatchEvent(event);
    },




    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminTagsList", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminTagsList", element, query_all, modifier);
    },

    /**
    * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
    * @param  {Object}     data            Prosledjeni podaci
    */
    render: function(data) {
        this.getElement("wrapper").innerHTML = this.templates.main({
            "tags":    data.tags,
        });

        return this;
    },








    /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Monitor.AdminTags.List objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminTags",
            "fetchData",
            {},
            this.render.bind(this)

        );
    },
};
document.addEventListener('DOMContentLoaded', Monitor.AdminTags.List.init.bind(Monitor.AdminTags.List), false);
