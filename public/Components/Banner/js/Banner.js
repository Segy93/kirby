(function () {
    "use strict"

    var config = {
        slide_index: 0,
        nr_banners: 0,
        interval_id: null,
    }

    var elements = {
        banner: ".banner__image",
        dot:    ".slider_nav__dot_button",
        slider: ".slider_slides",
        slides: ".slider_slide__container",
        prev:   ".slider_nav__prev",
        next:   ".slider_nav__next",
    };







    /**
     * Inicijalizacija komponente
     */
    var init = function() {
        registerElements();
        initListeners();
        initSlideObserver();
    };

    /**
     * Inicijalizacija osluskivaca
     *
     */
    var initListeners = function() {
        const dots = getElement("dot", true);
        const prev = getElement("prev");
        const next = getElement("next");
        dots.forEach(function(dot) {
            dot.addEventListener("click", dotClicked, false);
        });
        window.addEventListener("click", clickWithin, false);
        if (prev) {
            prev.addEventListener("click", prevClicked, false);
        }
        if (next) {
            next.addEventListener("click", nextClicked, false);
        }
        window.addEventListener("load", autoSlider, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("Order", elements);
    };

    /**
     * Klik unutar prozora
     *
     * @param   {MouseEvent}  event
     *
     */
    var clickWithin = function(event) {
        var banner_selector = getElementSelector("banner");
       // event.preventDefault();
        if (event.target.closest(banner_selector) !== null) {
            var banner_id = parseInt(event.target.closest(banner_selector).dataset.id, 10);

            clickBanner(banner_id);
        }
    };

    var initSlideObserver = function() {
        const wrapper = getElement("slider");
        if (wrapper) {
            config.nr_banners = parseInt(getElement("slider").dataset.count, 10);
            var slides = getElement("slides", true);
            var slider = getElement("slider");
            if ("IntersectionObserver" in window) {
                var end = new IntersectionObserver(
                    onScrollObserver, {
                        threshold: 0.9
                });

                var start = new IntersectionObserver(
                    onSlideObserver, {
                        threshold: 0.9
                });
                var obs = new IntersectionObserver(
                    onIntersectSlide, {
                        root: slider,
                        threshold: 0.9
                });

                slides.forEach(function(slide) {
                    obs.observe(slide);
                });

                start.observe(slider);

                end.observe(slider);
            }
        }
    };

    var onIntersectSlide = function(entries) {
        if (config.interval_id !== null) {
            clearInterval(config.interval_id);
            config.interval_id = null;
            autoSlider();
        }
        entries.forEach(function(entry) {
            if (entry.intersectionRatio !== 0) {
                config.slide_index = parseInt(entry.target.dataset.index, 10);
                var dots = getElement("dot", true);
                dots.forEach(function(dot) {
                    dot.classList.remove("slider_nav__dot_label_marked");
                });
                getElement("dot", false, config.slide_index).classList.add("slider_nav__dot_label_marked");
            }
        });
    };

    var onScrollObserver = function() {
        clearInterval(config.interval_id);
        config.interval_id = null;
    };

    var onSlideObserver = function() {
        if (config.interval_id === null) {
            autoSlider();
        }
    };

    /**
     * Klik na tackicu
     *
     * @param   {MouseEvent}  event  tackica na koju je kliknuto
     *
     */
    var dotClicked = function(event) {
        config.slide_index = parseInt(event.currentTarget.dataset.index, 10);
        showSlide(config.slide_index);
    };

    var prevClicked = function() {
        var max_index = config.nr_banners - 1;
        if (config.slide_index > 0) {
            config.slide_index--;
        } else {
            config.slide_index = max_index;
        }
        showSlide(config.slide_index);
    };

    /**
     * Klik za sledeci slajd
     *
     */
    var nextClicked = function() {
        var max_index = config.nr_banners - 1;
        if (config.slide_index < max_index) {
            config.slide_index++;
        } else {
            config.slide_index = 0;
        }
        showSlide(config.slide_index);
    };

    /**
     * Automatsko menjanje slajda na 3 sekunde
     *
     */
    var autoSlider = function() {
        if (config.interval_id === null) {
            config.interval_id = setInterval(nextClicked, 3000);
        }
    };

    /**
     * Prikaz odgovarajuceg slajda
     *
     * @param   {Number}  index  Koji slajd treba da prikaze
     *
     */
    var showSlide = function(index) {
        var elem = getElement("slider");
        if (elem) {
            var width = elem.getBoundingClientRect().width;
            if ("scrollTo" in elem) {
                elem.scrollTo({ left: width * index, behavior: 'smooth'});
            } else {
                elem.scrollLeft = width * index;
            }
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
        return Kirby.Main.Dom.getElement("Order", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("Order", element, query_all, modifier);
    };

    /**
     * Upisivanje klika na baner
     *
     * @param   {Number}  id  Id banera
     *
     */
    var clickBanner = function (id) {
        Kirby.Main.Beacon(
            "Banner",
            "clickBanner",
            {
                id: id,
            }
        );
    };








    document.addEventListener('DOMContentLoaded', init);
}());
