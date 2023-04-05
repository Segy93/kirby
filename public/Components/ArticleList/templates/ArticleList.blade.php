<section class="article_list">
    @if (count($articles) > 0)
        <div class = "article_list__header_container">
            <h1 class="article_list__category_name">{{ $title }} </h1>
        </div>
        <div id="article_list__content">
            @foreach ($articles as $article)
                <article class = "article_list__article">
                    {!! $article_single->renderHTML($article) !!}
                </article>
            @endforeach
        </div>
        <nav aria-label="Page navigation" class="article_list__arrow_grid" role="group">
            <a
                href    = "{{ $base_url }}{{ $date_first->format("Y-m-d H:i:s") }}|Nazad"
                class   = "
                    article_list__arrow
                    article_list__arrow--left
                    {{ $more_backward ? 'article_list__arrow--active' : '' }}
                "
                id      = "article__list__prev"
            >
                <span class="">Nazad</span>
            </a>

            <a
                href    = "{{ $base_url }}{{ $date_last->format("Y-m-d H:i:s") }}|Napred"
                class   = "
                    article_list__arrow
                    article_list__arrow--right
                    {{ $more_forward ? 'article_list__arrow--active' : '' }}
                "
                id      = "article__list__next"
            >
                <span class="">Napred</span>
            </a>
        </nav>
    @else
        <h2 class = "article_list__no_articles">Nema članaka za zadatu kategoriju</h2>
    @endif

    <script type="text/html" id="article_list__tmpl">
        <%for(var i = 0, l = articles.length; i < l; i++) {%>
            <%var article = articles[i];%>
            {!! $article_single->renderHTML() !!}
        <%}%>
    </script>
</section>