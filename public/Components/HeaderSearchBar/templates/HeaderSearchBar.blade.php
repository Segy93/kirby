<form method = "GET" action = "pretraga" class = "header_search__bar">
    {!! $csrf_field !!}
    <label
        class   = "common_landings__visually_hidden"
        for     = "header_search__bar_input"
    >
        Pretraga
    </label>
    <input
        class       = "header_search__bar_input common_landings__remove_appearence"
        id          = "header_search__bar_input"
        placeholder = "Pretraga"
        role        = "search"
        name        = "query"
        required    = "required"
        type        = "search"
    />

    <input
        class = "header_search__submit"
        id = "header_search__submit"
        label="PRETRAŽI"
        type = "submit"
        value = "PRETRAŽI"
    />
</form>
