(function () {
    "use strict";

    var config = {
        startMin:       0,
        startMax:       0,
        slider:         {},
        slider_names:   [],
    };

    var elements = {
        filter_checkbox:            ".product_filter__checkbox",
        filter_checkbox_checked:    ".product_filter__checkbox:checked",
        filter:                     ".product_filter",
        slider:                     "#product_filter__range_slider",
        price_input:                ".product_filter__price_input",
        wrapper_single:             ".product_filter__wrapper_single",
    };










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function() {
        registerElements();
        initSlider();
        initListeners();
        filterChanged();
    };

    /**
     * inicalizuje osluskivace
     */
    var initListeners = function() {
        // window.addEventListener("popstate", popState,       false);
        var filters = getElement("filter_checkbox", true);
        for (var i = 0; i < filters.length; i++) {
            filters[i].addEventListener("change",  filterChanged,  false);
        }

        document.addEventListener("Settings.Filter.Changed", filterChanged, false);
        for (var j = 0; j < config.slider_names.length; j++) {
            var name = config.slider_names[j];
            config.slider[name].subscribe("stop", filterChanged);
            config.slider[name].subscribe("moving", sliderMoved);
        }


        var input_min = getElement("price_input", false, "min");
        var input_max = getElement("price_input", false, "max");
        input_max.addEventListener("change",  priceInputChanged,  false);
        input_min.addEventListener("change",  priceInputChanged,  false);

    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Kirby.Main.Dom.register("ProductFilter", elements);
    };

    var initSlider = function() {
        var slider = getElement("slider", true);
        var input_min = getElement("price_input", false, "min");
        var input_max = getElement("price_input", false, "max");
        for (var i = 0, l = slider.length; i < l; i++) {
            var min = parseInt(slider[i].dataset.min, 10);
            var max = parseInt(slider[i].dataset.max, 10);

            var setMin = parseInt(slider[i].dataset.setMin, 10);
            var setMax = parseInt(slider[i].dataset.setMax, 10);

            if (isNaN(setMin)) {
                config.startMin = min;
                input_min.value = min;
            } else {
                config.startMin = setMin;
                input_min.value = setMin;
            }

            if (isNaN(setMax)) {
                config.startMax = max;
                input_max.value = max;
            } else {
                config.startMax = setMax;
                input_max.value = setMax;
            }

            // noUiSlider.create(slider[i], {
            //     start:      [config.startMin, config.startMax],
            //     connect:    true,
            //     step:       10,
            //     range:      {
            //         min: min,
            //         max: max
            //     }
            // });
            if (slider[i].dataset.name === "price_retail") {
                config.price_min = min;
                config.price_max = max;
                //config.startMax  = (config.startMax / max) * 100;
                //config.startMin  = config.startMin === 0 ? 0 : (config.startMin / max) * 100;
            }
            var options = {
                isDate:     false,
                // min:        min,
                // max:        max,
                start:      config.startMin,
                end:        config.startMax,
                overlap:    true
            };
            config.slider[slider[i].dataset.name] = new Slider(slider[i], options);
            config.slider_names.push(slider[i].dataset.name);
        }
    };

    var priceInputChanged = function(event) {

        var input_min = getElement("price_input", false, "min");
        var input_max = getElement("price_input", false, "max");
        var price_min = parseInt(input_min.value, 10);
        var price_max = parseInt(input_max.value, 10);

        if (price_min > price_max) {
            var changed_element = event.currentTarget;
            var modifier        = changed_element.dataset.type;

            if (modifier === 'min') {
                input_max.value = input_min.value;
                input_max.min   = input_min.value;
                price_max = parseInt(input_max.value, 10);
            } else {
                input_min.value = input_max.value;
                input_min.max   = input_max.value;
                price_min = parseInt(input_min.value, 10);
            }
        }


        var price_total_max = config.price_max;

        var minp = 0;
        var maxp = 10;
        var slope = 0.5;
        var padding = 1;
        var scale = maxp - minp;

        var value_max = Math.exp(slope * scale + padding);
        // var slider_right  = Math.round(parseFloat(slider_values.right));
        // var max = slider_right / scale;
        // var max_value = Math.exp(slope * max + padding);
        //var select_right = Math.round(config.price_max * (max_value / value_max));
        var max_value   = (price_max / config.price_max) * value_max;
        var max         = Math.log(Math.pow(max_value, 1/slope)) - 2 * padding;
        var right       = (max * scale);


        // var slider_left   = (parseFloat(slider_values.left));
        // var min_value = Math.exp(slope * min + padding);
        // var select_left = (config.price_max * (min_value / value_max));
        // var min = slider_left / scale;

        var min_value   = (price_min / config.price_max) * value_max;
        var min         = Math.log(Math.pow(min_value, 1/slope)) - 2 * padding;
        var left       = (min * scale);

        // var selected_min = Math.ceil(parseFloat(input_min.value));
        // var selected_max = Math.ceil(parseFloat(input_max.value));

        // var left = Math.floor(selected_min / range * 100);
        // var right = Math.ceil(selected_max / range * 100);

        config.slider.price_retail.move({
            left: left,
            right: right,
        });

        filterChanged();
    };

    var sliderMoved = function() {

        var input_min = getElement("price_input", false, "min");
        var input_max = getElement("price_input", false, "max");

        var slider_values = config.slider["price_retail"].getInfo();
        var minp = 0; // minimalna vrednost loga
        var maxp = 10; // maksimalna vrednost loga
        var slope = 0.5;
        var padding = 1;
        var scale = maxp - minp;

        var value_max = Math.exp(slope * scale + padding);

        var slider_left   = (parseFloat(slider_values.left));
        var slider_right  = (parseFloat(slider_values.right));

        var left_scaled = slider_left / scale;
        var right_scaled = slider_right / scale;

        var left_value = Math.exp(slope * left_scaled + padding);
        var select_left = (config.price_max * (left_value / value_max));
        var right_value = Math.exp(slope * right_scaled + padding);
        var select_right = (config.price_max * (right_value / value_max));

        input_min.value = Math.round(select_left);
        input_max.value = Math.round(select_right);
    };

    var filterChanged = function(event) {
        var data        = [];
        var refresh_data = true;
        if (event) {
            refresh_data = event.data !== undefined ? event.data : true;
        }
        var filters = getElement("wrapper_single", true);
        for (var i = 0, lo = filters.length; i < lo; i++) {
            var filter          = filters[i];
            var label           = filter.dataset.label;
            var machine_name    = filter.dataset.machine_name;
            var type            = filter.dataset.type;
            var checkboxes      = getElement("filter_checkbox_checked", true, undefined, filter);
            var values          = [];
            if (type === "checkbox") {
                for (var j = 0, li = checkboxes.length; j < li; j++) {
                    values.push(checkboxes[j].value);
                }
            }
            if (type === "slider") {
                var slider_values = config.slider[machine_name].getInfo();
                var min           = (config.price_max / 100) * Math.round(parseFloat(slider_values.left));
                var max           = (config.price_max / 100) * Math.round(parseFloat(slider_values.right));
                if (machine_name === "price_retail") {
                    var input_min = getElement("price_input", false, "min");
                    var input_max = getElement("price_input", false, "max");

                    max = input_max.value;
                    min = input_min.value;
                }

                var formatted      = "Min:"+min+"-Max:"+max;
                values.push(formatted);
            }
            if (values.length > 0) {
                data.push({
                    title:  label,
                    values: values,
                });
            }
        }
        var state_type = "product_filters";
        Kirby.Main.Router.stateChanged(data, state_type, refresh_data);
    };



    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz objekata
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("ProductFilter", element, query_all, modifier, parent);
    };










    document.addEventListener("DOMContentLoaded", init);
}());
