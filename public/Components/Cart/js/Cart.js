(function () {
    "use strict"

    var elements = {
        cart_list:      ".cart__list",
        button_delete:  ".cart__delete_item",
        form_delete:    ".cart__form_delete",
        form_update:    ".cart__form_update",
        item_single:    ".cart_single__wrapper",
        cart_quantity:  ".cart__change_quantity",
        label_total:    ".cart__total_price",
        update_submit:  ".cart_form__update_submit",
    };

    var templates = {
        main: function() {},
    };










    var init = function(event) {
        registerElements();
        initTemplates();
        initListeners();
    };

    var initListeners = function () {
        getElement("cart_list").addEventListener("submit", submitAnything, false);
        getElement("cart_list").addEventListener("change", changeAnything, false);
        document.addEventListener("Kirby.WishList.Cart.Added", fetchData, false);
    };

    var initTemplates = function() {
        templates.main = _.template(document.getElementById("cart_tmpl").innerHTML);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("Cart", elements);
    };



    var submitAnything = function(event) {
        var selector_form_delete = getElementSelector("form_delete");
        var selector_form_update = getElementSelector("form_update");
        var form_delete = event.target.closest(selector_form_delete);
        var form_update = event.target.closest(selector_form_update);

        if (form_delete !== null) {
            event.preventDefault();
            submitDelete(form_delete);
        } else if (form_update !== null){
            event.preventDefault();
            submitUpdate(form_update);
        }
    }

    var changeAnything = function(event) {
        var selector_quantity_change = getElementSelector("cart_quantity");
        var quantity_change = event.target.closest(selector_quantity_change);

        if (quantity_change !== null) {
            quantityBlurred(quantity_change);
        }
    }






    var submitDelete = function(form) {
        var id = parseInt(form.elements.id.value, 10);
        var selector_item = getElementSelector("item_single");
        var element = form.closest(selector_item);

        var data = form.dataset;
        var price = parseFloat(data.price, 10);
        var quantity = parseInt(form.elements.product_id_delete.dataset.quantity, 10);

        var reduce_price_by = -price * quantity;

        Swal.fire({
            title: 'Da li ste sigurni da želite da uklonite proizvod?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#b1003f',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ukloni',
            cancelButtonText: 'Odustani',
        }).then((result) => {
            if (result.value) {
                updatePrice(reduce_price_by);
                deleteCartItem(id);
                element.remove();
            }
        });

        //Proverava da li ima necega u korpi ako nema prazni div zbog dugmeta naruci koje nece biti obrisano kada se obrise poslednji proizvod
        if (getElement("button_delete") === null) {
            getElement("cart_list").innerHTML = "";
        }
    };

    var submitUpdate = function(form) {
        var product_id = parseInt(form.elements.product_id.value, 10);
        var quantity_new = parseInt(form.elements.quantity.value, 10);
        quantity_new = isNaN(quantity_new) ? 1 : quantity_new ;
        var form_delete = document.getElementsByName("product_id_delete");
        var data = form.dataset;
        var price = parseFloat(data.price, 10);
        var quantity_old = parseInt(data.quantity, 10);

        var price_difference = (quantity_new - quantity_old) * price;
        updatePrice(price_difference);
        changeCart(product_id, quantity_new);

        form.dataset.quantity = quantity_new;
        for(var i = 0; i < form_delete.length; i++) {
            if (product_id === parseInt(form_delete[i].value)) {
                form_delete[i].dataset.quantity = quantity_new;
            }
        }
    };

    var quantityBlurred = function(quantity_change) {
        var id = quantity_change.dataset.id;
        var button = getElement("update_submit", false, id);
        button.click();
    };










    var formatPrice = function(price) {
        if ("Intl" in window) {
            var options = {
                style: "currency",
                minimumFractionDigits: 2,
                currency: "RSD",
            };
            return new Intl.NumberFormat('sr-RS', options).format(price);
        } else {
            return price;
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
        return Kirby.Main.Dom.getElement("Cart", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("Cart", element, query_all, modifier);
    };

    var render = function(data) {
        var container = getElement("cart_list");
        var html = templates.main({
            cart: data,
        });

        container.innerHTML = html;
    };

    var fetchData = function() {
        Kirby.Main.Ajax(
            "Cart",
            "getUserCart",
            {},
            render
        );
    };

    var updatePrice = function(amount) {
        var label = getElement("label_total");
        var price_current = parseFloat(label.dataset.total, 10);
        var price_new = price_current + amount;

        label.dataset.total = price_new;
        label.textContent = formatPrice(price_new);
    };









    var deleteCartItem = function(id) {
        Kirby.Main.Ajax(
            "Cart",
            "deleteCartItem",
            {
                id: id,
            },
            function() {
                var event = new CustomEvent("Cart.Update");
                document.dispatchEvent(event);
            }
        );
    }

    var changeCart = function(product_id, quantity) {
        Kirby.Main.Ajax(
            "Cart",
            "changeCart",
            {
                product_id: product_id,
                quantity  : quantity,
            }
        );
    }



    document.addEventListener('DOMContentLoaded', init);
}());
