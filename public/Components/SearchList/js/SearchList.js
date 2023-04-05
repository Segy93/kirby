(function () {
    "use strict";

    var elements = {
        more:       ".search_list__load_more",
        wrapper:    ".search_list",
    };

    var config = {
        last: "",
        append: false,
        limit: 12,
        view: "list",
        append_count: 0,
    };

    var templates = {
        main: function () { },
    };

    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function () {
        registerElements();
        initTemplates();
        initListeners();
        getData();
    };

    /**
     * inicalizuje osluskivace
     */
    var initListeners = function () {
        getElement("more").addEventListener("click", clickLoadMore, false);
    };

    var getData = function () {
        var last = "";
        var search = getElement("more").dataset.search;
        if (getAppend()) {
            last = getLast();
        }
        fetchData(last, search);
    };

    var setLast = function (last) {
        config.last = last;
    };

    var getLast = function () {
        return config.last;
    };

    var setAppend = function (append) {
        if (append === true) config.append_count++;
        config.append = append;
    };



    var getAppend = function () {
        return config.append;
    };

    var clickLoadMore = function (event) {
        var id = parseInt(event.currentTarget.dataset.id, 10);
        event.preventDefault();
        setAppend(true);
        getData();
    };

    /**
     * Inicijalizacija sablona koje komponenta koristi
     * @return  {Object}                    Monitor.AdminArticles.List
     */
    var initTemplates = function () {
        templates.main = _.template(document.getElementById("search_list__tmpl").innerHTML);
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function () {
        Monitor.Main.DOM.register("SearchList", elements);
    };



    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz objekata
     */
    var getElement = function (element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("SearchList", element, query_all, modifier, parent);
    };

    var render = function (data) {
        var wrapper_list = getElement("wrapper", false);
        var products = data.products;
        var cart = data.cart;
        var wishlist = data.wishlist;
        var compare = data.compare;
        var more = products.length > config.limit;

        if (products.length > 0) {
            var last = products[products.length - 1].artid;
            setLast(last);
            if (more === true) products.pop();
            var html_list = templates.main({
                cart:           cart,
                wishlist:       wishlist,
                products:       products,
                compare:        compare,
            });

            if (getAppend() === true) {
                wrapper_list.innerHTML += html_list;
            } else {
                wrapper_list.innerHTML = html_list;
                getElement("more").dataset.id = products[products.length - 1].id;
            }
        }

        var button_more = getElement("more");
        button_more.classList[more === true ? "remove" : "add"]("common_landings__visually_hidden");
        setAppend(false);
    };


    var fetchData = function (last, search) {
        if (getAppend() === false) {
            config.append_count  = 0;
        }
        Monitor.Main.Ajax(
            "SearchList",
            "fetchData",
            {
                last:           last,
                search:         search,
            },
            render
        );
    };










    document.addEventListener("DOMContentLoaded", init);
}());
