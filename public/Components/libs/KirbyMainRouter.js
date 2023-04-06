(function () {
    "use strict";

    if (window.Kirby === undefined) {
        window.Kirby = {};
    }

    if (window.Kirby.Main === undefined) {
        window.Kirby.Main = {};
    }


    window.Kirby.Main.Router = {


        elements: {},
        config:   {
            filters: {},
        },

        /**
         * Inicijalizacija komponente
         */
        init: function() {
            registerElements();
            initSlider();
            initListeners();
        },

        /**
         * inicalizuje osluskivace
         */
        initListeners: function() {
        },

        /**
         * Registruje elemente koji se koriste u komponenti
         */
        registerElements: function() {
            Kirby.Main.Dom.register("KirbyMainRouter", elements);
        },


        /**
         * Prilikom promene url-a menja query string
         * @param data objekat sa informacijama o filteru
         */
        stateChanged: function(data, type, refresh_data) {
            var storage   = this.config.filters;
            storage[type] = data;

            this.updateState(refresh_data);
        },

        updateState: function(refresh_data) {
            var state  = "?";
            var keys   = Object.keys(this.config.filters);
            for (var i = 0, l = keys.length; i < l; i++) {
                var filter_type = keys[i];
                var group = this.config.filters[filter_type];
                for (var j = 0, jl = group.length; j < jl; j++) {
                    var filter = group[j];
                    if (Array.isArray(filter.values)) {
                        for (var i=0; i < filter.values.length; i++) {
                            filter.values[i] = filter.values[i].replaceAll(', ', 'commastring');
                        }
                    }
                    state += filter.title + "=" + filter.values + "&";
                }
            }
            var loc = window.location.href.split("?")[0];
            window.history.replaceState({}, document.title, loc + state);
            if (refresh_data) {
                var event = new CustomEvent("Router.Url.Changed");
                document.dispatchEvent(event);
            }
        },

        /**
         * Dohvatanje elementa, na osnovu lokalnog imena
         * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
         * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
         * @param   {String}    modifier        BEM modifier za selektor
         * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
         */
        getElement: function(element, query_all, modifier, parent) {
            return Kirby.Main.Dom.getElement("ProductFilter", element, query_all, modifier, parent);
        },

        /**
         * Dohvatanje selektora za elementa, na osnovu lokalnog imena
         * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
         * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
         * @param   {String}    modifier        BEM modifier za selektor
         * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
         */
        getElementSelector: function(element, query_all, modifier) {
            return Kirby.Main.Dom.getElementSelector("ProductFilter", element, query_all, modifier);
        },
    };
}());
