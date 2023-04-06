(function () {
    "use strict";

    var elements = {
        checkbox:   ".main_menu__submenu_checkbox",
    };

    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function () {
        getElement("checkbox", true).forEach(function(checkbox) {
            checkbox.addEventListener("focus", menuFocused, false);
        });

        getElement("checkbox", true).forEach(function(checkbox) {
            checkbox.addEventListener("blur", menuBlurred, false);
        });

        getElement("checkbox", true).forEach(function(checkbox) {
            checkbox.addEventListener("change", menuChecked, false);
        });
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("MainMenu", elements);
    };



    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element    Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all  Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier   BEM modifier za selektor
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("MainMenu", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje imena klase za selektor datog elements
     * (selektor bez tacke na pocetku)
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {String}    modifier        BEM modifier za selektor
     * @returns {String}                    Ime klase
     */
    var getElementClassName = function(element, modifier) {
        return getElementSelector(element, modifier).substring(1);
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {String}    modifier  BEM modifier za selektor
    */
    var getElementSelector = function(element, modifier) {
        return Kirby.Main.Dom.getElementSelector("MainMenu", element, modifier);
    };

    var menuFocused = function(event) {
        var class_name = getElementClassName("checkbox", "focused");
        event.currentTarget.labels[0].classList.add(class_name);
    };

    var menuBlurred = function(event) {
        var class_name = getElementClassName("checkbox", "focused");
        event.currentTarget.labels[0].classList.remove(class_name);
    };

    var menuChecked = function(event) {
        getElement("checkbox", true).forEach(function(checkbox){
            if (checkbox !== event.currentTarget) {
                checkbox.checked = false;
            }
        });
    };










    document.addEventListener("DOMContentLoaded", init);
}());
