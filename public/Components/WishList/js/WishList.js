(function () {
    "use strict"

    var elements = {

        button_cart:       ".wishlist__cart_add",
        button_delete:      ".wishlist__delete_item",
        wish_wrapper:       ".wishlist__single_wrapper",
        wishlist:           ".wishlist__wrapper"
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

        var all_cart_buttons = getElement("button_cart", true);

        for (var i = 0, l = all_cart_buttons.length; i < l; i++) {
            all_cart_buttons[i].addEventListener("click", clickCart, false);
        }
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("WishList", elements);
    };









    var clickDelete = function(event) {
        var id = event.currentTarget.dataset.id;
        var element = getElement("wish_wrapper", undefined, id);
        event.preventDefault();
        deleteWish(parseInt(id, 10));

        element.remove();
        if (getElement("button_delete") === null) {
            getElement("wishlist").innerHTML = "";
        }
    };

    var clickCart = function(event) {
        var id         = event.currentTarget.dataset.id;
        var product_id = event.currentTarget.dataset.product_id;
        var element = getElement("wish_wrapper", undefined, id);
        event.preventDefault();
        addToCart(parseInt(product_id, 10));
        deleteWish(parseInt(id, 10));

        element.remove();
        
        if (getElement("button_delete") === null) {
            getElement("wishlist").innerHTML = "";
        }

        document.dispatchEvent(new CustomEvent("Kirby.WishList.Cart.Added"));
    };










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("WishList", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("WishList", element, query_all, modifier);
    };

    var addedToCart = function (data) {
            var event    = new CustomEvent("WishList.Update");
            document.dispatchEvent(event);
            var event_cart    = new CustomEvent("Cart.Update");
            document.dispatchEvent(event_cart);
    };









    var deleteWish = function (id) {
        Kirby.Main.Ajax(
            "WishList",
            "removeWish",
            {
                id: id,
            },  function() {
                var event    = new CustomEvent("WishList.Update");
                document.dispatchEvent(event);
            }
        );
    };

    var addToCart = function (id) {
        Kirby.Main.Ajax(
            "WishList",
            "addToCart",
            {
                id: id,
            }, 
            addedToCart
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
