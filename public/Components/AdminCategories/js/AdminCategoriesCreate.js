"use strict";

if( typeof Kirby                 === "undefined") var Kirby             = {};
if( typeof Kirby.AdminCategories === "undefined") Kirby.AdminCategories = {};

Kirby.AdminCategories.Create = {



	/*
	*Konfiguracija komponente
	*/





	config:{
        seo_valid: false,
	},

	elements:{
		"form": 		"#admin_categories__create_form",
		"name": 		"#admin_categories__input_name",
		"image": 		"#admin_categories__input_image",
        "submit":       "#admin_categories__button_submit",
	},


	/**
	 * Inicijalizacija komponente
	 * @param  {Object} event event objekat javascripta
	 */
	init:function(event){
		this
			.registerElements()
			.initListeners()
	},

	/**
	 * Inicializacija osluskivaca u sklopu komponente kao i funkcija koje reaguju na njih
	 * @return {Object}  Kirby.AdminCategories.Create objekat za ulancavanje funkcija
	 */
	initListeners:function(){
		var form = this.getElement("form");
		if(form !== null) form.onsubmit = this.formSubmited.bind(this);

		var name = this.getElement("name");
		if(name !== null)name.addEventListener("blur", this.blurName.bind(this), false);
        this.getElement("submit").addEventListener("click", this.clickSubmit.bind(this), false);
        document.addEventListener("Kirby.SEO.Form", this.changedSEOState.bind(this), false);

		return this;
	},

	/**
	 * Registracija elemenata u uptrebi od strane komponente
	 * @return {Object} Kirby.AdminCategories.Create objekat za ulancavanje funkcija
	 */
	registerElements:function(){
		Kirby.Main.Dom.register("AdminCategoriesCreate", this.elements);
		return this;
	},
	 /**
     * Novi tag je kreiran, pa obaveštavamo ostatale komponente o tome
     * @param   {Object}    data            Informacije o kreiranom predmetu
     */
    categoryCreated: function(data) {
        var event = new CustomEvent("Kirby.Admin.Categories");
        event.info = "Create";
        event.data = data;
        document.dispatchEvent(event);
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
		if(name.length > 0) this.isCategoryNameTaken(name);
	},

	/**
	 * Salje se forma i kerira ajax zahtev
	 * @param  {Object} event javascript event objekat
	 * @return {Boolean}       false zato sto se salje klasicnim putem
	 */
	formSubmited:function(event){
		var name 				= this.getElement("name").value;
		var image 				= this.getElement("image");

		this.createCategory(name, image.files[0]);
		event.target.reset();

		return false;
	},

	/**
     * Zadaje validity za name polje, u zavisnosti da li postoji kategorija s ovim  imenom
     * @param   {Boolean}   exists          Da li je ime kategorije vec zauzet
     * @return  {Object}                    Kirby.AdminCategories.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("name").setCustomValidity(exists ? "Category with this name already exists" : "");
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
		return Kirby.Main.Dom.getElement("AdminCategoriesCreate", element, querry_all, modifier);
	},

	isCategoryNameTaken:function(name){
		Kirby.Main.Ajax(
			"AdminCategories",
			"isCategoryNameTaken",
			{
				"name": name,
			},
			this.setNameValidity.bind(this)
		);
		return this;
	},

	createCategory:function(name, image){
		Kirby.Main.Ajax(
			"AdminCategories",
			"createCategory",
			{
				"name": name,
				"image": image,
			},
			this.categoryCreated.bind(this),
			undefined,
			true
		);
		return this;
	},



};
document.addEventListener('DOMContentLoaded', Kirby.AdminCategories.Create.init.bind(Kirby.AdminCategories.Create), false);
