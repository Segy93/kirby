<article
        class               = "article_single__container"
        itemscope itemtype  = "http://schema.org/Article"
>
    <div class="article_single__article_content">
        <div
            itemprop    = "articleSection"
            class       = "article_single__article_image"
        >
            <a
                class       = "article_single__image_link"
                itemprop    = "url"
                href        = "/{!! $js_template ? '<%= article.url %>' : $article->url !!}"
                tabindex    = "-1"
            >
                <img
                    alt         = "{!! $js_template ? '<%= article.title %>' : $article->title !!}"
                    class       = "article_single__article_image_file"
                    itemprop    = "image"
                    src         = "/uploads_static/originals/{!! $js_template ? '<%= article.picture %>' : $article->picture !!}"
                />
            </a>
        </div>

        <div
                itemprop    = "articleSection"
                class       = "article_single__article_excerpt"
        >
            <h2
                itemprop    = "headline"
                class       = "article_single__article_heading_content"
            >
                <a
                    class       = "article_single__content_link"
                    href        = "/{!! $js_template ? '<%= article.url %>' : $article->url !!}"
                    itemprop    = "url"
                >
                    @if ($js_template)
                        <%= article.title %>
                    @else
                        {{ $article->title }}
                    @endif
                </a>
            </h2>
            <div class = "article_single__info">
                <address
                    class       = "article_single__info_author"
                    itemprop    = "author"
                >
                    <svg class="article_single__icon article_single__user_icon">
                        <use xlink:href="#article_single__user_icon"></use>
                    </svg>
                    @if ($js_template)
                        <% if (article.author !== null) { %>
                        <a
                            class   = "article_single__info_author__link"
                            rel     = "author" 
                            href    = "/autori/{!!'<%= article.author.username %>'!!}"
                        >
                            <%= article.author.username %>
                         </a>
                         <% } else { %>
                            Monitor
                         <% }%>
                    @else
                        @if ($article->author !== null)                     
                        <a
                            class   = "article_single__info_author__link"
                            rel     = "author" 
                            href    = "/autori/{!! $article->author->username !!}"
                        >
                                {{ $article->author->username }}
                         </a>

                        @else 
                            Monitor
                        @endif
                    @endif
                </address>
                <span
                    class       = "article_single__info_date"
                    itemprop    = "datePublished"
                >
                <svg class="article_single__icon article_single__clock_icon">
                    <use xlink:href="#article_single__clock_icon"></use>
                </svg>
                    <time
                        pubdate
                        datetime = "{!! $js_template ? '<%= article.published_unmod %>' : $article->published_at->format("Y-m-d") !!}"
                    >
                        {!! $js_template ? '<%= article.published_at %>' : $article->published_at->format("d.m.Y") !!}
                    </time>
                </span>
            </div>

            <div itemprop="about" class="article_single__article_excerpt_text">
                @if ($js_template)
                    <%= article.excerpt %>
                @else
                    {!! $article->excerpt !!}
                @endif
            </div>
        </div>
    </div>
</article>
