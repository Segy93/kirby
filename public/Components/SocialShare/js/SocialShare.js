(function () {
    "use strict";

    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        initListeners();
        SocialShareKit.init({
        	selector: '.social_share .ssk',
    	});
    };

    /**
     * inicalizuje osluskivace
     */
    var initListeners = function() {
    	window.addEventListener("keyup", keyUpLink, false);
    };

    /**
     * Da bi mogao da se otvori link na enter(keycode : 13).Fokus je na li tagu(tabindex), zato enter ne radi.
     * @param {Object} event 	keyaboard event
     */
    var keyUpLink =  function(event){
    	if (event.target.classList.contains("ssk") && event.which === 13)
    		event.target.firstChild.click();
    };





    document.addEventListener("DOMContentLoaded", init);
}());
