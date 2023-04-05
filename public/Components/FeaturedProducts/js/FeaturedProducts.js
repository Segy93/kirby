(function ()  {








    var elements = {
        radios:     ".featured_products__category_products--active",
    };


    var config = {
        current_term: "",
    }








    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function() {
        var radios = getElement("radios", true);

        window.addEventListener("load", radioChecked, false);

        for(var i = 0, l = radios.length; i < l; i++) {
            radios[i].addEventListener("focus", radioFocused, false);
            radios[i].addEventListener("blur", radioBlurred, false);
            radios[i].addEventListener("change", radioUnchecked, false)
        }
    };




    var registerElements = function() {
        Monitor.Main.DOM.register("FeaturedProducts", elements);
    };





    var radioFocused = function(event) {
        var radios = getElement("radios", true);
        for(var i = 0, l = radios.length; i < l; i++) {
            if (radios[i].checked === false) {
                radios[i].labels[0].classList.remove("featured_products__filter_single__link_checked");
            }
            radios[i].labels[0].classList.remove("featured_products__filter_single__link_focused");
        }
        event.currentTarget.labels[0].classList.add("featured_products__filter_single__link_focused");
    };

    var radioChecked = function() {
        var radios = getElement("radios", true);
        for(var i = 0, l = radios.length; i < l; i++) {
            if (radios[i].checked) {
                radios[i].labels[0].classList.add("featured_products__filter_single__link_checked");
            }
        }
    };

    var radioUnchecked = function() {
        var radios = getElement("radios", true);
        for(var i = 0, l = radios.length; i < l; i++) {
            if (radios[i].checked) {
                radios[i].labels[0].classList.add("featured_products__filter_single__link_checked");
            } else {
                radios[i].labels[0].classList.remove("featured_products__filter_single__link_checked");
            }
        }
    };

    var radioBlurred = function() {
        var radios = getElement("radios", true);
        for(var i = 0, l = radios.length; i < l; i++) {
            radios[i].labels[0].classList.remove("featured_products__filter_single__link_focused");
        }
    };



    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("FeaturedProducts", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("FeaturedProducts", element, query_all, modifier);
    };








    // var getSearchResults = function(query, response) {
    //     Monitor.Main.Ajax(
    //         "FeaturedProducts",
    //         "getSearchResults",
    //         {
    //             query: query,
    //         }, function(data) {
    //             if (query === config.current_term) {
    //                 response(data);
    //             }
    //         }
    //     );
    // };

    document.addEventListener("DOMContentLoaded", init);
}());
