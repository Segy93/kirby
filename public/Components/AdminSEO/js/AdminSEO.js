"use strict";

if (Kirby === undefined) window.Kirby = {};

Kirby.AdminSEO = {
    config: {
        field:  null, //Cuva polje za koje treba da se zakaci custom validity
        valid:  true, //validnost forme
        submit: false,
    },

    elements: {
        button_save:                "#admin_seo__form_save",
        button_submit:              "#admin_seo__submit",
        button_unlock:              ".admin_seo__form__url_button",
        change_thumbnail_twitter:   ".admin_seo__change__thumbnail_twitter",
        form_input_description:     "#admin_seo__edit_form_input_description",
        form_input_keywords:        "#admin_seo__edit_form_input_keywords",
        form_input_machine_name:    "#admin_seo__form_input_machine_name",
        form_input_title:           "#admin_seo__form_input_title",
        form_input_url:             "#admin_seo__form__url",
        form_update:                "#admin_seo__form_update",
        image_create:               "#admin_seo__image",
        image_open_graph:           ".admin_seo__image_open_graph",
        image_twitter:              ".admin_seo__image_twitter",
        input_description:          "#admin_seo__description",
        input_keywords:             "#admin_seo__keywords",
        input_machine_name:         "#admin_seo__machine_name",
        input_title:                "#admin_seo__title",
        input_url:                  "#admin_seo__url",
        invoker:                    ".seo__invoker",
        modal_update:               "#admin_seo__modal_update",
        seo_form:                   "#admin_seo__form",
        thumbnail_twitter:          ".admin_seo__thumbnail_twitter",
        // "button_warning_modal_close": "#admin_seo__modal_warning__button_close",
        // "button_modal_close":         "#admin_seo__form_update__button_close",
        // "modal_warning":              "#admin_seo__modal_warning",
    },










    init: function(event) {
        this
            .registerElements()
            .initListeners()
        ;
    },

    initListeners: function() {
        var form = this.getElement("seo_form");
        if(form !== null) form.onsubmit = this.formSubmitted.bind(this);
        this.getElement("form_update").onsubmit = this.clickUpdate.bind(this);
        if (this.getElement("input_url") !== null) {
            this.getElement("input_url").addEventListener("blur", this.blurUrl.bind(this), false);
        }
        this.getElement("form_input_url").addEventListener("blur", this.blurUrl.bind(this), false);
        this.getElement("button_unlock").addEventListener("click", this.clickUnlockUrl.bind(this), false);
        // this.getElement("button_modal_close").addEventListener("click", this.clickSeoModalClose.bind(this), false);

        document.addEventListener("Kirby.Admin.Tags", this.createdTag.bind(this));
        document.addEventListener("Kirby.Admin.Categories", this.createdCategory.bind(this));
        document.addEventListener("Kirby.Admin.StaticCategories", this.createdStaticCategory.bind(this));
        document.addEventListener("Kirby.Admin.Articles", this.createdArticle.bind(this));
        document.addEventListener("Kirby.Admin.StaticPages", this.createdStaticPage.bind(this));

        $(this.getElementSelector("modal_update")).on("hidden.bs.modal", this.closeModal.bind(this));
        $(document).on("click", this.getElementSelector("invoker"), this.clickedInvoker.bind(this));
        $(document).on("click", this.getElementSelector("thumbnail_twitter"), this.clickImage.bind(this));
        $(document).on("change", this.getElementSelector("change_thumbnail_twitter"), this.changedImageTwitter.bind(this));

        $(this.getElementSelector("seo_form")).on("blur", "input, textarea", this.blurInput.bind(this));
    },

    registerElements: function() {
        Kirby.Main.Dom.register("AdminSEO", this.elements);
        return this;
    },










    createdTag: function(event) {
        if (event.info === "Create") {
            var tag_id = event.data;
            var machine_name = "tag_" + tag_id;
            this.getElement("input_machine_name").value = machine_name;
            this.config.submit = true;
            this.getElement("button_submit").click();
        }
    },

    createdCategory: function(event) {
            if (event.info === "Create") {
            var category_id = event.data;
            var machine_name = "articleCategory_" + category_id;
            this.getElement("input_machine_name").value = machine_name;
            this.config.submit = true;
            this.getElement("button_submit").click();
        }
    },

    createdArticle: function(event) {
            if (event.info === "Create") {
            var article_id = event.data;
            var machine_name = "article_" + article_id;
            this.getElement("input_machine_name").value = machine_name;
            this.config.submit = true;
            this.getElement("button_submit").click();
        }
    },

    createdStaticCategory: function(event) {
            if (event.info === "Create") {
            var static_category_id = event.data;
            var machine_name = "static_category_" + static_category_id;
            this.getElement("input_machine_name").value = machine_name;
            this.config.submit = true;
            this.getElement("button_submit").click();
        }
    },



    createdStaticPage: function(event) {
        if (event.info === "Create") {
        var static_page_id = event.data;
        var machine_name = "static_" + static_page_id;
        this.getElement("input_machine_name").value = machine_name;
        this.config.submit = true;
        this.getElement("button_submit").click();
    }
},











    blurUrl: function(event) {
        this.config.field = event.target;
        this.validateUrl(event);
        this.isUrlTaken(event.target.value);
    },

    blurInput: function(event) {
        var seo_event = new CustomEvent("Kirby.SEO.Form");
        var form = $(event.target).parents("form");
        var message = "";
        var inputs = event.target.parentNode.querySelectorAll("input:not([type=hidden])");
        for (var i = 0, l = inputs.length; i < l; i++) {
            this.config.valid =  inputs[i].validity.valid;
            message = inputs[i].validationMessage;
            if(this.config.valid === false) {
                break;
            }
        }

        var inputs = event.target.parentNode.querySelectorAll("textarea");
        for (var i = 0, l = inputs.length; i < l; i++) {
            this.config.valid = this.config.valid && inputs[i].validity.valid;
        }
        seo_event.valid = this.config.valid;
        seo_event.message = message;
        document.dispatchEvent(seo_event);
    },

    /*clickSeoModalClose: function() {
        $(this.getElementSelector("modal_warning")).modal("show");
    },*/

    clickImage: function(event) {
        event.currentTarget.previousSibling.click();
    },

    clickUnlockUrl: function(event) {
        this.setURLState(true);
    },

    changedImageTwitter: function(event) {
        var input = event.currentTarget;
        var image = this.getElement("thumbnail_twitter");
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                image.src = e.target.result;
            }

            reader.readAsDataURL(input.files[0]);
        }
    },

    clickedInvoker: function(event) {
        var machine_name = event.currentTarget.dataset.machine_name;
        this.getElement("form_input_machine_name").value = machine_name;
        this.fetchData(machine_name);

        $(this.getElement("modal_update")).modal("show");
    },

    clickUpdate: function(event) {
        var machine_name    = this.getElement("form_input_machine_name").value;
        var title           = this.getElement("form_input_title").value;
        var description     = this.getElement("form_input_description").value;
        var keywords        = this.getElement("form_input_keywords").value;
        var input_url       = this.getElement("form_input_url");
        var input_picture   = this.getElement("change_thumbnail_twitter");

        var url = input_url.disabled ? undefined : input_url.value;
        var picture = input_picture.files.length > 0 ? input_picture.files[0] : null;

        this.updateSEO(machine_name, title, description, keywords, picture, url);
        $(this.getElement("modal_update")).modal("hide");
        return false;
    },

    closeModal: function() {
        this.lockUrl();
    },

    formSubmitted: function(event) {
        var machine_name    = this.getElement("input_machine_name").value;
        var title           = this.getElement("input_title").value;
        var url             = this.getElement("input_url").value;
        var description     = this.getElement("input_description").value;
        var keywords        = this.getElement("input_keywords").value;
        var input_picture   = this.getElement("image_create");

        var picture = input_picture.files.length > 0 ? input_picture.files[0] : null;

        if (this.config.submit === true) {
            this.createEntry(machine_name, title, description, keywords, picture, url);
            event.target.reset();
            this.config.submit = false;
        }

        return false;
    },

    setURLState: function(state) {
        this.getElement("form_input_url").disabled = !state;
        this.getElement("button_unlock").classList[state ? "add" : "remove"]("hidden");
    },

    SEOCreated: function(data) {
        var event = new CustomEvent("Kirby.Admin.SEO.Create");
        event.data = data;
        document.dispatchEvent(event);
    },

    SEOUpdated: function(data) {
        var event = new CustomEvent("Kirby.Admin.SEO.Update");
        event.data = data;
        document.dispatchEvent(event);
    },


    lockUrl: function() {
        this.setURLState(false);
    },









    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminSEO", element, query_all, modifier);
    },

    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminSEO", element, query_all, modifier);
    },

    validateUrl: function(event) {
        var elem = event.currentTarget;
        var error = "";
        error = event.target.value.indexOf(" ") !== -1 ? "Url should not contain space" : "";
        if(error === "") error = event.target.value.indexOf("/") !== -1 ? "Url should not contain / sign" : "";
        if(error === "") error = event.target.value.match(/^[a-zA-Z0-9\-]+$/) === null ? "URL should only contain letters of English alphabet or numbers." : "";

        elem.setCustomValidity(error);
        return this;
    },

    validateUrlName: function(event) {
        var error = event ? "Ova putanja je zauzeta" : "";

        this.config.field.setCustomValidity(error);
        return this;
    },

    render: function(data) {
        this.getElement("form_input_title").value = data.title;
        this.getElement("form_input_url").value = data.url;
        this.getElement("form_input_description").value = data.description;
        this.getElement("form_input_keywords").value = data.keywords;
        // this.getElement("image_twitter").src = "/uploads_admin/" + data.image_twitter;
        // this.getElement("image_open_graph").src = "/uploads_admin/" + data.image_open_graph;

        var image_twitter = this.getElement("thumbnail_twitter");
        if (data.thumbnail_twitter) {
            image_twitter.src = "/uploads_static/originals/" + data.thumbnail_twitter;
        } else {
            image_twitter.src = "/Components/AdminSEO/img/add.png";
        }
     },










    createEntry: function(machine_name, title, description, keywords, picture, url) {
        var params = {
            "machine_name": machine_name,
            "title":        title,
            "description":  description,
            "keywords":     keywords,
            "url":          url,
        };

        if (picture) params["picture"] = picture;

        Kirby.Main.Ajax(
            "AdminSEO",
            "createEntry",
            params,
            this.SEOCreated.bind(this),
            {},
            true
        );
        return this;
    },

    updateSEO: function(machine_name, title, description, keywords, picture, url) {
        var params = {
            "machine_name": machine_name,
            "title":        title,
            "description":  description,
            "keywords":     keywords,
        };

        if (url) params['url'] = url;
        if (picture) params["picture"] = picture;

        Kirby.Main.Ajax(
            "AdminSEO",
            "updateSEO",
            params,
            this.SEOUpdated.bind(this),
            {},
            true
        );
    },

    fetchData: function(machine_name) {
        Kirby.Main.Ajax(
        "AdminSEO",
        "fetchData",
        {
            "machine_name": machine_name,
        },
            this.render.bind(this)
        );
        return this;
    },

    isUrlTaken: function(url) {
        Kirby.Main.Ajax(
        "AdminSEO",
        "isUrlTaken",
        {
            "url": url,
        },
            this.validateUrlName.bind(this)
        );
        return this;
    },

};

document.addEventListener('DOMContentLoaded', Kirby.AdminSEO.init.bind(Kirby.AdminSEO), false);
