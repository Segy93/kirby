"use strict";

if (typeof Monitor           === "undefined") var  Monitor      = {};
if (typeof Monitor.AdminStaticCategories === "undefined") Monitor.AdminStaticCategories = {};


Monitor.AdminStaticCategories.Change = {

    config: {

    },

    elements: { // Elementi u komponenti
        form:         "#admin_categories__static_change__form",
        input_name:   "#admin_categories__static_change__input",
        wrapper:      "#admin_categories__static_modal__change",
    },








    init: function(event){
        this
            .registerElements()
            .initListeners()
            ;
    },

    initListeners: function(){
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        this.getElement("input_name").addEventListener("blur", this.blurName.bind(this), false);
       return this;
    },

    registerElements: function(){
        Monitor.Main.DOM.register("AdminStaticCategoriesChange", this.elements);
        return this;
    },








     /**
     * Poslata je forma za promenu imena
     * @param   {Object}    event           JS event objekat
     */
    submitChanges: function(event) {
        var form = event.target;
        var elements = form.elements;

        var category_id = parseInt(elements.category_id.value, 10);
        var name = elements.name.value;
        var original = elements.name.dataset.original;
        if (name !== original) {

        this
            .updateName(category_id, name)
            .hideDialog()
            ;
        }else{
            this.hideDialog();
        }
            form.reset();
        return false;
    },

     /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var category_id = parseInt(event.relatedTarget.dataset.categoryId, 10);
        var elements = this.getElement("form").elements;
        elements.category_id.value = category_id;

        this.fetchCategory(category_id);
//         elements.name.value = event.relatedTarget.textContent.trim();
    },

    /**
     * Provera da li kategorija sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        var name = event.target.value;
        if (name !== event.target.dataset.original && name.length > 0) this.isCategoryNameTaken(name);
    },











    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");
        return this;
    },









    /**
     * Zadaje validity za name polje, u zavisnosti da li postoji kategorija s ovim imenom
     * @param   {Boolean}   exists          Da li je ime kategorije  zauzeto
     * @return  {Object}                    Monitor.AdminStaticCategories.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("input_name").setCustomValidity(exists ? "Category with this name already exists" : "");
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
        return Monitor.Main.DOM.getElement("AdminStaticCategoriesChange", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminStaticCategoriesChange", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object} data        podaci o kategoriji
     * @return {Object}      Monitor.AdminStaticCategories.Change objekat za ulancavanje funkcija
     */
    render: function(data){
        var elements = this.getElement("form").elements;

        elements.category_id.value = data.id;
        elements.name.value   = data.name;
        elements.name.dataset.original = data.name;

        return this;
    },











    /**
    * Provera da li vec postoji kategorija s ovim korisnickim imenom
    * @param   {String}    username        Ime koje proveravamo
    * @return  {Object}                    Monitor.AdminStaticCategories.Change objekat, za ulančavanje funkcija
    */
    isCategoryNameTaken: function(name) {
        Monitor.Main.Ajax(
            "AdminStaticCategories",
            "isCategoryNameTaken",
            {
                "name": name,
            },
            this.setNameValidity.bind(this)
        );
        return this;
    },

    /**
    * Menja trenutno ime u podeseno
    * @param  {Number} category_id ID kategorije kojem se menja ime
    * @param  {String} name   ime kojim se menja
    * @return {Object}        Monitor.AdminStaticCategories.Change objekat za ulancavanje funkcija
    */
    updateName: function(category_id, name){
        Monitor.Main.Ajax(
            "AdminStaticCategories",
            "updateName",
            {
                "category_id": category_id,
                "name": name,
            },
            function(data) {

                var event = new CustomEvent("Monitor.Admin.StaticCategories");
                event.info = "Update";
                event.data = data;
                document.dispatchEvent(event);
            }
          );
        return this;
    },

    /**
    * Dohvata kategoriju zarad dobijanja parametara
    * @param  {Number} category_id ID kategorije kojem se menja ime
    * @return {Object}        Monitor.AdminStaticCategories.Change objekat za ulancavanje funkcija
    */
    fetchCategory: function(category_id){
        Monitor.Main.Ajax(
            "AdminStaticCategories",
            "fetchCategory",
            {
                "category_id": category_id,
            },
            this.render.bind(this)
            );
        }

};
document.addEventListener('DOMContentLoaded', Monitor.AdminStaticCategories.Change.init.bind(Monitor.AdminStaticCategories.Change), false);
