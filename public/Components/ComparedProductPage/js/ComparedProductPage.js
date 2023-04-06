(function () {
    "use strict"

    var elements = {

        button_delete:         ".compared_product_page__delete_item",
        compare_wrapper:       ".compared_product_page__details",
        wrapper:               ".compared_product_page__wrapper",
    };

    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function () {

        var all_delete_buttons = getElement("button_delete", true);

        for (var i = 0, l = all_delete_buttons.length; i < l; i++) {
            all_delete_buttons[i].addEventListener("click", clickDelete, false);
        }
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("Compare", elements);
    };









    var clickDelete = function(event) {
        var id = event.currentTarget.dataset.id;
        var elements = getElement("compare_wrapper", true, id);
        event.preventDefault();
        deleteCompare(parseInt(id, 10));

        elements.forEach(function(element) {
            element.remove();
        });
        if (getElement("compare_wrapper") === null) {
            getElement("wrapper").innerHTML = "";
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
        return Kirby.Main.Dom.getElement("Compare", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("Compare", element, query_all, modifier);
    };









    var deleteCompare = function (id) {
        Kirby.Main.Ajax(
            "ComparedProductPage",
            "removeCompare",
            {
                id: id,
            },  function() {
                var event    = new CustomEvent("CompareList.Update");
                document.dispatchEvent(event);
            }
        );
    }



    document.addEventListener('DOMContentLoaded', init);
}());
