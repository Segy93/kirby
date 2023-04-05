(function () {
    "use strict"

    var elements = {
        text: ".breadcrumbs_item--text",
    };

    var initListeners = function () {
        window.addEventListener("popstate", statePop, false);
        document.addEventListener("Monitor.PushState", statePop, false);
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("Breadcrumbs", elements);
    };


    var statePop = function() {
        getElement("text").innerHTML = window.location.pathname.substring(1).replace(/\b\w/g, function(l) {
            return l.toUpperCase();
        });    
        // bilo pre umesto function ie 11 ne podrzava
        // l => l.toUpperCase());
    };









    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("Breadcrumbs", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("Breadcrumbs", element, query_all, modifier);
    };

    registerElements();
    initListeners();
}());
