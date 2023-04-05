(function () {
    "use strict";

    var elements = {
        button_change_cart:     ".atom_cart_toggle__change",
        form:                   ".atom_cart_toggle",
        cart_quantity:          ".cart_toggle__quantity",
        in_cart_text:           ".cart_toggle__in_cart",
    };

    var init = function() {
        registerElements();
        initListeners();
        // getUserCart();
    };

    var initListeners = function () {
        window.addEventListener("submit", submitAnything, false);
        window.addEventListener("click", clickWithin, false);
        // var buttons = getElement("button_change_cart", true);

        // for (var i = 0, l = buttons.length; i < l; i++) {
        //     buttons[i].addEventListener("click", clickChangeCart, false);
        // }

        document.addEventListener("Cart.Update", updatedCart, false);
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("AtomCartToggle", elements);
    };










    var submitAnything = function(event) {
        var selector_form = getElementSelector("form");
        var form = event.target.closest(selector_form);

        if (form !== null) {
            event.preventDefault();
            submitForm(form);
        }
    };

    var clickWithin = function() {
        var selector_cancel_button = getElementSelector("button_change_cart");
        if (event.target.closest(selector_cancel_button) !== null) {
            clickChangeCart();
        }
    }

    var updatedCart = function(event) {
        var data = event.detail;
        var product_id = data.product_id;
        var quantity = data.quantity;
        var in_cart = data.in_cart;

        var forms = getElement("form", true, product_id);
        forms.forEach(function(form) {
            var elements = form.elements;
            elements.quantity.dataset.quantity = quantity;
            elements.product_id.value = product_id;
            elements.quantity.value = quantity;
            elements.in_cart.checked = in_cart;
        });
    };

    /**
     * Poziva funkciju za izmenu podataka o korpi
     * @param   {Object}    dataset         data- podaci na tasteru
     */
    var submitForm = function(form) {
        var elements    = form.elements;
        var product_id  = parseInt(elements.product_id.value, 10);
        var quantity    = 1;
        var in_cart = !elements.in_cart.checked;
        changeCart(product_id, quantity, in_cart);
    };

    var clickChangeCart = function() {
        Swal.fire({
            title: 'Proizvod je dodat u Vašu korpu!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Idi na korpu',
            cancelButtonText: 'Nastavi kupovinu',
        }).then((result) => {
            if (result.value) {
                window.location.href = "/korpa";
            }
        });
    };








    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("AtomCartToggle", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("AtomCartToggle", element, query_all, modifier);
    };

    // var render = function (data) {
    //     var forms = getElement("forms", true);
    // };










    var changeCart = function (product_id, quantity, in_cart) {
        Monitor.Main.Ajax(
            "AtomCartToggle",
            "changeCart",
            {
                product_id: product_id,
                quantity:   quantity,
                in_cart:    in_cart,
            },
            function() {
                document.dispatchEvent(new CustomEvent("Cart.Update", {
                    detail: {
                        product_id: product_id,
                        quantity: quantity,
                        in_cart: in_cart,
                    },
                }));
            }
        );
    };

    // var getUserCart = function () {
    //     Monitor.Main.Ajax(
    //         "AtomCartToggle",
    //         "getUserCart",
    //         {
    //         },
    //         render
    //     );
    // };








    document.addEventListener('DOMContentLoaded', init);
}());
