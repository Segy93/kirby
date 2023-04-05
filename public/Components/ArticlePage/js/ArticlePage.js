(function () {
    "use strict";

    var config = {
    };

    var elements = {
        iframe: ".article_page__body iframe",
        wrapper: "#article_page__body",
    };










    var init = function(event) {
        registerElements();
        initListeners();
        //initEmbed();
        calculateEmbedHeights();
    };

    var initEmbed = function() {
        var wrapper = getElement("wrapper");
        Monitor.Main.Shortcodes.initEmbeds(wrapper);
    };

    var initListeners = function() {
        window.addEventListener("optimizedResize", resizeWindow.bind(this), false);
    };

    var registerElements = function() {
        Monitor.Main.DOM.register("ArticlePage", elements);
    };










    var resizeWindow = function(event) {
        calculateEmbedHeights();
    };










    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ArticlePage", element, query_all, modifier, parent);
    };

    var calculateEmbedHeights = function() {
        var iframes = getElement("iframe", true);

        for (var i = 0, l = iframes.length; i < l; i++) {
            var iframe = iframes[i];

            if (iframe.dataset.aspectRatio === undefined) {
                var original_width  = iframe.width ? parseInt(iframe.width, 10) : 16;
                var original_height = iframe.height ? parseInt(iframe.height, 10) : 9;
                var aspect_ratio    = original_width / original_height;

                iframe.dataset.aspectRatio = aspect_ratio.toFixed(3);
                iframe.style.minHeight = "0";
            } else {
                var aspect_ratio = iframe.dataset.aspectRatio;
            }

            var width           = iframe.offsetWidth;
            iframe.style.height = width / aspect_ratio + "px";
        }
    };






















    document.addEventListener("DOMContentLoaded", init);
}());
