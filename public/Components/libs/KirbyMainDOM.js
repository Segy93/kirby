(function () {
	"use strict";

    if (window.Kirby === undefined) {
        window.Kirby = {};
    }

    if (window.Kirby.Main === undefined) {
        window.Kirby.Main = {};
    }

    var elements = {};

    window.Kirby.Main.Dom = {
    	getElement: function(component, element, query_all, modifier, parent) {
            if (parent === undefined) parent = document;
            else if (typeof parent === "string") parent = this.getElement(component, parent);

    		var selector = this.getElementSelector(component, element, modifier);
    		if (query_all === true) {
    			return parent.querySelectorAll(selector)
			} else {
				return parent.querySelector(selector);
			}
    	},

	    getElementSelector: function(component, selector, modifier) {
	    	var selector = elements[component][selector];
	    	return modifier === undefined ? selector : selector + "--" + modifier;
	    },

    	register: function(component, selectors) {
    		elements[component] = selectors;
    	}
    };
}());
