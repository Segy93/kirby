<svg xmlns="http://www.w3.org/2000/svg" encoding="utf-8" style="display: none;">

    <symbol id="icon-tag" viewBox="0 0 32 32">
        <title>Tagovi</title>
        <path d="M14 4l13.381 13.381c0.783 0.783 0.787 2.051 0.008 2.831l-7.177 7.177c-0.778 0.778-2.047 0.776-2.831-0.008l-13.381-13.381v-8c0-1.112 0.895-2 2-2h8zM9.5 11c0.828 0 1.5-0.672 1.5-1.5s-0.672-1.5-1.5-1.5c-0.828 0-1.5 0.672-1.5 1.5s0.672 1.5 1.5 1.5v0z"></path>
    </symbol>

</svg>
<article class = "article_page__content" itemscope itemtype ="http://schema.org/Article">
    <div itemprop = "articleSection" class = "article_page__content_holder">

        <header class = "article_page__content_heading" itemprop = "articleSection">
            <h1
                itemprop    = "headline"
                class       = "article_page__content_heading_title"
            >
                {{$article->title}}
            </h1>
            <div class = "article_page__info">
                <address
                    class       = "article_page__info_author"
                    itemprop    = "author"
                >
                    <svg class="article_page__icon article_page__user_icon">
                        <use xlink:href="#article_page__user_icon"></use>
                    </svg>
                        <a
                            class   = "article_page__info_author__link"
                            rel     = "author"
                            href    = "/autori/{!! $article->author_id !!}"
                        >
                            @if ($article->author_id === null)
                                Monitor
                            @else
                                {{ $article->author->username }}
                            @endif
                         </a>
                </address>
                <span
                    class       = "article_page__info_date"
                    itemprop    = "datePublished"
                >
                <svg class="article_page__icon article_page__clock_icon">
                    <use xlink:href="#article_page__clock_icon"></use>
                </svg>
                    <time pubdate datetime = "{!! $article->published_at->format("Y-m-d") !!}">
                        {!! $article->published_at->format("d.m.Y") !!}
                    </time>
                </span>
            </div>
        </header>

        <div class="article_page__body" id="article_page__body" itemprop="articleBody">
            {!! $article->text !!}
        </div>
        <section class = "article_page__tags_social">
            <section class = "article_page__content_tags">
                @if (!empty($tags))
                    <p class = "article_page__tags_heading">Tagovi:</p>
                    @foreach ($tags as $tag)
                        <a itemprop = "link" class = "article_page__content_tag" href = "{{$tag->url}}">{{$tag->name}}</a>
                        @if (end($tags) !== $tag)
                            ,
                        @endif
                    @endforeach
                @endif
            </section>

            <section class = "article_page__social">
                {!!$social_share_lg->renderHTML($article->url, $article->title)!!}
            </section>
        </section>
        {!!$recomended_articles->renderHTML()!!}
        {!! $comment_list->renderHTML() !!}
    </div>
</article>
