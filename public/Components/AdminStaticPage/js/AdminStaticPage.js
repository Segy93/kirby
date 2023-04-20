"use strict";

if( typeof Kirby                 === "undefined") var Kirby             = {};
if( typeof Kirby.AdminStaticPages === "undefined") Kirby.AdminStaticPages = {};

Kirby.AdminStaticPages.Create = {



	/*
	*Konfiguracija komponente
	*/





	config:{
        seo_valid: false,
        editor_text: {},    // Editor teksta
	},

	elements:{
		"form": 		"#admin_pages__static_create__form",
		"name": 		"#admin_pages__static_input__name",
		"text": 		"#admin_pages__static_input__text",
		"category": 	"#admin_pages__static_input__category",
        "submit":       "#admin_pages__static_button__submit",
	},


	/**
	 * Inicijalizacija komponente
	 * @param  {Object} event event objekat javascripta
	 */
	init:function(event){
		this
			.registerElements()
			.initListeners()
            .initEditor("text")
	},

	/**
	 * Inicializacija osluskivaca u sklopu komponente kao i funkcija koje reaguju na njih
	 * @return {Object}  Kirby.AdminStaticPages.Create objekat za ulancavanje funkcija
	 */
	initListeners:function(){
		var form = this.getElement("form");
		if(form !== null) form.onsubmit = this.formSubmited.bind(this);

		var name = this.getElement("name");
		if(name !== null)name.addEventListener("blur", this.blurName.bind(this), false);
		this.getElement("submit").addEventListener("click", this.clickSubmit.bind(this), false);
		document.addEventListener("Kirby.SEO.Form", this.changedSEOState.bind(this), false);
		document.addEventListener("Kirby.Admin.SEO.Create", this.redirectToList.bind(this), false);

		return this;
	},

	/**
	 * Registracija elemenata u uptrebi od strane komponente
	 * @return {Object} Kirby.AdminStaticPages.Create objekat za ulancavanje funkcija
	 */
	registerElements:function(){
		Kirby.Main.Dom.register("AdminStaticPagesCreate", this.elements);
		return this;
	},
	 /**
     * Novi tag je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom predmetu
     */
    pageCreated: function(data) {
        var event = new CustomEvent("Kirby.Admin.StaticPages");
        event.info = "Create";
        event.data = data;
        document.dispatchEvent(event);
	},

    /**
     * Callback funkcija za setovanje editora u config promenljive
     * @param {Object} editor  Objekat inicializovanog editora
     * @param {string} element Naziv elementa
     */
    setConfig: function (editor, element) {
        this.config["editor_" + element] = editor;
        this.config["editor_" + element] .getBody().setAttribute('contenteditable', true);
	},

    /**
     * Inicijalizacija tinyMCE editora
     * @param   {string}    element         Na kom elementu (iz this.elements) ce biti editor
     * @return  {Object}                    Kirby.AdminArticles.Create
     */
	initEditor: function(element) {
        var selector = this.getElementSelector(element);
        var callback = this.setConfig.bind(this);
        Kirby.Main.Editor.initEditor(element, selector, callback);
        return this;
    },








    changedSEOState: function(event) {
        this.config.seo_valid = event.valid;
    },

    clickSubmit: function(event) {
        event.target.setCustomValidity(this.config.seo_valid ? "" : "Please fill out the SEO form");
    },

	/**
	 * Proverava da li kategorija sa postojecim imenom vec postoji
	 * @param  {Object} event Javascript event objekat
	 */
	blurName:function(event){
		var name = event.target.value;
		if(name.length > 0) this.isPageNameTaken(name);
	},

	/**
	 * Redirekcija na listu statickih strana kada se kreira seo
	 *
	 */
	redirectToList:function() {
        window.location.href = "/admin/staticne/strane";
	},

	/**
	 * Salje se forma i kerira ajax zahtev
	 * @param  {Object} event javascript event objekat
	 * @return {Boolean}       false zato sto se salje klasicnim putem
	 */
	formSubmited:function(event){
		var name 		= this.getElement("name").value;
		var category 	= this.getElement("category").value;
        var text    	= this.config.editor_text.getContent();

		this.createPage(name, category, text);
		event.target.reset();

		return false;
	},

	/**
     * Zadaje validity za name polje, u zavisnosti da li postoji kategorija s ovim  imenom
     * @param   {Boolean}   exists          Da li je ime kategorije vec zauzet
     * @return  {Object}                    Kirby.AdminStaticPages.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("name").setCustomValidity(exists ? "Page with this name already exists" : "");
        return this;
    },









	/**
	 * Dohvata se element da osnovu lokalnog imena
	 * @param  {String} element    Lokalno ime elementa definisano u vrhu fajla
	 * @param  {Boolean} querry_all da li nam treba jedan element ili svi koji odgovaraju upitu
	 * @param  {[type]} modifier   BEM Modifier za selektor
	 * @return {Node/NodeList}      Vraca Node objekat ukoliko je querry_all false u suprotnom vraca niz objekata
	 */
	getElement:function(element, querry_all, modifier){
		return Kirby.Main.Dom.getElement("AdminStaticPagesCreate", element, querry_all, modifier);
	},


    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
   	getElementSelector: function(element, query_all, modifier) {
		return Kirby.Main.Dom.getElementSelector("AdminStaticPagesCreate", element, query_all, modifier);
	},

	isPageNameTaken:function(name){
		Kirby.Main.Ajax(
			"AdminStaticPages",
			"isPageNameTaken",
			{
				"name": name,
			},
			this.setNameValidity.bind(this)
		);
		return this;
	},

	createPage:function(name, category, text){
		Kirby.Main.Ajax(
			"AdminStaticPages",
			"createPage",
			{
				"name": 	name,
				"category": category,
				"text": 	text,
			},
			this.pageCreated.bind(this),
			undefined,
			true
		);
		return this;
	},



};
document.addEventListener('DOMContentLoaded', Kirby.AdminStaticPages.Create.init.bind(Kirby.AdminStaticPages.Create), false);
