"use strict";

if (typeof Monitor           === "undefined") var  Monitor      = {};
if (typeof Monitor.AdminStaticPages === "undefined") Monitor.AdminStaticPages = {};


Monitor.AdminStaticPages.Change = {

    config: {

        "editor_text": {},
    },

    elements: { // Elementi u komponenti
        form:         "#admin_pages__static_change__form",
        input_name:   "#admin_pages__static_change__input_name",
        text:         "#admin_pages__static_change__input_text",
        wrapper:      "#admin_pages__static_modal__change",
    },








    init: function(event){
        this
            .registerElements()
            .initListeners()
            .initEditor("text")
            ;
    },

    initListeners: function(){
        $(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        this.getElement("input_name").addEventListener("blur", this.blurName.bind(this), false);
       return this;
    },

    registerElements: function(){
        Monitor.Main.DOM.register("AdminStaticPagesChange", this.elements);
        return this;
    },

    

    /* Inicijalizacija tinyMCE editora
    * @param   {string}    element         Na kom elementu (iz this.elements) ce biti editor
    * @return  {Object}                    Monitor.AdminArticles.Change
    */
   initEditor: function(element) {
       var selector = this.getElementSelector(element);
       var callback = this.setConfig.bind(this);
       Monitor.Main.Editor.initEditor(element, selector, callback);
       return this;
   },

   /**
    * Callback funkcija za setovanje editora u config promenljive
    * @param {Object} editor  Objekat inicializovanog editora
    * @param {string} element Naziv elementa
    */
   setConfig: function (editor, element) {
       this.config["editor_" + element] = editor;
   },







     /**
     * Poslata je forma za promenu imena
     * @param   {Object}    event           JS event objekat
     */
    submitChanges: function(event) {
        var form = event.target;
        var elements = form.elements;

        var page_id = parseInt(elements.page_id.value, 10);
        var name        = elements.name.value;
        var category_id = elements.category.value;
        var original    = elements.name.dataset.original;
        var text        = this.config.editor_text.getContent();
        if (name !== original) {

        this
            .updatePage(page_id, name, category_id, text)
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
        var page_id = parseInt(event.relatedTarget.dataset.pageId, 10);
        var elements = this.getElement("form").elements;
        elements.page_id.value = page_id;

        this.fetchPage(page_id);
//         elements.name.value = event.relatedTarget.textContent.trim();
    },

    /**
     * Provera da li kategorija sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        var name = event.target.value;
        if (name !== event.target.dataset.original && name.length > 0) this.isPageNameTaken(name);
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
     * @return  {Object}                    Monitor.AdminStaticPages.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("input_name").setCustomValidity(exists ? "Page with this name already exists" : "");
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
        return Monitor.Main.DOM.getElement("AdminStaticPagesChange", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminStaticPagesChange", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object} data        podaci o kategoriji
     * @return {Object}      Monitor.AdminStaticPages.Change objekat za ulancavanje funkcija
     */
    render: function(data){
        var elements = this.getElement("form").elements;

        elements.page_id.value = data.id;
        elements.name.value   = data.title;
        elements.name.category   = data.category_id;

        this.config.editor_text.setContent(data.text);
        elements.name.dataset.original = data.name;

        return this;
    },











    /**
    * Provera da li vec postoji kategorija s ovim korisnickim imenom
    * @param   {String}    username        Ime koje proveravamo
    * @return  {Object}                    Monitor.AdminStaticPages.Change objekat, za ulančavanje funkcija
    */
    isPageNameTaken: function(name) {
        Monitor.Main.Ajax(
            "AdminStaticPages",
            "isPageNameTaken",
            {
                "name": name,
            },
            this.setNameValidity.bind(this)
        );
        return this;
    },

    /**
    * Menja trenutno ime u podeseno
    * @param  {Number} page_id ID kategorije kojem se menja ime
    * @param  {String} name   ime kojim se menja
    * @return {Object}        Monitor.AdminStaticPages.Change objekat za ulancavanje funkcija
    */
    updatePage: function(page_id, name, category_id, text){
        Monitor.Main.Ajax(
            "AdminStaticPages",
            "updatePage",
            {
                "page_id":      page_id,
                "name":         name,
                "category_id":  category_id,
                "text":         text,
            },
            function(data) {
            var event = new CustomEvent("Monitor.Admin.StaticPages");
            event.info = "Update";
            event.data = data;
            document.dispatchEvent(event);
        },
          );
        return this;
    },

    /**
    * Dohvata kategoriju zarad dobijanja parametara
    * @param  {Number} page_id ID kategorije kojem se menja ime
    * @return {Object}        Monitor.AdminStaticPages.Change objekat za ulancavanje funkcija
    */
    fetchPage: function(page_id){
        Monitor.Main.Ajax(
            "AdminStaticPages",
            "fetchPage",
            {
                "page_id": page_id,
            },
            this.render.bind(this)
            );
        }

};
document.addEventListener('DOMContentLoaded', Monitor.AdminStaticPages.Change.init.bind(Monitor.AdminStaticPages.Change), false);
