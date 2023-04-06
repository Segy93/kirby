(function () {
    "use strict";

    var elements = {
        wrapper:    ".product_category__products",
        more:       ".product_category__load_more",
    };

    var config = {
        last: "",
        sort: "",
        append: false,
        limit: 12,
        view: "list",
        last_printed: 0,
        total_printed_list: 0,
        total_printed_grid: 0,
        banners_count: 0,
        append_count: 0,
        page: 0,
    };

    var templates = {
        main: function () { },
        grid: function () { },
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
        // window.addEventListener("popstate", popState,       false);
        getElement("more").addEventListener("click", clickLoadMore, false);
        document.addEventListener("Router.Url.Changed", getData, false);
        document.addEventListener("Kirby.CategoryPageView", changeView, false);
    };

    var changeView = function (event) {
        var type = event.data;
        config.view = type;
        var grid = getElement("wrapper", false, "grid");
        var list = getElement("wrapper", false, "list");

        if (type === "grid") {
            grid.classList.remove("common_landings__display_none");
            list.classList.add("common_landings__display_none");
        } else {
            list.classList.remove("common_landings__display_none");
            grid.classList.add("common_landings__display_none");
        }
    }


    var getData = function () {
        var url = window.location.search;
        url = url.substr(url.length - 1) === "&" ? url.slice(0, -1) : url;
        url = getUrlVars(url);
        var category_id = parseInt(getElement("wrapper").dataset.categoryId, 10);
        var category = getElement("wrapper").dataset.category;
        if (url.limit) {
            config.limit = parseInt(url.limit, 10);
        }

        if (url.strana) {
            config.page = parseInt(url.strana, 10);
        }

        var currentSort = getSort();

        if (url.sort !== currentSort) {
            setLast("");
        }

        if (url.sort) {
            setSort(url.sort);
        }

        var last = "";

        if (getAppend()) {
            last = getLast();
        }
        fetchData(url, category_id, last, config.page, category);
    };

    var getUrlVars = function (url) {
        var hash;
        var myJson = {};
        var hashes = url.slice(url.indexOf("?") + 1).split("&");
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split("=");
            var name = decodeURI(hash[0]).split(" ").join("_");
            var value = decodeURI(hash[1]);
            myJson[name] = value;
        }
        return myJson;
    };


    var setLast = function (last) {
        config.last = last;
    };

    var getLast = function () {
        return config.last;
    };

    var setSort = function (sort) {
        config.sort = sort;
    };

    var getSort = function () {
        return config.sort;
    };

    var setAppend = function (append) {
        if (append === true) config.append_count++;
        config.append = append;
    };



    var getAppend = function () {
        return config.append;
    };

    var isOnSale = function () {
        return getElement("wrapper").dataset.sale === "sale";
    };

    var clickLoadMore = function (event) {
        var id = parseInt(event.currentTarget.dataset.id, 10);
        event.preventDefault();
        // setID(id);
        setAppend(true);
        getData();
        config.page++;
        var ev = new CustomEvent("Kirby.Filters.More.Clicked");
        document.dispatchEvent(ev);
    };

    /**
     * Inicijalizacija sablona koje komponenta koristi
     * @return  {Object}                    Kirby.AdminArticles.List
     */
    var initTemplates = function () {
        templates.main = _.template(document.getElementById("product_category__tmpl_list").innerHTML);
        templates.grid = _.template(document.getElementById("product_category__tmpl_grid").innerHTML);
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function () {
        Kirby.Main.Dom.register("ProductCategory", elements);
    };



    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz objekata
     */
    var getElement = function (element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("ProductCategory", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz objekata
     */
    var getElementSelector = function (element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("ProductCategory", element, query_all, modifier);
    };

    var render = function (data) {
        var wrapper_grid = getElement("wrapper", false, "grid");
        var wrapper_list = getElement("wrapper", false, "list");
        var banners = data.banners;
        var products = data.products;
        var cart = data.cart;
        var wishlist = data.wishlist;
        var compare = data.compare;
        var more = products.length > config.limit;
        var sort = getSort();
        var last = "";

        config.banners_count = data.banners.length;
        if (products.length > 0) {
            if (sort === "" || sort === "artid_asc" || sort === "artid_desc") {
                last = products[products.length - 1].artid;
            } else if (sort === "price_desc" || sort === "price_asc") {
                last = products[products.length - 1].price_discount;
            } else if (sort === "name_desc" || sort === "name_asc") {
                last = products[products.length - 1].name;
            }

            setLast(last);

            // dohvatio sam jedan vise zbog paginacije pa ga sad izbacujem ako ih ima jos
            if (more === true) products.pop();
            var html_list = templates.main({
                banners:        banners,
                cart:           cart,
                wishlist:       wishlist,
                products:       products,
                compare:        compare,
                last_printed:   config.last_printed,
                total_printed_list:  config.total_printed_list
            });

            var html_grid = templates.grid({
                banners:    banners,
                cart:       cart,
                wishlist:   wishlist,
                products:   products,
                compare:    compare,
                last_printed:   config.last_printed,
                total_printed_grid:  config.total_printed_grid
            });

            config.last_printed += (config.limit / 5 )- 1;
            if (config.last_printed > banners.length - 1) config.last_printed = 0;
            config.total_printed_list += config.limit;
            config.total_printed_grid += config.limit;

            if (getAppend() === true) {
                var scroll_before = window.scrollY;
                wrapper_list.insertAdjacentHTML('beforeend', html_list);
                wrapper_grid.insertAdjacentHTML('beforeend', html_grid);
                window.scrollTo(0, scroll_before);
            } else {
                wrapper_list.innerHTML = html_list;
                wrapper_grid.innerHTML = html_grid;
                getElement("more").dataset.id = products[products.length - 1].id;
            }
        } else {
            wrapper_list.innerHTML = "<p class = 'product_category__no_items'>Nema proizvoda za zadate filtere</p>";
            wrapper_grid.innerHTML = "<p class = 'product_category__no_items'>Nema proizvoda za zadate filtere</p>";
        }

        var button_more = getElement("more");
        button_more.classList[more === true ? "remove" : "add"]("common_landings__visually_hidden");
        setAppend(false);
    };


    var fetchData = function (url, category_id, last, page, category) {
        if(getAppend() === false) {
            config.banners_count = 0;
            config.append_count  = 0;
        }
        Kirby.Main.Ajax(
            "ProductCategory",
            "fetchData",
            {
                url:            url,
                category_id:    category_id,
                last:           last,
                sale:           isOnSale(),
                append:         getAppend(),
                banners_count:  config.banners_count,
                append_count:   config.append_count,
                page:           page,
                category:       category
            },
            render
        );
    };










    document.addEventListener("DOMContentLoaded", init);
}());
