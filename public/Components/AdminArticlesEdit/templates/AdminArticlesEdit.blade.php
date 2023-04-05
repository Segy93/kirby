<form
    action  = ""
    id      = "admin_article__edit_form"
    role    = "form"
>
    {!! $csrf_field !!}
    <input name="article_id" class = "admin_articles__edit_id" type="hidden" value = "{{$id}}" />

    <?php /*Naslov*/ ?>
    <label for="admin_articles__create_heading">Naslov</label>
    <input
        class       = "form-control"
        id          = "admin_articles__edit_heading"
        maxlength   = "63"
        name        = "heading"
        placeholder = "Naslov"
        required    = "required"
        type        = "text"
    />
    <?php /* Ime Autora
    @if ($permissions['article_update_author'])
        <label>Izaberite ime autora</label>
            <select
                class       = "form-control"
                id          = "admin_articles__edit_author"
                required    = "required"
                name        = "author"
            >
                <option value = "0">Nepotpisan</option>
            @foreach ($authors as $author)
                <option
                {{ $current === $author->id ?  "selected" : "" }}
                value="{{ $author->id }}"
                >
                {{ $author->name }}
                </option>
            @endforeach
            </select>
    @endif */  ?>

    <?php /*Tekst*/ ?>
    <label for="admin_articles__edit_text">Tekst</label>
    <textarea
        class       = "form-control admin_articles__edit_text"
        id          = "admin_articles__edit_text"
        maxlength   = "63"
        name        = "text"
        placeholder = "Tekst"
    >
    </textarea>

    <?php /*Isecak*/ ?>
    <label for="admin_articles__create_excerpt">Isečak</label>
    <textarea
        class       = "form-control"
        id          = "admin_articles__edit_excerpt"
        maxlength   = "63"
        name        = "excerpt"
        placeholder = "Isečak"
    ></textarea>

    <button
        autofocus    = "autofocus"
        class        = "btn btn-default"
        data-dismiss = "modal"
        type         = "button"
    >
        Odustani
    </button>

    <input
        class       = "btn btn-primary"
        id          = "admin_articles__modal_edit__confirm"
        type        = "submit"
        value       = "Sačuvaj"
    />
</form>
