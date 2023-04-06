(function () {
    "use strict";

    var config = {
    };

    var elements = {
    };










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initListeners();
    };

    /**
     * inicalizuje osluskivace
     */
    var initListeners = function() {
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Kirby.Main.Dom.register("UserProfile", elements);
    };




    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node|NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("UserProfile", element, query_all, modifier, parent);
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("UserProfile", element, query_all, modifier);
    };









    document.addEventListener("DOMContentLoaded", init);
}());
