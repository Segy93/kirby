(function () {
    "use strict";




    var sendAnalytics = function (type, event_category, action, label, transport = "xhr") {
        if(typeof transport === undefined) transport = "xhr";
        if ("ga" in window) {
            ga("send", {
                hitType:        type,
                eventCategory:  event_category,
                eventAction:    action,
                eventLabel:     label,
                transport:      transport
            });
        }
    }


    if (window.Monitor                 === undefined) window.Monitor = {};
    if (window.Monitor.Main            === undefined) window.Monitor.Main = {};
    if (window.Monitor.Main.Analytics  === undefined) window.Monitor.Main.Analytics = {};

    window.Monitor.Main.Analytics.send = sendAnalytics;
}());