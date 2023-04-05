@if($is_logged)
    <div class = "notification_allow__wrapper">
        <h4 class = "notification_allow__header">Odobrite notifikacije na ovom uređaju</h4>
        <input
            type    = "button"
            id      = "notification_allow__approve"
            class   = "notification_allow__button notification_allow__approve"
            value   = "Odobri"
        />
        <input
            type    = "button"
            id      = "notification_allow__reject"
            class   = "notification_allow__button notification_allow__reject"
            value   = "Ne, hvala"
        />
        <input
            type    = "hidden"
            id      = "notification_allow__key"
            value   = "{{$push_public__key}}"
        />
    </div>
@endif