(function () {
    "use strict"

    var elements = {

        button_cancel:      ".order_details__delete_item",
        button_confirm:     ".order_details__confirm_item",
        button_return:      ".order_details__return_item",
        order_details:      ".order__details",
        order_buttons:      ".order_details__info_table_row--buttons",
        order_status:       ".order_details__info_table_cell--status",
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
        // Ukoliko je narudzbina potvrdjena nemoguce ju je otkazati, 
        // potvrditi ponovo ili vratiti u korpu samo pise tekst da je narudzbina potvrdjena i tasteri ne postoje
        if (getElement("button_cancel") !== null) {
            getElement("button_cancel").addEventListener("click", clickCancel, false);
            getElement("button_confirm").addEventListener("click", clickConfirm, false);
            getElement("button_return").addEventListener("click", clickReturnToCart, false);
        }
    };

    var registerElements = function () {
        Monitor.Main.DOM.register("OrderDetails", elements);
    };










    var clickCancel = function() {
        var confirm = window.confirm("Da li ste sigurni da želite da otkažete narudžbinu?");
        var order_id = parseInt(getElement("button_cancel").dataset.orderId, 10);

        if (confirm === true) {
            cancelOrder(order_id);
        }
    };

    var clickReturnToCart = function () {
        var confirm = window.confirm("Da li ste sigurni da želite da vratite narudžbinu u korpu?");
        var order_id = parseInt(getElement("button_return").dataset.orderId, 10);

        if (confirm === true) {
            returnToCart(order_id);
        }
    };

    var clickConfirm = function () {
        var confirm = window.confirm("Da li ste sigurni da želite da potvrdite narudžbinu?");
        var order_id = parseInt(getElement("button_confirm").dataset.orderId, 10);

        if (confirm === true) {
            confirmOrder(order_id);
            getElement("order_buttons").remove();
            getElement("order_status").innerHTML = "potvrđeno";
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
        return Monitor.Main.DOM.getElement("OrderDetails", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("OrderDetails", element, query_all, modifier);
    };










    var onReturnToCart = function () {
        document.dispatchEvent(new CustomEvent("Cart.Update"));
        document.dispatchEvent(new CustomEvent("OrderDetails.Changed"));
        window.location = "../../korpa";
    };

    var onOrderUpdate = function () {
        document.dispatchEvent(new CustomEvent("OrderDetails.Changed"));
    };

    var onOrderCancel = function () {
        document.dispatchEvent(new CustomEvent("OrderDetails.Changed"));
        window.location = "../";
    };

    var cancelOrder = function(id) {
        Monitor.Main.Ajax(
            "UserProfile",
            "cancelOrder",
            {
                id: id,
            },
            onOrderCancel
        );
    };

    var returnToCart = function(order_id) {
        Monitor.Main.Ajax(
            "OrderList",
            "returnToCart",
            {
                order_id: order_id,
            },
            onReturnToCart
        );
    };

    var confirmOrder = function(order_id) {
        Monitor.Main.Ajax(
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
