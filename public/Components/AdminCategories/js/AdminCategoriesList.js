"use strict"

if(typeof Monitor                 === "undefined") var Monitor             = {};
if(typeof Monitor.AdminCategories === "undefined") Monitor.AdminCategories = {};

Monitor.AdminCategories.List = {


	config:{
        sortable: null,
	},
	elements:{
		"categories_templ": 	"#admin_categories__list_tmpl",
		"image_click": 			".admin_categories__image",
		"image_change": 		".admin_categories__picture_change",
        "sortable_wrapper":     "#admin__sortable_wrapper", // Omotac za sortiranje
        "wrapper":              "#admin_categories__list",
	},

	templates:{
		main:function(){},
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
		var $wrapper = $(this.getElement('wrapper'));

		$wrapper.on("click",    this.getElementSelector("image_click"),            this.clickImage.bind(this));
        $wrapper.on("change",   this.getElementSelector("image_change"),            this.changedImage.bind(this));

        document.addEventListener("Monitor.Admin.Categories", this.changeOccured.bind(this), false);
        document.addEventListener("Monitor.Admin.SEO.Create", this.fetchData.bind(this), false);
        return this;

	},

	  /**
     * Inicijalizacija sablona
     * @return  {Object}                    Monitor.AdminCategories.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_categories__list_tmpl").innerHTML);

        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.AdminFields.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Monitor.Main.DOM.register("AdminCategoriesList", this.elements);
        return this;
    },









    /**
     * Prilikom promene refreshuje
     * @return {Function} ponovo povlaci podatke
     */
    changeOccured:function(event){
        var refresh_on = ["Update"];
        var fetch_on = ["Create", "Delete"];
        if (event.info !== "Create") {
            this.fetchData();
        }

    },

    /**
     * aktivira se klikom na sliku i simulira klik na input
     * @param  {Obejct} event Javascript event objekat
     */
    clickImage: function(event) {
        var input = event.currentTarget.previousSibling;
        if (input) input.click(); // Ako nema dozvolu za izmenu, nece biti input-a
    },
     /**
     * Promena slike
     * @param  {Object} event       JavaScript event objekat
     */
    changedImage: function(event) {
        var category_id = parseInt(event.target.dataset.categoryId, 10);
        this.updateImage(category_id, event.target.files[0]);
    },





	 /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("AdminCategoriesList", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
	getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminCategoriesList", element, query_all, modifier);
    },
    /**
    * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
    * @param  {Object}     data            Prosledjeni podaci
    */
	render: function(data) {

        this.getElement("wrapper").innerHTML = this.templates.main({
           "categories": data.categories,
        });
        if (Monitor._params.AdminCategories.permission_reorder) {
            if (this.config.sortable) this.config.sortable.destroy();
            this.config.sortable = Sortable.create(this.getElement("sortable_wrapper"), {
                animation: 150,
                onEnd: function (event) {
                    this.changeOrder(event.oldIndex + 1, event.newIndex + 1);
                }.bind(this)
            });
        }
        return this;
    },









     /**
     * Dohvata podatke neophodne za funkcionisanje komponenti, nakon toga prikazuje komponentu
     * @return {Object}                     Monitor.AdminCategories.List objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Monitor.Main.Ajax(
            "AdminCategories",
            "fetchData",
            {},
            function(data) {
            	this.render(data);
			}.bind(this)

        );
    },

    /**
     * Promena slike kategorije
     * @param  {Number}     id              ID kategorije kojoj menjamo sliku
     * @param  {File}       picture         Nova slika
     * @return {Object}                     Monitor.AdminCategories.List objekat, za ulancavanje funkcija
     */
    updateImage: function(id, picture) {
        Monitor.Main.Ajax(
            "AdminCategories",
            "updateImage",
            {
                "category_id": id,
                "picture": picture,
            },
           	 function(data) {
                var event = new CustomEvent("Monitor.Admin.Categories");
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
     * Promena redosleda
     * @param  {Number}     order_old       Stara pozicija
     * @param  {Number}     order_new       Nova pozicija
     * @return {Object}                     Monitor.AdminCourses.List objekat, za ulancavanje funkcija
     */
    changeOrder: function(order_old, order_new) {
        Monitor.Main.Ajax(
            "AdminCategories",
            "changeOrder",
            {
                "order_old": order_old,
                "order_new": order_new,
            }
        );
    },
};
document.addEventListener('DOMContentLoaded', Monitor.AdminCategories.List.init.bind(Monitor.AdminCategories.List), false);