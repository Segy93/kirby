<div class = "notifications_settings">
    <?php $current_device_subscribed = false; ?>
        <table class = "notifications_settings__table">
            <thead>
                <th class = "notifications_settings__table_head">Uređaji</th>
                <th class = "notifications_settings__table_head notifications_settings__table_head--right">Brisanje</th>
            </thead>
            <tbody class = "notification_settings__device_tbody">
                @foreach ($user_endpoints as $endpoint)
                    <tr class = "notification_settings__device notification_settings__device--{{$endpoint->id}}">
                        <td class = "notifications_settings__table_cell">
                            <?php 
                                if ($current_device === $endpoint->device) {
                                    $current_device_subscribed = true;
                                } 
                            ?>
                            {{$endpoint->device}}
                        </td>
                        <td class = "notifications_settings__table_cell notifications_settings__table_cell--right">
                            <button
                                class               = "notification_settings__delete_device"
                                type                = "submit"
                                data-endpoint_id    = "{{$endpoint->id}}"
                            >
                                Ukloni 
                            </button>
                        </td>
                    </tr>
                @endforeach
                @if(empty($user_endpoints))
                    <tr>
                        <td colspan = "2">Nemate uneti uredjaj</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <table class = "notifications_settings__table">
            <thead>
                <th class = "notifications_settings__table_head">Tipovi notifikacija</th>
                <th class = "notifications_settings__table_head notifications_settings__table_head--right">Uključi/Isključi</th>
            </thead>
            <tbody class = "notification_settings__device_tbody">
                @foreach ($notification_types as $type)
                <tr>
                    <td class = "notifications_settings__table_cell">
                        <label for = "notification_settings__subscription_checkbox__{{$type->id}}" >{{$type->name}}</label>
                    </td>
                    <td class = "notifications_settings__table_cell notifications_settings__table_cell--right">
                        <input
                            class           = "notification_settings__subscription_checkbox"
                            id              = "notification_settings__subscription_checkbox__{{$type->id}}"
                            type            = "checkbox"
                            data-type_id    = "{{$type->id}}"
                            @if (in_array($type->id, $user_subscriptions))
                                checked
                            @endif
                        >
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    <div class = "notifications_allow__device {{$current_device_subscribed === false ? '' : 'common_landings__visually_hidden'}}">
        {!! $allow_component->renderHTML() !!}
    </div>
</div>