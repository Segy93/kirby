(function () {
    "use strict"

    var elements = {

        button_cancel:      ".order_list__delete_item",
        button_confirm:     ".order_list__confirm_item",
        button_return:      ".order_list__return_item",
        order_wrapper:      ".order__wrapper",
        order_single:       ".order__single_wrapper",
        order_buttons:      ".order__table_cell__buttons",
    };

    var templates = {
        main: function() {},
    };












    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initListeners();
    };

    var initListeners = function() {
        getElement("order_wrapper").addEventListener("click", clickWithin, false);
    }

    var registerElements = function () {
        Kirby.Main.Dom.register("OrderList", elements);
    };

    var clickWithin = function() {
        var selector_cancel_button = getElementSelector("button_cancel");
        if (event.target.closest(selector_cancel_button) !== null) {
            var order_id = parseInt(event.target.closest(selector_cancel_button).dataset.orderId, 10);
            clickCancel(order_id);
        }

        var selector_confirm_button = getElementSelector("button_confirm");
        if (event.target.closest(selector_confirm_button) !== null) {
            var order_id = parseInt(event.target.closest(selector_confirm_button).dataset.orderId, 10);
            clickConfirm(order_id);
        }

        var selector_return_button = getElementSelector("button_return");
        if (event.target.closest(selector_return_button) !== null) {
            var order_id = parseInt(event.target.closest(selector_return_button).dataset.orderId, 10);
            clickReturnToCart(order_id);
        }
    }

    var clickCancel = function(order_id) {
        var element = getElement("order_single", undefined, order_id);
        var confirm = window.confirm("Da li ste sigurni da želite da otkažete narudžbinu?");

        if (confirm === true) {
            cancelOrder(order_id);
            element.remove();
        }

    };

    var clickReturnToCart = function (order_id) {
        var element = getElement("order_single", undefined, order_id);
        var confirm = window.confirm("Da li ste sigurni da želite da vratite narudžbinu u korpu?");
        
        if (confirm === true) {
            returnToCart(order_id);
            element.remove();
        }

    };


    var clickConfirm = function (order_id) {
        var element = getElement("order_buttons", undefined, order_id);
        var confirm = window.confirm("Da li ste sigurni da želite da potvrdite narudžbinu?");

        if (confirm === true) {
            confirmOrder(order_id);
            element.innerHTML = "Narudžbina je potvrđena!";
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
        return Kirby.Main.Dom.getElement("OrderList", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("OrderList", element, query_all, modifier);
    };


    var onReturnToCart = function () {
        document.dispatchEvent(new CustomEvent("Cart.Update"));
        document.dispatchEvent(new CustomEvent("OrderList.Changed"));
        
        var buttons_field  = getElement("order_buttons");
        var body    = getElement("order_wrapper");

        if (buttons_field === null) {
            body.innerHTML = "";
        }
    };

    var onOrderUpdate = function () {
        document.dispatchEvent(new CustomEvent("OrderList.Changed"));

        var buttons_field  = getElement("order_buttons");
        var body    = getElement("order_wrapper");

        if (buttons_field === null) {
            body.innerHTML = "";
        }
    };


    var cancelOrder = function(id) {
        Kirby.Main.Ajax(
            "UserProfile",
            "cancelOrder",
            {
                id: id,
            },
            onOrderUpdate
        );
    };

    var returnToCart = function(order_id) {
        Kirby.Main.Ajax(
            "OrderList",
            "returnToCart",
            {
                order_id: order_id,
            },
            onReturnToCart
        );
    };

    var confirmOrder = function(order_id) {
        Kirby.Main.Ajax(
            "OrderList",
            "confirmOrder",
            {
                order_id: order_id,
            },
            onOrderUpdate
        );
    };








    document.addEventListener('DOMContentLoaded', init, false);
}());
