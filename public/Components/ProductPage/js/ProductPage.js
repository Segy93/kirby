(function () {
    "use strict"

    var elements = {

        buttonChangeWish:         ".product_page__change_wishlist",
        buttonChangeCart:         ".product_page__change_cart",
        cart_quantity:            ".product_page__cart_quantity",
        productPage:              ".product_page__wrapper",
    };

    var init = function(event) {
        registerElements();
        initListeners();

    };

    var initListeners = function () {

    };

    var registerElements = function () {
        Monitor.Main.DOM.register("ProductPage", elements);
    };

















    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ProductPage", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("ProductPage", element, query_all, modifier);
    };










    document.addEventListener('DOMContentLoaded', init);
}());
