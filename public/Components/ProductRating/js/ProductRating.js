(function () {
    "use strict";

    var config = {
        current_rating: 0,
    };

    var elements = {
        rating_list:      ".product_rating__list",
        rating_field:     ".product_rating__value",
        rating_item:      ".product_rating__star",
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

        window.addEventListener("click", clickAnything, false);
        // var rating_item = getElement("rating_item", true);
        // for (var i = 0, l = rating_item.length; i < l; i++) {
        //     rating_item[i].addEventListener("click", ratingClicked);
        // }
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Monitor.Main.DOM.register("ProductRating", elements);
    };


    var clickAnything = function(event) {
        var rating_item_selector = getElementSelector("rating_item");
        var rating_item = event.target.closest(rating_item_selector);

        if (rating_item !== null) {
            event.preventDefault();
            ratingClicked(rating_item);
        }
    };







    var ratingClicked = function(rating_item) {
        var element     = rating_item;
        var product_id  = parseInt(element.dataset.product_id, 10);
        var rating      = parseInt(element.dataset.rating, 10);
        // if ((event.type === "click" && event.clientX !== 0 && event.clientY !== 0) || event.keyCode === 13) {
        ratingAdd(rating, product_id);
        // }
    };









    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ProductRating", element, query_all, modifier, parent);
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("ProductRating", element, query_all, modifier);
    };



    var render = function(data, product_id, is_latest) {
        if (is_latest) {
            var selector_parent = getElementSelector("rating_list");
            var element = getElement("rating_field", false, product_id);
            getElement("rating_item", true, undefined, element.closest(selector_parent)).forEach(function(star) {
                var star_rating = parseInt(star.dataset.rating, 10);
                var method = star_rating <= data ? "add" : "remove";
                star.classList[method]("product_rating__star--selected");
            });
            element.innerHTML = data;
        }
    };




    var ratingAdd = function(rating, product_id) {
        Monitor.Main.Ajax(
            "ProductRating",
            "ratingAdd",
            {
                rating:     rating,
                product_id: product_id,
            },
            render,
            product_id
        );
    };








    document.addEventListener("DOMContentLoaded", init);
}());
