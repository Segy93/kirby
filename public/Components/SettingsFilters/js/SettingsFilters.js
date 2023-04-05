(function () {
    "use strict";

    var config = {
        startMin: 0,
        startMax: 0,
        filters:  {},
        page: 0,
        refresh_data: true,
    };

    var elements = {
        limit:              ".settings_filters__limit_radio",
        search_field:       ".settings_filters__search_field",
        sort:               ".settings_filters_sort",
        search_button:      ".settings_filters__search_submit",
        pagination_next:    ".settings_filters__next",
        pagination_prev:    ".settings_filters__prev",
        view_radio:         ".settings_filters__view_type_radio",
    };










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function() {
        registerElements();
        initListeners();
        initData();
    };

    /**
     * inicijalizuje osluskivace
     */
    var initListeners = function() {
        var limits = getElement("limit", true);
        for (var i = 0, l = limits.length; i < l; i++) {
            var limit = limits[i];
            limit.addEventListener("change", limitChanged, false);
        }

        var view_buttons = getElement("view_radio", true);

        for (var j = 0, lj = view_buttons.length; j < lj; j++) {
            var view_button = view_buttons[j];
            view_button.addEventListener("change", viewChanged, false);
        }

        getElement("search_button").addEventListener("click", searchChanged);
        getElement("sort").addEventListener("change", sortChanged);
        document.addEventListener("Monitor.Filters.More.Clicked", moreClicked, false);
    };

    var initData = function () {
        var href = getUrlVars(window.location.search);
        if (href.limit) {
            config.filters.limit = href.limit;
            getElement("limit", false, href.limit).checked = true;
        } else {
            var limits = getElement("limit", true);
            for (var i = 0, l = limits.length; i < l; i++) {
                var limit = limits[i];
                if (limit.checked)  config.filters.limit = limit.value;
            }
        }
        if (href.search) {
            config.filters.search = href.search;
            getElement("search_field").value = href.search;
        }

        if(href.strana) {
            config.page = href.strana;
        }

        if (href.sort) {
            config.filters.sort = href.sort;
            var select  = getElement("sort");
            var options = select.options;
            if (select.selected) {
                config.filters.sort = select.value;
            }

            for (var j = 0, lj = options.length; j < lj; j++) {
                if (options[j].value === href.sort) {
                    options[j].selected = true;
                }
            }
        } else {
            var select_sort  = getElement("sort");
            var sorts = select_sort.options;
            for (var k = 0, lk = sorts.length; k < lk; k++) {
                var sort = sorts[k];
                if (sort.selected)  config.filters.sort = sort.value;
            }
        }

        filterChanged();
    };


    var getUrlVars = function (url) {
        var hash;
        var myJson = {};
        var hashes = url.slice(url.indexOf("?") + 1).split("&");
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split("=");
            myJson[hash[0]] = hash[1];
        }
        return myJson;
    };

    var viewChanged = function (event) {
        var el = event.currentTarget;
        var event   = new CustomEvent("Monitor.CategoryPageView");
        event.data = el.value;
        document.dispatchEvent(event);
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Monitor.Main.DOM.register("SettingsFilters", elements);
    };

    var limitChanged = function(event) {
        var limit = event.currentTarget.value;
        config.filters.limit = limit;
        filterChanged();
    };

    var searchChanged = function(event) {
        event.preventDefault();
        var search = getElement("search_field").value;
        config.filters.search = search;
        filterChanged();
    };

    var sortChanged = function(event) {
        event.preventDefault();
        var sort = getElement("sort").value;
        config.filters.sort = sort;
        filterChanged();
    };

    var moreClicked = function() {
        config.page++;
        config.refresh_data = false;
        filterChanged();
    }


    var filterChanged = function() {
        var data = [];
        var keys = Object.keys(config.filters);
        for (var i = 0, l = keys.length; i < l; i++) {
            var title = keys[i];
            data.push({
                title:  title,
                values: config.filters[title],
            });
        }
        data.push({
            title: 'strana',
            values: config.page
        });
        var state_type = "settings_filters";
        Monitor.Main.Router.stateChanged(data, state_type, config.refresh_data);
        var ev    = new CustomEvent("Settings.Filter.Changed");
        ev.data = config.refresh_data;
        document.dispatchEvent(ev);
    };



    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz objekata
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("SettingsFilters", element, query_all, modifier, parent);
    };










    document.addEventListener("DOMContentLoaded", init);
}());
