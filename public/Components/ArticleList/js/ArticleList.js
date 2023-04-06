(function () {
    "use strict";

    var config = {
        base_url:     Kirby._params.Columns.ArticleList.base_url,

        date_first:   null,
        date_last:    null,

        id_object:    Kirby._params.Columns.ArticleList.id_object,

        id_oldest:    Kirby._params.Columns.ArticleList.id_oldest,
        id_newest:    Kirby._params.Columns.ArticleList.id_newest,

        type:         Kirby._params.Columns.ArticleList.type,

        interrupt_animation: false, /*prekid animacije*/
    };

    var elements = {
        button_prev: ".article_list__arrow--left",
        button_next: ".article_list__arrow--right",
        wrapper:     "#article_list__content",
    };

    var templates = {
        main: function(){},
    };










    var init = function(event) {
        initDateFirst();
        initDateLast();
        registerElements();
        initTemplates();
        initListeners();
    };

    var initDateFirst = function() {
        var date_object = Kirby._params.Columns.ArticleList.date_first;
        if (date_object !== null) config.date_first = date_object.date;
    };

    var initDateLast = function() {
        var date_object = Kirby._params.Columns.ArticleList.date_last;
        if (date_object !== null) config.date_last = date_object.date;
    };

    var initListeners = function() {
        var button_prev = getElement("button_prev");
        if (button_prev !== null) button_prev.addEventListener("click", clickPrev, false);

        var button_next = getElement("button_next");
        if (button_next !== null) button_next.addEventListener("click", clickNext, false);


        window.addEventListener("keydown", keyDown, false);
        window.addEventListener("wheel", scroll, false);
        window.addEventListener("touchstart", touchStart, false);

        window.addEventListener("popstate", popState, false);
    };

    var initTemplates = function() {
        var html = document.getElementById("article_list__tmpl").innerHTML;
        templates.main = _.template(html);
    };

    var registerElements = function() {
        Kirby.Main.Dom.register("ArticleList", elements);
    };










    var clickPrev = function(event) {
        event.preventDefault();
        pushState(false);
        fetchData(false);
    };

    var clickNext = function(event) {
        event.preventDefault();
        pushState(true);
        fetchData(true);
    };

    var keyDown = function(event) {
        var e = event;
        var interruptOn = [9,32,33,34,35,38,40];
        if (interruptOn.indexOf(e.keyCode) !== -1) {
            config.interrupt_animation = true;
        }
    };

    var touchStart = function(event) {
        config.interrupt_animation = true;
    }

    var scroll = function(event) {
        config.interrupt_animation = true;
    };

    var popState = function(event) {
        var info = window.location.href
            .replace(/\/$/, "") // sklanja / na kraju
            .split("/") // deli na url segmente
            .pop() // uzima poslednji, gde su informacije za dohvatanje
            .split("%7C") // dohvata datum i smer
        ;

        if (info.length !== 2) {
            var date = toSQLTimeString();
            var direction = true;
        } else {
            var date = info[0];
            var direction = info[1].toUpperCase() === "NAPRED";
        }

        if (direction) config.date_last = date;
        else config.date_first = date;

        fetchData(direction);
    };










    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement(
            "ArticleList",
            element,
            query_all,
            modifier,
            parent
        );
    };

    var render = function(data, direction) {
        var wrapper = getElement("wrapper");
        var html = templates.main({
            "articles": data,
        });

        wrapper.innerHTML = html;
        var duration = 100;
        var element = wrapper;
        var to = element.offsetTop- 100;
        scrollTo(to, duration);



        var id_first = data.length > 0 ? data[0].id : null;
        var id_last = data.length > 0 ? data[data.length - 1].id : null;

        var class_name = "article_list__arrow--active";

        var method_first = id_first === config.id_newest ? "remove" : "add";
        getElement("button_prev").classList[method_first](class_name);

        var method_last = id_last === config.id_oldest ? "remove" : "add";
        getElement("button_next").classList[method_last](class_name);

        if (data.length > 0) {
            config.date_first = data[0].published_full;
            config.date_last = data[data.length - 1].published_full;
        }

        updateNavigation(direction);

    };

    var updateNavigation = function(direction) {
        var button_prev = getElement("button_prev");
        var button_next = getElement("button_next");

        var url_prev = config.base_url + config.date_first + "|Nazad";
        var url_next = config.base_url + config.date_last + "|Napred";

        button_prev.setAttribute("href", url_prev);
        button_next.setAttribute("href", url_next);
    };

    var scrollTo = function (to, duration) {
        config.interrupt_animation = false;
        var callback = function() {
            var current = window.scrollY;
            var step = Math.round((current - to) / duration);
            current -= step;
            window.scrollTo(0, current);
            if (current > to && config.interrupt_animation !== true) {
                requestAnimationFrame(callback);
            }
        };
        requestAnimationFrame(callback);
    };







    const toSQLTimeString = function(date, compensate) {
        if (typeof date         === "undefined") date       = new Date();
        if (typeof compensate   === "undefined") compensate = true;

        if (typeof compensate) {
            date.setMinutes(date.getMinutes() + date.getTimezoneOffset());
        }
        return date.toISOString().slice(0, 19).replace('T', ' ');
    };

    const pushState = function(direction) {
        if ("history" in window) {
            var url = config.base_url;
            url += direction ? config.date_last : config.date_first;
            url += "|";
            url += direction ? "Napred" : "Nazad";
            window.history.pushState({}, document.title, url);
        }
    };










    var fetchData = function(direction) {
        Kirby.Main.Ajax(
            "ArticleList",
            "fetchData",
            {
                direction: direction,
                date_start: direction ? config.date_last : config.date_first,
                id_object: config.id_object,
                type: config.type,
                with_author: true,
            },
            render,
            direction
        );
    };

    document.addEventListener("DOMContentLoaded", init);
}());
