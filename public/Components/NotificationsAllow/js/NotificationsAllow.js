(function () {
    "use strict";

    var elements = {
        approve:      "#notification_allow__approve",
        key_element:  "#notification_allow__key",
        wrapper:      ".notification_allow__wrapper",
        reject:       "#notification_allow__reject",
    };

    var config = {
        device_subscribed: Kirby._params.device_subscribed,
    }

    var init = function(event) {
        registerElements();
        initListeners();
        shouldHide();
    };

    var initListeners = function () {
        //document.addEventListener("Cart.Update", cartUpdated, false);
        var approve = getElement("approve");
        if (approve !== null) {
            getElement("approve").addEventListener("click", allowNotifications, false);
        }
        var reject = getElement("reject");
        if (reject !== null) {
            getElement("reject").addEventListener("click", hidePrompt, false);
        }
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("NotificationsAllow", elements);
    };


    var shouldHide = function () {
        if ("Notification" in window) {
            if (Notification.permission === 'granted' && config.device_subscribed) {
                hidePrompt();
            }
        } else {
            hidePrompt();
        }

    };

    var hidePrompt = function (event) {
        var event   = new CustomEvent("Kirby.Notifications.Hide.Prompt");
        document.dispatchEvent(event);
    };

    var urlB64ToUint8Array = function (base64String) {
        var padding = '='.repeat((4 - base64String.length % 4) % 4);
        var base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/');

        var rawData = window.atob(base64);
        var outputArray = new Uint8Array(rawData.length);

        for (var i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    var allowNotifications = function () {
        if ("Notification" in window ) {
            var key_element = getElement('key_element');
            var applicationServerKey = urlB64ToUint8Array(key_element.value);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.ready.then(function(reg) {
                reg.pushManager.subscribe({
                    userVisibleOnly: true,
                    applicationServerKey: applicationServerKey
                }).then(function(sub) {
                    var p256h = btoa(String.fromCharCode.apply(null, new Uint8Array(sub.getKey('p256dh'))));
                    var auth  = btoa(String.fromCharCode.apply(null, new Uint8Array(sub.getKey('auth'))));
                    notificationsAllowed(sub.endpoint, p256h, auth);
    
                    var event   = new CustomEvent("Kirby.Notifications.Device.Added");
                    document.dispatchEvent(event);
                }).catch(function(e) {
                    if ("Notification" in window && Notification.permission === 'denied') {
                        console.warn('Permission for notifications was denied');
                    } else {
                        console.error('Unable to subscribe to push', e);
                    }
                });
                })
            }
        };
        }














    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("NotificationsAllow", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("NotificationsAllow", element, query_all, modifier);
    };

    var render = function (data) {
        if (data) {
            //hidePrompt();
        }
    };

    function notificationsAllowed (endpoint, p256dh, auth) {
        Kirby.Main.Ajax(
            "NotificationsAllow",
            "notificationsAllowed",
            {
                endpoint: endpoint,
                p256dh: p256dh,
                auth: auth
            },
            render
        );
    }

    document.addEventListener("DOMContentLoaded", init);
}());
