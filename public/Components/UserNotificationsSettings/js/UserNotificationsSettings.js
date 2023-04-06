(function () {
    "use strict"

    var config = {

    };

    var elements = {
        subscription_checkbox: ".notification_settings__subscription_checkbox",
        remove_device_button:  ".notification_settings__delete_device",
        device:                ".notification_settings__device",
        device_tbody:          ".notification_settings__device_tbody",
        allow_component:        ".notifications_allow__device",
    };

    var init = function(event) {
        registerElements();
        initListeners();

    };

    var initListeners = function () {
        var checkboxes = getElement('subscription_checkbox', true);
        for(var i = 0, l = checkboxes.length; i < l; i++) {
            var checkbox = checkboxes[i];
            checkbox.addEventListener("click", subscriptionClicked, false)
        }
        // var buttons = getElement('remove_device_button', true);
        // for(var i = 0, l = buttons.length; i < l; i++) {
        //     var button = buttons[i];
        //     button.addEventListener("click", removeDeviceClicked, false)
        // }

        window.addEventListener("click", clickAnything, false);

        document.addEventListener("Kirby.Notifications.Device.Added", deviceAdded, false);
        document.addEventListener("Kirby.Notifications.Hide.Prompt", hidePrompt, false);
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("UserNotificationsSettings", elements);
    };


    var clickAnything = function(event) {
        var remove_device_selector = getElementSelector("remove_device_button");
        var remove_device_button = event.target.closest(remove_device_selector);

        if (remove_device_button !== null) {
            event.preventDefault();
            removeDeviceClicked(remove_device_button);
        }
    };

    var hidePrompt = function () {
        var allow_element = getElement("allow_component");
        allow_element.classList.add("common_landings__visually_hidden"); 
    };

    var subscriptionClicked = function (event) {
        var element = event.currentTarget;
        var type_id = parseInt(element.dataset.type_id, 10);

        changeNotificationSubscription(type_id);
    };

    var removeDeviceClicked = function (element) {
        var endpoint_id = parseInt(element.dataset.endpoint_id, 10);

        removeEndpoint(endpoint_id);
    };

    var deviceAdded = function() {
        getEndpoints();
    };



    var render = function () {
    };

    var renderDevices = function (data, endpoint_id) {
        var element = getElement('device', false, endpoint_id);
        element.remove();
        var all_devices = getElement('device', true);
        if(all_devices.length === 0) {
            var allow_element = getElement("allow_component");
            allow_element.classList.remove("common_landings__visually_hidden");
        }
    };

    var renderUserDevices = function(data) {
        var body = getElement("device_tbody");
        var html = '';
        for(var i = 0, l = data.length; i < l; i++) {
            var endpoint = data[i];
            html += '<tr class = "notification_settings__device notification_settings__device--'+endpoint.id+'"><td>';
            html += endpoint.device;
            html += '</td><td><button class = "notification_settings__delete_device" type = "submit"';
            html += 'data-endpoint_id    = '+'"'+ endpoint.id +'"';
            html += '>Ukloni </button></td></tr>';
        }

        body.innerHTML = html;
        hidePrompt();
    };








    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("UserNotificationsSettings", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("UserNotificationsSettings", element, query_all, modifier);
    };



    var changeNotificationSubscription = function (type_id) {
        Kirby.Main.Ajax(
            "UserNotificationsSettings",
            "changeNotificationSubscription",
            {
                type_id: type_id,
            },
            render
        );
    };

    var getEndpoints = function () {
        Kirby.Main.Ajax(
            "UserNotificationsSettings",
            "getUserEndpoints",
            {
            },
            renderUserDevices
        );
    }

    var removeEndpoint = function (endpoint_id) {
        Kirby.Main.Ajax(
            "UserNotificationsSettings",
            "removeEndpoint",
            {
                endpoint_id: endpoint_id,
            },
            renderDevices,
            endpoint_id
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
