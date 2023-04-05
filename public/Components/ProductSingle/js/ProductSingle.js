(function () {
    "use strict"

    var elements = {
        productSingle:            ".product_single__wrapper",
        gallery_picture:          ".product_single__image",
    };

    var init = function(event) {
        registerElements();
        initListeners();

    };

    var initListeners = function () {
        var element = getElement('gallery_picture');
        if(element !== null) {
            element.addEventListener('click', galleryPictureClicked, false);
        }
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("ProductSingle", elements);
    };

    var galleryPictureClicked = function(event) {
        event.preventDefault();
        var event   = new CustomEvent("Monitor.Gallery.Clicked");
        document.dispatchEvent(event);
    };












    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ProductSingle", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("ProductSingle", element, query_all, modifier);
    };












    document.addEventListener('DOMContentLoaded', init);
}());
