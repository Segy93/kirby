"use strict"

if(typeof Kirby                 === "undefined") var Kirby             = {};
if(typeof Kirby.AdminStaticPages === "undefined") Kirby.AdminStaticPages = {};

Kirby.AdminStaticPages.List = {


	config:{
        sortable: null,
	},
	elements:{
		"pages_templ": 	"#admin_pages__static_list__tmpl",
		"image_click": 			".admin_pages__static_image",
		"image_change": 		".admin_pages__static_picture_change",
        "sortable_wrapper":     "#admin__sortable_wrapper", // Omotac za sortiranje
        "wrapper":              "#admin_pages__static_list",
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

        document.addEventListener("Kirby.Admin.StaticPages", this.changeOccured.bind(this), false);
        return this;

	},

	  /**
     * Inicijalizacija sablona
     * @return  {Object}                    Kirby.AdminStaticPages.List objekat, za ulančavanje funkcija
     */
    initTemplates: function() {
        this.templates.main = _.template(document.getElementById("admin_pages__static_list__tmpl").innerHTML);

        return this;
    },

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Kirby.AdminFields.List objekat, za ulančavanje funkcija
     */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminStaticPagesList", this.elements);
        return this;
    },









    /**
     * Prilikom promene refreshuje
     * @return {Function} ponovo povlaci podatke
     */
    changeOccured:function(){
        var refresh_on = ["Update"];
        var fetch_on = ["Create", "Delete"];

        this.fetchData();

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
        var page_id = parseInt(event.target.dataset.pageId, 10);
        this.updateImage(page_id, event.target.files[0]);
    },





	 /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminStaticPagesList", element, query_all, modifier);
    },

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
	getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminStaticPagesList", element, query_all, modifier);
    },
    /**
    * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
    * @param  {Object}     data            Prosledjeni podaci
    */
	render: function(data) {

        this.getElement("wrapper").innerHTML = this.templates.main({
           "pages": data.pages,
        });
        if (Kirby._params.AdminStaticPages.permission_reorder) {
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
     * @return {Object}                     Kirby.AdminStaticPages.List objekat, za ulancavanje funkcija
     */
    fetchData: function() {
        Kirby.Main.Ajax(
            "AdminStaticPages",
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
     * @return {Object}                     Kirby.AdminStaticPages.List objekat, za ulancavanje funkcija
     */
    updateImage: function(id, picture) {
        Kirby.Main.Ajax(
            "AdminStaticPages",
            "updateImage",
            {
                "page_id": id,
                "picture": picture,
            },
           	 function(data) {
                var event = new CustomEvent("Kirby.Admin.StaticPages");
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
     * @return {Object}                     Kirby.AdminCourses.List objekat, za ulancavanje funkcija
     */
    changeOrder: function(order_old, order_new) {
        Kirby.Main.Ajax(
            "AdminStaticPages",
            "changeOrder",
            {
                "order_old": order_old,
                "order_new": order_new,
            }
        );
    },
};
document.addEventListener('DOMContentLoaded', Kirby.AdminStaticPages.List.init.bind(Kirby.AdminStaticPages.List), false);