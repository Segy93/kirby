<form class = "settings_filters" method = "post" action = "category_controls">
    {!! $csrf_field !!}
    <div class = "settings_filters__search">
        <label
            class   = "common_landings__visually_hidden"
            for     = "settings_filters__search_field"
        >
            Pretraga
        </label>
        <input
            class       = "settings_filters__search_field"
            id          = "settings_filters__search_field"
            required    = "required"
            type        = "search"
            placeholder = "Pretraga kategorije"
        />
        <button class = "settings_filters__search_submit">Pretraži</button>
    </div>
    <label
        class   = "common_landings__visually_hidden"
        for     = "settings_filters_sort"
    >
        Sortiranje
    </label>
    <select
        class   = "settings_filters_sort"
        id      = "settings_filters_sort"
        title   = "Sortiraj podatke"
    >
        @foreach($sort as $key => $value)
            <option value="{{$key}}">{{$value}}</option>
        @endforeach
    </select>
    <div class = "settings_filters__limit">

        <input
            class = "settings_filters__limit_radio settings_filters__limit_radio--12 common_landings__visually_hidden"
            id="settings_filters__limit_radio--12"
            name = "settings_filters__limit_radio"
            type = "radio"
            value = "12" checked
        />
        <label
            class   = "settings_filters__limit_radio_label"
            for     = "settings_filters__limit_radio--12"
            title   = "Prikaži 12 proizvoda po strani"
        >
                12
        </label>
        <input
            class = "settings_filters__limit_radio settings_filters__limit_radio--24 common_landings__visually_hidden"
            id    = "settings_filters__limit_radio--24"
            name  = "settings_filters__limit_radio"
            type  = "radio"
            value = "24"
        />
        <label
            class   = "settings_filters__limit_radio_label"
            for     = "settings_filters__limit_radio--24"
            title   = "Prikaži 24 proizvod po strani"
        >
                24
        </label>
        <input
            class = "settings_filters__limit_radio settings_filters__limit_radio--48 common_landings__visually_hidden"
            id="settings_filters__limit_radio--48"
            name = "settings_filters__limit_radio"
            type = "radio"
            value = "48"
        />
        <label
            class   = "settings_filters__limit_radio_label"
            for     = "settings_filters__limit_radio--48"
            title   = "Prikaži 48 proizvoda po strani"
        >
                48
        </label>
    </div>
    <div class = "settings_filters__view">

        <input
            class       = "settings_filters__view_type_radio settings_filters__view_type_radio--grid common_landings__visually_hidden "
            id          = "settings_filters__view_type_radio--grid"
            name        = "settings_filters__view_type_radio"
            type        = "radio"
            value       = "grid"
            data-view   = "grid"
        />
        <label
            class   = "settings_filters__view_type_radio_label"
            for     = "settings_filters__view_type_radio--grid"
            title   = "Prikaži kao mrežu"
        >
            <svg
                class   = "settings_filters__view_type_radio_label_icon"
                version = "1.1"
                xmlns   = "http://www.w3.org/2000/svg"
                width   = "32"
                height  = "32"
                viewBox = "0 0 32 32"
            >
                <title>Pregled u mreži</title>
                <path d="M12 12h8v8h-8v-8z"></path>
                <path d="M0 0h8v8h-8v-8z"></path>
                <path d="M12 24h8v8h-8v-8z"></path>
                <path d="M0 12h8v8h-8v-8z"></path>
                <path d="M0 24h8v8h-8v-8z"></path>
                <path d="M24 0h8v8h-8v-8z"></path>
                <path d="M12 0h8v8h-8v-8z"></path>
                <path d="M24 12h8v8h-8v-8z"></path>
                <path d="M24 24h8v8h-8v-8z"></path>
            </svg>
        </label>

        <input
            class       = "settings_filters__view_type_radio settings_filters__view_type_radio--list common_landings__visually_hidden "
            id          = "settings_filters__view_type_radio--list"
            name        = "settings_filters__view_type_radio"
            type        = "radio"
            value       = "list"
            data-view   = "list"
            checked
        />

        <label
            class   = "settings_filters__view_type_radio_label"
            for     = "settings_filters__view_type_radio--list"
            title   = "Prikaži kao listu"
        >
            <svg
                class   = "settings_filters__view_type_radio_label_icon"
                version = "1.1"
                xmlns   = "http://www.w3.org/2000/svg"
                width   = "32"
                height  = "32"
                viewBox="0 0 32 32"
            >
                <title>Pregled u listi</title>
                <path d="M12 2h20v4h-20v-4zM12 14h20v4h-20v-4zM12 26h20v4h-20v-4zM0 4c0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4s-4-1.791-4-4zM0 16c0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4s-4-1.791-4-4zM0 28c0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.209-1.791 4-4 4s-4-1.791-4-4z"></path>
            </svg>
        </label>
    </div>
</form>
