(function () {
    "use strict"

    var elements = {
        form: '.cookies_info__form',
        wrapper: '.cookies_info',
    };

    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function () {
        var form = getElement('form');
        if (form !== null) {
            form.addEventListener("submit", submitForm, false);
        }
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("CookiesInfo", elements);
    };


    /**
     * Poziva funkciju za izmenu podataka o korpi
     * @param   {Object}    dataset         data- podaci na tasteru
     */
    var submitForm = function(event) {
        event.preventDefault();
        cookieAccepted();
    };









    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("CookiesInfo", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("CookiesInfo", element, query_all, modifier);
    };


    var render = function(data) {
        if (data) {
            getElement('wrapper').remove();
        }
    };



    var cookieAccepted = function () {
        Kirby.Main.Ajax(
            "CookiesInfo",
            "cookieAccepted",
            {
            },
            render
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
