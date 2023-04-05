(function () {
    "use strict"

    var elements = {
        items_number : "#menu_wishlist__number",
    };

    var init = function(event) {
        registerElements();
        initListeners();

    };

    var initListeners = function () {
        document.addEventListener("WishList.Update", wishListUpdated, false);
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("MainMenuWishList", elements);
    };









    var wishListUpdated = function () {
        fetchData();
    }












    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("MainMenuWishList", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("MainMenuWishList", element, query_all, modifier);
    };










    var renderData = function (data) {
        var element = getElement("items_number");
        element.innerHTML = data;
    }









    var fetchData = function (){
        Monitor.Main.Ajax(
            "MainMenuWishList",
            "fetchData",
            {},
            renderData
        );
    };






    document.addEventListener('DOMContentLoaded', init);
}());
