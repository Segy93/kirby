"use strict";

if(typeof Monitor           === "undefined") var  Monitor      = {};
if(typeof Monitor.AdminTags === "undefined") Monitor.AdminTags = {};


Monitor.AdminTags.Change = {

	config: {
        callback_submit: null,
        processing_name: false,    
	},

	elements: { // Elementi u komponenti
		"form": 		"#admin_tags__change_form",
		"input_name": 	"#admin_tags__change__input",
		"wrapper": 		"#admin_tags__modal_change",
	},








	init: function(event) {
		this
			.registerElements()
			.initListeners()
			;
	},

	initListeners: function() {
		$(this.getElementSelector("wrapper")).on("show.bs.modal", this.componentRequested.bind(this));
        this.getElement("form").onsubmit = this.submitChanges.bind(this);
        this.getElement("input_name").addEventListener("blur", this.blurName.bind(this), false);
       return this;
	},

	registerElements: function() {
		Monitor.Main.DOM.register("AdminTagsChange", this.elements);
        return this;
	},








 	 /**
     * Poslata je forma za promenu imena
     * @param   {Object}    event           JS event objekat
     */
    submitChanges: function(event) {
        var form        = event.target;
        var elements    = form.elements;

        var tag_id      = parseInt(elements.tag_id.value, 10);
        var name        = elements.name.value;
        var original    = elements.name.dataset.original;

        if (name !== original) this.updateName(tag_id, name);

        return false;
    },

     /**
     * Otvorice se modal
     * @param   {Object}    event           jQuery event objekat
     */
    componentRequested: function(event) {
        var tag_id = parseInt(event.relatedTarget.dataset.tagId, 10);
        var elements = this.getElement("form").elements;
        elements.tag_id.value = tag_id;

        this.fetchTag(tag_id);
//         elements.name.value = event.relatedTarget.textContent.trim();
    },

    /**
     * Provera da li tag sa datim imenom vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    blurName: function(event) {
        var name = event.target.value;
        if (name !== event.target.dataset.original && name.length !==0) this.isNameTaken(name);
    },











    /**
     * Zatvara modal
     * @return  {Object}                    Monitor.AdminUsers.Dialogs.Password objekat, za ulančavanje funkcija
     */
    hideDialog: function() {
        $(this.getElement("wrapper")).modal("hide");

        var form = this.getElement("form");
        form.reset();
        return this;
    },









    /**
     * Zadaje validity za name polje, u zavisnosti da li postoji korisnik s ovim korisnickim imenom
     * @param   {Boolean}   exists          Da li je username vec zauzet
     * @return  {Object}                    Monitor.AdminTags.Create objekat, za ulančavanje funkcija
     */
    setNameValidity: function(exists) {
        this.getElement("input_name").setCustomValidity(exists ? "User with this username already exists" : "");
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
        return Monitor.Main.DOM.getElement("AdminTagsChange", element, query_all, modifier);
    },

	/**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
	getElementSelector: function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AdminTagsChange", element, query_all, modifier);
    },

    /**
     * Generise HTML na osnovu prosledjenih podataka i ubacuje u omotac
     * @param  {Object} data        podaci o tagu
     * @return {Object}      Monitor.AdminTags.Change objekat za ulancavanje funkcija
     */
    render: function(data) {
        var elements = this.getElement("form").elements;

        elements.tag_id.value           = data.id;
        elements.name.value             = data.name;
        elements.name.dataset.original  = data.name;

        return this;
    },











    /**
    * Provera da li vec postoji tag s ovim korisnickim imenom
    * @param   {String}    username        Ime koje proveravamo
    * @return  {Object}                    Monitor.AdminTags.Change objekat, za ulančavanje funkcija
    */
    isNameTaken: function(name) {
        Monitor.Main.Ajax(
            "AdminTags",
            "isNameTaken",
            {
                "name": name,
            },
            function(exists) {
                this.getElement("input_name").setCustomValidity(exists ? "User with this username already exists" : "");
                if (this.config.callback_submit !== null) {
                    this.config.callback_submit();
                    this.hideDialog();
                }
                this.config.processing_name = false;
            }.bind(this)
        );
        return this;
    },

    /**
    * Menja trenutno ime u podeseno
    * @param  {Number} tag_id ID taga kojem se menja ime
    * @param  {String} name   ime kojim se menja
    * @return {Object}        Monitor.AdminTags.Change objekat za ulancavanje funkcija
    */
    updateName: function(tag_id, name) {
        var callback = function () {
            Monitor.Main.Ajax(
                "AdminTags",
                "updateName",
                {
                    "tag_id": tag_id,
                    "name": name,
                },
                function(data) {

                    var event = new CustomEvent("Monitor.Admin.Tags");
                    event.info = "Update";
                    event.data = data;
                    document.dispatchEvent(event);
                }
            );
        };

        if (this.config.processing_name === false) {
            this.config.callback_submit = null;
            callback();
            this.hideDialog();
        } else {
            this.config.callback_submit = callback;
        }

        return this;
    },

    /**
    * Dohvata tag zarad dobijanja parametara
    * @param  {Number} tag_id ID taga kojem se menja ime
    * @return {Object}        Monitor.AdminTags.Change objekat za ulancavanje funkcija
    */
    fetchTag: function(tag_id) {
        Monitor.Main.Ajax(
            "AdminTags",
            "fetchTag",
            {
                "tag_id": tag_id,
            },
            this.render.bind(this)
        );
    }

};
document.addEventListener('DOMContentLoaded', Monitor.AdminTags.Change.init.bind(Monitor.AdminTags.Change), false);
