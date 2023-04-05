<?php /*Forma za kreiranje clanaka*/ ?>
@if ($permissions ['article_create'])
    <form enctype="multipart/formdata" id="admin_articles__create_form" method="post">
        {!! $csrf_field !!}
        <?php /* Naslov */ ?>
        <label for="admin_articles__create_heading">Naslov</label>
        <input
            class       = "form-control"
            id          = "admin_articles__create_heading"
            maxlength   = "63"
            name        = "heading"
            placeholder = "Naslov"
            required    = "required"
            type        = "text"
        />

        <?php /* Slika */ ?>
        <label for="admin_articles__create_image">Slika</label>
        <input
            class       = "form-control"
            id          = "admin_articles__create_image"
            name        = "image"
            required    = "required"
            type        = "file"
        />

        <?php /* Datum */ ?>
        <label for="admin_articles__create_date">Datum</label>
        <input
            class       = "form-control"
            id          = "admin_articles__create_date"
            name        = "date"
            type        = "datetime-local"
        />

        <?php /* Kategorija */ ?>
        <label for="admin_articles__create_categories">Izaberite kategoriju</label>
        <select
            class       = "form-control"
            id          = "admin_articles__create_categories"
            required    = "required"
        >
            <option value = "">Izaberi...</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <?php /* Autori */ ?>

        <label for="admin_articles__create_authors">Izaberite autora</label>
        <select
            class       = "form-control"
            id          = "admin_aricles__create_author"
            required    = "required"
            name        = "author"
        >
            <option value = "0"> Nepotpisan </option>
            @foreach ($authors as $author)
                <option value="{{ $author->id }}">{{ $author->username }}</option>
            @endforeach
        </select>

        <?php /* Tagovi */ ?>
        <label>Izaberite tag</label>
        <br />
        <div class="btn-group">
            @foreach ($tags as $tag)
                <input
                    class   = "sr-only active admin_articles__create_tag"
                    id      = "admin_articles__create_tag--{{ $tag->id }}"
                    name    = "admin_articles__create_tag"
                    type    = "checkbox"
                    value   = "{{ $tag->id }}"
                />

                <label
                    class   = "btn btn-primary admin_articles__create_tag__label"
                    for     = "admin_articles__create_tag--{{ $tag->id }}"
                >
                    {{ $tag->name }}
                </label>
            @endforeach
        </div>
        <br />


        <?php /*Tekst*/ ?>
        <label for="admin_articles__create_text">Tekst (Unos je zakljucan dok se ne unesu naslov i kategorija)</label>
        <textarea
            class       = "form-control admin_articles__create_text"
            id          = "admin_articles__create_text"
            name        = "text"
            placeholder = "Tekst"
            type        = "text"
        ></textarea>

        <?php /*Isecak*/ ?>
        <label for="admin_articles__create_excerpt">Isečak (Unos je zakljucan dok se ne unesu naslov i kategorija)</label>
        <textarea
            class       = "form-control"
            id          = "admin_articles__create_excerpt"
            name        = "excerpt"
            placeholder = "Isečak"
            type        = "text"
        ></textarea>

        <input
            id          = "admin_articles__create__reset"
            type        = "reset"
            class       = "btn btn-danger"
            value       = "Reset"
        />
        <input
            id          = "admin_articles__create__submit"
            type        = "submit"
            class       = "btn btn-default"
            value       = "Napravi"
        />

        <p class="alert alert-success collapse" role="alert">
            <strong>BRAVO</strong> Uspešno ste kreirali članak!!!!
        </p>

    </form>

@endif