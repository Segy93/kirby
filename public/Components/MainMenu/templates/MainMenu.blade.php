<nav class = "main_menu main_menu--{{ $expanded ? 'expanded' : 'folded' }}" role = "menubar">
    <ul class="main_menu__list">
        @foreach ($links as $i => $link)
            <li
                @if (isset($link['sub']))
                    aria-haspopup="true"
                @endif
                class = "main_menu__item main_menu__item--parent"
            >
                <a class="main_menu__item_link" href="/{{ $link['url'] }}">
                    <span class="main_menu__item_link_text">
                        {{ $link['title'] }}
                    </span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
