(function () {
    "use strict";

    var config   = {
        lightbox: null,
    };

    var elements = {
        thumbs: ".product_gallery__thumbnail",
    };
    var options = {
        preload: 			true,
        carousel: 			true,
        animation: 			400,
        nextOnClick: 		true,
        responsive: 		true,
        keyControls: 		true,
        dimensions:         false,
        nextImg:            'Components/ProductLightboxGallery/img/right-arrow.svg',
        prevImg:            'Components/ProductLightboxGallery/img/left-arrow.svg',
    };

    var init = function() {
        var lightbox = new Lightbox();
        lightbox.load(options);
        config.lightbox = lightbox;
        registerElements();
        initListeners();
    };



    var initListeners = function () {
        var thumbs = getElement("thumbs", true);
        for(var i = 0, l = thumbs.length; i < l; i ++) {
            var thumb = thumbs[i];
            thumb.addEventListener('keydown', thumbEnterd, false);
        }
        document.addEventListener("Kirby.Gallery.Clicked", galleryClicked, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("ProductLightBoxGallery", elements);
    };

    var galleryClicked = function () {
        var thumbs = getElement('thumbs', true);
        thumbs[0].click();
    };

    var thumbEnterd = function (event) {
        if(event.keyCode === 13 || event.keyCode === 32) {
            var element  = event.currentTarget;
            config.lightbox.open(element.dataset.jslghtbx);
        }
    }







    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("ProductLightBoxGallery", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("ProductLightBoxGallery", element, query_all, modifier);
    };

    document.addEventListener("DOMContentLoaded", init);
}());