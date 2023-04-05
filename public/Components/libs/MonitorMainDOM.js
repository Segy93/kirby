(function () {
	"use strict";

    if (window.Monitor === undefined) {
        window.Monitor = {};
    }

    if (window.Monitor.Main === undefined) {
        window.Monitor.Main = {};
    }

    var elements = {};

    window.Monitor.Main.DOM = {
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
