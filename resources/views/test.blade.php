
{!!$csrf_meta!!}
<input type = "button" id = "enable_notifications" class = "enable_notifications" value = "enable"/>
<input type = "button" id = "send_notifications" class = "send_notifications" value = "send"/>
<script src = "Components/libs/MonitorMainAjax.js" ></script>
<script  type="text/javascript" nonce = "{{$_SESSION['token']}}">
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('service-worker.js').then(function(reg) {
            console.log('Service Worker Registered!', reg);

            reg.pushManager.getSubscription().then(function(sub) {
            if(sub === null) {
                console.log('Nije registrovan za notifikacije');
            } else {
                console.log('registrovan je ', sub);
            }
            });
        });
    }
</script>


<script >
    var enable = document.getElementById('enable_notifications');
    var send = document.getElementById('send_notifications');
    enable.addEventListener("click", enableNotificationsPopup);
    send.addEventListener("click", sendNotification);
    var applicationServerPublicKey = 'BLsbf-T4w7-QVFGDUJrIJEU_ZfdUd2DTELkfCainmyQkvdRszg-oCym2TiVMGzNdd9gOSeUhXoWg7M5Ts7iBFhI';

    function urlB64ToUint8Array(base64String) {
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

    var applicationServerKey = urlB64ToUint8Array(applicationServerPublicKey);
    function enableNotificationsPopup(event) {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.ready.then(function(reg) {

            reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: applicationServerKey
            }).then(function(sub) {
                console.log('Endpoint URL: ', sub.endpoint);
                console.log('sub keys: ', sub);
            }).catch(function(e) {
                if (Notification.permission === 'denied') {
                console.warn('Permission for notifications was denied');
                } else {
                console.error('Unable to subscribe to push', e);
                }
            });
            })
        }
    }

    function sendNotification (event) {
        Monitor.Main.Ajax(
            "test",
            "sendNotification",
            {
                applicationServerPublicKey: applicationServerPublicKey
            }
        );
    }
</script>

