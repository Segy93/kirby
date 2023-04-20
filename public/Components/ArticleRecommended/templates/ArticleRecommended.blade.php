<div class="article_recommended">
    <div class = "article_recommended__heading_container">
        <h3 class="article_recommended__heading">Preporučujemo:</h3>
    </div>
    <section
        class       = "article_recommended__list"
        itemscope
        itemtype    = "http://schema.org/WebPage"
    >
        @if (empty($recommended_articles))
            <h4 class = "article_recommended__no_articles">Nema preporučenih članaka</h4>
        @endif
        @foreach($recommended_articles as $article)
            <?php 
                //Iz nekog razloga mi je svaki pojedinacni clanak u niz
                // i ne znam kako da resim trenutno a nisi online pa ga hakujem
                $article = $article[0];
            ?>
            <div class="article_recommended__article">
                <a
                    itemprop    = "relatedLink"
                    class       = "article_recommended__link"
                    href        = "/{{ $article->url }}"
                >
                    <div class = "article_recommended__img_wrapper">
                        <img
                            alt     = "{{ $article->title }}"
                            class   = "article_recommended__article_image"
                            src     = "/uploads_static/originals/{{ $article->picture }}"
                        />
                    </div>

                    <h4
                        class   = "article_recommended__single_header"
                        title   = "{{ $article->title }}"
                    >
                        {{ $article->title }}
                    </h4>
                </a>
            <div class = "article_recommended__info">
                <span
                    class       = "article_recommended__info_author"
                    itemprop    = "author"
                >
                    <svg class="article_recommended__icon article_recommended__user_icon">
                        <use xlink:href="#article_recommended__user_icon"></use>
                    </svg>
                    Kese za Kirby
                </span>
                <span
                    class       = "article_recommended__info_date"
                    itemprop    = "datePublished"
                >
                <svg class="article_recommended__icon article_recommended__clock_icon">
                    <use xlink:href="#article_recommended__clock_icon"></use>
                </svg>
                    {!!$article->published_at->format("d.m.Y") !!}
                </span>
            </div>
            </div>
        @endforeach
    </section>
</div>
