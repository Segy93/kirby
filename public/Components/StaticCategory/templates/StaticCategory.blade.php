<nav class = "static_page__menu">
    <ul class = "static_page__list">
        @foreach ($links as $link)
            <li class = "static_page__list_element">
                <a
                    class = "
                        static_page__link
                        @if ($path === $link->url)
                            static_page__link--selected
                        @endif
                    "
                    href  = "/{{ $link->url }}"
                >
                    {{ $link->title }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
<article class = "static_page__wrapper">
    @if ($page !== null)
        {!!$page->text!!}
    @endif
</article>
