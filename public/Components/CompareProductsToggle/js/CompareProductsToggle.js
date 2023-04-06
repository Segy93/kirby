(function () {
    "use strict"

    var elements = {

        button_change_compare:      ".compare_list__toggle_change",
        form:                       ".compare_toggle",
        product_single:             ".product_single__wrapper",
    };

    var init = function(event) {
        registerElements();
        initListeners();
        // getComparing();
    };

    var initListeners = function () {
        window.addEventListener("submit", submitAnything, false);
        // var buttons = getElement("button_change_compare", true);
        // for (var i = 0, l = buttons.length; i < l; i++) {
        //     buttons[i].addEventListener("change", clickChangeCompare, false);
        // }
        document.addEventListener("CompareList.Update", updatedCompare, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("CompareProductsToggle", elements);
    };


    var submitAnything = function(event) {
        var selector_form = getElementSelector("form");
        var form = event.target.closest(selector_form);

        if (form !== null) {
            event.preventDefault();
            submitForm(form);
        }
    };

    var updatedCompare = function(event) {
        var data = event.detail;
        var product_id = data.product_id;
        var in_compare = data.in_compare;

        var forms = getElement("form", true, product_id);
        forms.forEach(function(form) {
            var elements = form.elements;
            elements.product_id.value = product_id;
            elements.in_compare.checked = in_compare;
        });
    };

    /**
     * Poziva funkciju za izmenu podataka o korpi
     * @param   {Object}    dataset         data- podaci na tasteru
     */
    var submitForm = function(form) {
        var elements    = form.elements;
        var product_id  = parseInt(elements.product_id.value, 10);
        var in_compare = !elements.in_compare.checked;
        changeCompare(product_id, in_compare);
    };





    // var clickChangeCompare = function(event) {
    //     var id      = event.currentTarget.dataset.id;
    //     //var element = getElement("productSingle",undefined, id);
    //     var param   = event.currentTarget.checked;

    //     changeCompare(parseInt(id, 10), param);
    // };









    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("CompareProductsToggle", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("CompareProductsToggle", element, query_all, modifier);
    };



    // var render = function (data) {
    //     for (var i = 0, l = data.length; i < l; i++) {
    //         if (getElement("button_change_compare", false, data[i])) {
    //             var element = getElement("button_change_compare", false, data[i]);
    //             element.checked = true;
    //         }
    //     }
    // };






    var changeCompare = function (product_id, in_compare) {
        Kirby.Main.Ajax(
            "CompareProductsToggle",
            "changeCompare",
            {
                product_id: product_id,
                in_compare: in_compare,
            },  function() {
                document.dispatchEvent(new CustomEvent("CompareList.Update", {
                    detail: {
                        product_id: product_id,
                        in_compare: in_compare,
                    },
                }));
            }
        );
    };


    // var getComparing = function() {
    //     Kirby.Main.Ajax(
    //         "CompareProductsToggle",
    //         "getComparingProductsIds",
    //         {
    //         },
    //         render
    //     );
    // };



    document.addEventListener('DOMContentLoaded', init);
}());
