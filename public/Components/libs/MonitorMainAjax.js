(function () {
    "use strict";

    var config = {
        batch: new Date().getTime(),

        callbacks: {
            success:    {},
            failure:    {},
        },
        ctx:                {},
        queue:              {},
        timeout_id:         null,
        timeout_delay:      100,

        timestamps: {},
    };

    config.callbacks.success[config.batch] = [];
    config.callbacks.failure[config.batch] = [];
    config.ctx[config.batch] = [];
    config.queue[config.batch] = [];

    var callCallbacks = function(data, batch, which, callback_no) {
        if(callback_no !== undefined) {
            var callbacks = config.callbacks[which][batch][callback_no];
            var ctx = config.ctx[batch][callback_no];
            if (data !== undefined && data !== undefined && data.ok) {
                var is_latest = data.timestamp === config.timestamps[data.key];
                callbacks(data.data, ctx, is_latest);
            } else if (data.ok === false) {
                var is_latest = data.timestamp === config.timestamps[data.key];
                callbacks(data.error, ctx, is_latest);
            }       
        }

    };

    var callCallbacksSuccess = function(data, batch, callback_no) {
        callCallbacks(data, batch, "success", callback_no);
    };

    var callCallbacksFailure = function(data, batch, callback_no) {
        callCallbacks(data, batch, "failure", callback_no);
    };

    var makeRequestRegular = function () {
        var batch = config.batch;
        if (config.queue[batch].length > 0) {
            var csrf_field = document.getElementById("csrf-token");
            var csrf_token = csrf_field ? csrf_field.content : "";

            var request = new XMLHttpRequest();
            request.open("POST", "/ajax/data", true);
            request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            request.setRequestHeader("X-CSRF-TOKEN", csrf_token);
            request.onreadystatechange = function() {
                if (request.readyState === 4) {
                    if (request.status === 200) {
                        var response = JSON.parse(request.responseText);
                        var batch    = response.batch;
                        for(var i = 0, l = response.data.length; i < l; i++) {
                            if (response.data[i].ok) {
                                callCallbacksSuccess(response.data[i], batch, i);
                            } else {
                                callCallbacksFailure(response.data[i], batch, i);
                            }
                        }

                    } else {
                        try {
                            callCallbacksFailure(JSON.parse(request.responseText));
                        } catch (e) {
                            callCallbacksFailure();
                        }
                    }

                    config.callbacks.success[batch] = [];
                    config.callbacks.failure[batch] = [];
                    config.ctx[batch] = [];
                    config.queue[batch] = [];
                }
            };

            request.send(JSON.stringify({
                queue: config.queue[batch],
                batch: config.batch,
            }));
            config.batch = new Date().getTime();
            config.callbacks.success[config.batch] = [];
            config.callbacks.failure[config.batch] = [];
            config.ctx[config.batch] = [];
            config.queue[config.batch] = [];
        }
    };

    var makeRequestRaw = function (data, callback, ctx) {
        var request = new XMLHttpRequest();
        var csrf_field = document.getElementById("csrf-token");
        var csrf_token = csrf_field ? csrf_field.content : "";
        request.open("POST", "/ajax/data_raw");
        request.setRequestHeader("X-CSRF-TOKEN", csrf_token);
        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                var mime = this.getResponseHeader("content-type");
                if (mime === "text/csv") { // U pitanju je CSV fajl
                    // Kreiramo blob od odgovora
                    var blob = new Blob([this.response], {type: 'text/csv'});

                    // Kreiramo privremeni link, sakriven
                    var a = document.createElement("a");
                    a.style = "display: none";
                    document.body.appendChild(a);

                    // Kreiramo privremeni URL za dohvacene podatke, kacimo to na link i klikcemo na njega
                    var url = window.URL.createObjectURL(blob);
                    a.href = url;
                    a.download = 'Korisnici.csv';
                    a.click();

                    window.URL.revokeObjectURL(url);
                } else {
                    callback(JSON.parse(request.responseText), ctx);
                }
            }
        };
        request.send(data);
    };

    var makeRequestBeacon = function(data) {
        var csrf_field = document.getElementById("csrf-token");
        var csrf_token = csrf_field ? csrf_field.content : "";
        data.append("X-CSRF-TOKEN", csrf_token);
        navigator.sendBeacon("/ajax/data_raw", data);
    };


    var errorDispatch =  function(data) {
        var event = new CustomEvent("Monitor.Error");
        event.error_code = data.code;
        event.error_message = data.message;
        document.dispatchEvent(event);
    };

    if ("Monitor" in window === false) {
        window.Monitor = {};
    }

    if (window.Monitor.Main === undefined) {
        window.Monitor.Main = {};
    }

    /**
     * Dodaje u red za AJAX pozive
     * Na svakih n milisekundi se salju svi zahtevi zajedno
     * @param   {String}    component_name      Ime komponente kojoj saljemo zahtev
     * @param   {String}    component_method    Metoda komponente koju pozivamo
     * @param   {*}         params              Parametri koji ce biti poslati pri pozivu metode
     * @param   {*}         callbacks           callback funkcij(e)
     *                                          Kada je niz, prvi element ce biti success callback, a drugi failure callback
     *                                          Kada je objekat, moze da ima kljuceve success i failure
     *                                          Kada je funkcija, ona se koristi kao success callback
     * @param   {*}         ctx                 2. argument za callback funkciju
     * @param   {Boolean}   unprocessed         Da li saljemo sirov, neobradjeni zahtev (primer, za fajlove neophodno)
     * @param   {Boolean}   beacon              Da li slati kao XHR ili Beacon
     */
    window.Monitor.Main.Ajax = function (
        component_name,
        component_method,
        params,
        callbacks,
        ctx,
        unprocessed,
        beacon
    ) {
        var callback_success = function() {};
        var callback_failure = function() {};


        if (typeof params === "undefined") {
            params = {};
        }

        if (Array.isArray(callbacks)) {
            if (callbacks.length > 0 && typeof callbacks[0] === "function") {
                callback_success = callbacks[0];
            }

            if (callbacks.length > 1 && typeof callbacks[1] === "function") {
                callback_failure = callbacks[1];
            }
        } else if (typeof callbacks === "function") {
             callback_success = callbacks;
            callback_failure  = errorDispatch;
        } else if (typeof callbacks !== "undefined") {
            if (typeof callbacks.success !== "undefined") {
                callback_success = callbacks.success
            }

            if (typeof callbacks.failure !== "undefined") {
                callback_failure = callbacks.failure
            } else {
                callback_failure = errorDispatch;
            }
        }

        if (typeof ctx === "undefined") {
            ctx = {};
        }

        var key = component_name + "--" + component_method;
        var timestamp = new Date().getTime();
        config.timestamps[key] = timestamp;

        if (unprocessed || beacon) {
            var data = new FormData();
            for (var key in params) {
                if (params.hasOwnProperty(key)) {
                    var value = params[key];

                    if (Array.isArray(value)) {
                        for (var i = 0, l = value.length; i < l; i++) {
                            data.append(key + "[]", value[i]);
                        }
                    } else {
                        data.append(key, value);
                    }
                }
            }

            data.append("component_name", component_name);
            data.append("component_method", component_method);
            data.append("timestamp", timestamp.toString());

            if (beacon && "sendBeacon" in navigator) {
                makeRequestBeacon(data);
            } else {
                makeRequestRaw(data, callback_success, ctx);
            }
        } else {
            var batch = config.batch;
            config.callbacks.success[batch].push(callback_success);
            config.callbacks.failure[batch].push(callback_failure);
            config.ctx[batch].push(ctx);

            var queue = unprocessed ? config.queue_raw : config.queue[batch];
            queue.push({
                "name":             component_name,
                "component_method": component_method,
                "timestamp":        timestamp,
                "params":           params,
            });

            if (config.timeout_id) clearTimeout(config.timeout_id);

            config.timeout_id = setTimeout(makeRequestRegular, config.timeout_delay);
        }
    };

    window.Monitor.Main.Beacon = function(component_name, component_method, params) {
        Monitor.Main.Ajax(
            component_name,
            component_method,
            params,
            undefined,
            undefined,
            undefined,
            true
        );
    };
}());
