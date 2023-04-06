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


    if (window.Kirby                 === undefined) window.Kirby = {};
    if (window.Kirby.Main            === undefined) window.Kirby.Main = {};
    if (window.Kirby.Main.Analytics  === undefined) window.Kirby.Main.Analytics = {};

    window.Kirby.Main.Analytics.send = sendAnalytics;
}());