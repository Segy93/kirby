(function () {
    "use strict";

    var elements = {

        button_change_wish:         ".wish_list__toggle_change",
        form:                       ".atom_wishlist_toggle",
        product_single:             ".product_single__wrapper",
    };

    var init = function(event) {
        registerElements();
        initListeners();
        // getWishlist();
    };

    var initListeners = function () {
        window.addEventListener("submit", submitAnything, false);
        // var buttons = getElement("button_change_wish", true);
        // for (var i = 0, l = buttons.length; i < l; i++) {
        //     buttons[i].addEventListener("change", clickChangeWish, false);
        // }

        document.addEventListener("WishList.Update", updatedWishlist, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("AtomWishListToggle", elements);
    };







    var submitAnything = function(event) {
        var selector_form = getElementSelector("form");
        var form = event.target.closest(selector_form);

        if (form !== null) {
            event.preventDefault();
            submitForm(form);
        }
    }


    var updatedWishlist = function(event) {
        var data = event.detail;
        var product_id = data.product_id;
        var in_wishlist = data.in_wishlist;

        var forms = getElement("form", true, product_id);
        forms.forEach(function(form) {
            var elements = form.elements;
            elements.product_id.value = product_id;
            elements.in_wishlist.checked = in_wishlist;
        });
    };

    var submitForm = function(form) {
        var elements    = form.elements;
        var product_id  = parseInt(elements.product_id.value, 10);
        var in_wishlist = !elements.in_wishlist.checked;
        changeWish(product_id, in_wishlist);
    };







    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("AtomWishListToggle", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AtomWishListToggle", element, query_all, modifier);
    };

    // var render = function (data) {
    //     for (var i = 0, l = data.length; i < l; i++) {
    //         if (getElement("button_change_wish", false, data[i])) {
    //             var element = getElement("button_change_wish", false, data[i]);
    //             element.checked = true;
    //         }
    //     }
    // };









    var changeWish = function (product_id, in_wishlist) {
        Kirby.Main.Ajax(
            "AtomWishListToggle",
            "changeWish",
            {
                in_wishlist: in_wishlist,
                product_id:  product_id,
            },  function() {
                document.dispatchEvent(new CustomEvent("WishList.Update", {
                    detail: {
                        product_id: product_id,
                        in_wishlist: in_wishlist,
                    },
                }));
            }
        );
    };



    // var getWishlist = function() {
    //     Kirby.Main.Ajax(
    //         "AtomWishListToggle",
    //         "getUsersWishlist",
    //         {
    //         },
    //         render
    //     );
    // };



    document.addEventListener('DOMContentLoaded', init);
}());
