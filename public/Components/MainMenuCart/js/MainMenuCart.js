(function () {
    "use strict";

    var elements = {
        items_number: "#menu_cart__number",
    };

    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function () {
        document.addEventListener("Cart.Update", cartUpdated, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("MainMenuCart", elements);
    };









    var cartUpdated = function () {
        fetchData();
    };









    var renderData = function (data) {
        var element = getElement("items_number");
        element.innerHTML = data;
    };










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("MainMenuCart", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("MainMenuCart", element, query_all, modifier);
    };

    var fetchData = function () {
        Kirby.Main.Ajax(
            "MainMenuCart",
            "fetchData",
            {},
            renderData
        );
    };



    document.addEventListener("DOMContentLoaded", init);
}());
