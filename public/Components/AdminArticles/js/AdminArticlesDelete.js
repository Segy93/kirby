"use strict"

if(typeof Kirby  			 === "undefined") var Kirby 			 = {};
if(typeof Kirby.AdminArticles === "undefined") Kirby.AdminArticles = {};

/**
 *
 * Modal za potvrdu brisanja clanaka
 *
 */
Kirby.AdminArticles.Delete = {

	config :{ // Konfiguracija komponente
		"article_id": 0, // ID clanka koji ce biti obrisan
	},

	elements :{ // Selektori elemenata koje komponenta koristi
		"button_confirm": 			"#admin_articles__modal_delete__confirm",
		"wrapper": 					"#admin_articles__modal_delete",
	},

    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           JS event objekat
     */
	init: function(event) {
		this
			.registerElements()
			.initListeners()
		;
	},

    /**
     * Inicijalizacija osluskivaca komponente
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
	initListeners: function() {
		$(this.getElementSelector("wrapper")).on("show.bs.modal", this.requestedComponent.bind(this));
		this.getElement("button_confirm").addEventListener("click", this.clickDelete.bind(this), false);
		return this;
	},

    /**
     * Registracija elemenata za Kirby.Main.Dom
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
	registerElements: function() {
		Kirby.Main.Dom.register("AdminArticlesDelete", this.elements);
		return this;
	},








    /**
     * Klik na taster za potvrdu brisanja
     * @param   {Object}    event           JS event objekat
     */
	clickDelete: function(event) {
		this.deleteArticle();
	},

    /**
     * Modal za potvrdu je zahtevan; Pamtimo ID clanka za koji je vezan
     * @param   {Object}    event           jQuery event objekat
     */
	requestedComponent: function(event) {
		var article_id = parseInt(event.relatedTarget.dataset.articleId, 10);
		this.setArticleID(article_id);
	},








	/**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminArticlesDelete", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminArticlesDelete", element, query_all, modifier);
    },








    /**
     * Dohvata ID trenutno aktivnog clanka
     * @return  {Number}                    ID aktivnog clanka
     */
    getArticleID: function() {
    	return this.config.article_id;
    },

    /**
     * Cuva ID trenutno aktivnog clanka
     * @param   {Number}    article_id      ID clanka
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
    setArticleID: function(article_id) {
    	this.config.article_id = article_id;
    	return this;
    },










    /**
     * Brisanje clanka
     * @return  {Object}                    Kirby.AdminArticles.Delete
     */
    deleteArticle: function() {
    	Kirby.Main.Ajax(
    		"AdminArticles",
    		"deleteArticle",
    		{
    			"article_id": this.getArticleID(),
    		},
    		function(data) {
    			var event  = new CustomEvent("Kirby.Admin.Articles");
    			event.info = "Delete";
    			event.data = data;
    			document.dispatchEvent(event);
    		}
    	);

    	return this;
    },
}

document.addEventListener("DOMContentLoaded", Kirby.AdminArticles.Delete.init.bind(Kirby.AdminArticles.Delete, false));
