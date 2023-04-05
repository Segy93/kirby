<section class = "recommended_list__content common_landing__main_bg_pattern">
    <div class = "recommended_list__header_container">
        <h2
            class   = "recommended_list__header"
        >
            Najčitanije
        </h2>
    </div>
    <nav class = "recommended_list__nav">
        <ul class = "recommended_list__list" itemscope itemtype="http://schema.org/WebPage">
            @foreach ($popular_articles as $popular_article)
                <li class = "recommended_list__article">                    
                    <a
                        href    = "{{ $popular_article->url }}"
                        class   = "recommended_list__image_link"
                        title   = "{{ $popular_article->title }}"
                        itemprop= "relatedLink"
                    >
                        <img
                            alt  = "{{ $popular_article->title }}"
                            class = "recommended_list__image"
                            src   = "/uploads_static/originals/{{ $popular_article->picture }}"
                        />
                    </a>
                    <div class = "recommended_list__article_info">
                        <a
                            href    = "{{ $popular_article->url }}"
                            class   = "recommended_list__article_link"
                            title   = "{{ $popular_article->title }}"
                            itemprop= "relatedLink"
                        >
                            {{ $popular_article->title }}
                        </a>
                        <div
                            class       = "recommended_list__info_date"
                            itemprop    = "datePublished"
                        >
                        <svg class="recommended_list__icon recommended_list__clock_icon">
                            <use xlink:href="#recommended_list__clock_icon"></use>
                        </svg>
                        <time
                            pubdate datetime = "{!! $popular_article->published_at->format("Y-m-d") !!}"
                        >
                            {{ $popular_article->published_at->format("d.m.Y") }}
                        </time>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </nav>
</section>
