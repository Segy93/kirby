<div class="admin_articles__filter">
    <h3>Filtriranje po tagu</h3>
    <div class="btn-group" data-toggle="buttons" id="filter_tags">
        <label class = "btn btn-primary admin_articles__filter_tag__label">
            <input
                class   = "checkbox active admin_articles__filter_tag"
                id      = "admin_articles__filter_tag--all"
                name    = "admin_articles__filter_tag"
                type    = "radio"
                value   = "0"
            />
            Svi tagovi
        </label>

        @foreach ($tags as $tag)
            <label class="btn btn-primary admin_articles__create_tag__label">
                <input
                    class   = "checkbox active admin_articles__filter_tag"
                    name    = "admin_articles__filter_tag"
                    type    = "radio"
                    value   = "{{ $tag->id }}"
                />
                {{ $tag->name }}
            </label>
        @endforeach
    </div>

    <h3>Filtriranje po kategoriji</h3>
    <div class="btn-group" data-toggle="buttons">
        <label class="btn btn-primary admin_articles__filter_category__label">
            <input
                class   = "checkbox active admin_articles__filter_category"
                name    = "admin_articles__filter_category"
                type    = "radio"
                value   = "0"
            />
            Sve kategorije
        </label>

        @foreach ($categories as $category)
            <label class="btn btn-primary admin_articles__filter_category__label">
                <input
                    class   = "checkbox active admin_articles__filter_category"
                    name    = "admin_articles__filter_category"
                    type    = "radio"
                    value   = "{{ $category->id }}"
                />
                {{ $category->name }}
            </label>
        @endforeach
    </div>
</div>

<table class="admin_articles__list_table table table-striped table-sm table-bordered table-hover">
    <thead>
        <th>Slika</th>
        <th>Naslov</th>
        <th>Datum</th>
        <th>Broj pregleda</th>
        <th>Kategorija</th>
        <th>Otvori</th>
        <th>Status</th>
        <th>Promeni</th>
        <th>Izmena tagova</th>
        <th>SEO</th>
        <th>Obriši</th>
    </thead>
    <tbody id="admin_articles__list_content"></tbody>
</table>

<nav
    aria-label  = "Strane"
    class       = "center-block text-center clearfix"
    role        = "group"
>
    <button
        class="btn btn-default invisible"
        id="admin_articles__list__prev"
        type="button"
    >
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Nazad</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_articles__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Napred</span>
    </button>
</nav>

<script type="text/html" id="admin_articles__list_temp" >
    @if ($permissions['article_read'])
        <%var timezone_offset = new Date().getTimezoneOffset();%>
        <%for(var i = 0, l = articles.length; i < l; i++) {%>
            <%var article = articles[i];%>
            <%var published_at = new Date(article.published_unmod);%>
            <%published_at.setMinutes(published_at.getMinutes()- timezone_offset);%>

            <tr class = "admin_article__row_<%= article.id %>">
                <?php /*Slika*/ ?>
                <td>
                    <input
                        class           = "hidden"
                        data-article-id = "<%= article.id %>"
                        id              = "admin_article__image_change"
                        type            = "file"
                    />
                    <img
                        alt             = "<%= article.title %> picture"
                        class           = "admin_articles__list_picture"
                        src             = "/uploads_static/originals/<%= article.picture %>"
                        width           = "100"
                    />
                </td>

                <?php /*naslov*/ ?>
                <td>
                    <%= article.title %>
                </td>

                <?php /*Datum*/?>
                <td>
                    <input
                        class           = "form-control admin_articles__list_date"
                        id              = "admin_articles__list_date"
                        name            = "date"
                        type            = "datetime"
                        data-article-id = "<%= article.id %>"
                        value           = "<%= published_at.toISOString() %>"
                    />
                </td>

                <?php /*Broj pregleda*/?>
                <td>
                    <%= article.views %>
                </td>

                <?php /*Kategorija*/ ?>
                <td>
                    <select
                        class            = "form-control admin_articles__list_categories"
                        data-article-id  = "<%= article.id %>"
                        data-category-id = "<%= article.category_id %>"
                    >
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </td>

                <?php /*Otvori*/ ?>
                <td>
                    <a class="btn btn-warning" href="/<%= article.url %>">Otvori</a>
                </td>

                <?php /*Status*/?>
                <td>
                    <% if (article.status) {%>
                        <button
                            class           = "btn btn-success admin_article__button_publish"
                            data-article-id = "<%= article.id %>"
                            type            = "button"
                        >
                            Objavi
                        </button>
                    <%} else {%>
                         <button
                            class           ="btn btn-danger admin_article__button_return"
                            type            = "button"
                            data-article-id = "<%= article.id %>"
                        >
                            Povuci
                        </button>
                    <% } %>
                </td>

                <?php /*izmeni*/?>
                <td>
                    <a
                        class                   = "btn btn-warning"
                        href                    = "admin/clanci/izmena/<%= article.id %>"
                    >
                        Promeni
                    </a>
                </td>

                <?php /*Tagovi izmena*/?>
                <td>
                    <button
                        class           = "btn btn-warning"
                        data-article-id = "<%= article.id %>"
                        data-target     = "#admin_articles__modal_tag"
                        data-toggle     = "modal"
                        type            = "button"
                    >
                        Tagovi
                    </button>
                </td>

                <?php /*SEO izmena*/?>
                <td>
                    <button
                        class             = "btn btn-warning admin_article__list_seo seo__invoker"
                        data-target       = "#admin_articles__modal_seo"
                        data-tag-id       = "<%= article.id %>"
                        data-toggle       = "modal"
                        data-backdrop     = "static"
                        data-keyboard     = "false"
                        data-machine_name = "article_<%= article.id %>"
                        type              = "button"
                    >
                        SEO
                    </button>
                </td>

                <?php /*Obriši*/?>
                <td>
                    <button
                        class           = "btn btn-danger admin_articles__modal_delete"
                        type            = "button"
                        data-target     = "#admin_articles__modal_delete"
                        data-toggle     = "modal"
                        data-article-id = "<%= article.id %>"
                    >
                        Obriši
                    </button>
                </td>

            </tr>
        <%}%>
    @endif
</script>
