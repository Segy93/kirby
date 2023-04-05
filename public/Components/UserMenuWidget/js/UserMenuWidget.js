(function () {
    "use strict"

    var elements = {
        notification:      ".user_menu__notification_dot",
    };

    var init = function(event) {
        registerElements();
        initListeners();

    };

    var initListeners = function () {
        document.addEventListener("Order.Changed", changeNotification, false);
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("UserMenuWidget", elements);
    };




    var render = function (notify) {
        if (!notify) {
            var element = getElement("notification");
            element.remove();
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
        return Monitor.Main.DOM.getElement("UserMenuWidget", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("UserMenuWidget", element, query_all, modifier);
    };



    var changeNotification = function () {
        Monitor.Main.Ajax(
            "UserMenuWidget",
            "checkNotification",
            {},
            render
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
