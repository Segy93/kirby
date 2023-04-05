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

                    @if (isset($link['sub']))
                        <label for = "main_menu__submenu_checkbox--{{$i}}" class = "main_menu__submenu_label">
                            <svg class="common_landings__icon main_menu__icon main_menu__icon--chevron_right">
                                <use xlink:href="#main_menu__icon--chevron_right"></use>
                            </svg>
                            <span class ="common_landings__visually_hidden main_menu__submenu_label__text">Otvori podmeni</span>
                        </label>
                    @endif
                </a>

                @if (isset($link['sub']))
                    <input
                        aria-label      = "Otvori podmeni"
                        class           = "common_landings__visually_hidden main_menu__submenu_checkbox main_menu__submenu_checkbox--{{$i}}"
                        id              = "main_menu__submenu_checkbox--{{$i}}"
                        type            = "checkbox"
                    />

                    <ul aria-hidden="true" class = "main_menu__sub" role="menu">
                        @foreach ($link['sub'] as $column)
                            <li class="main_menu__column">
                                <ul class="main_menu__column_list">
                                    @foreach ($column as $sub)
                                        <li
                                            class       = "main_menu__item main_menu__item--child"
                                            role        = "menuitemradio"
                                            tabindex    = "-1"
                                        >
                                            <a
                                                class = "main_menu__heading"
                                                href  = "/{{$sub['url']}}"
                                            >
                                                {{$sub['title']}}
                                            </a>

                                            @if (isset($sub['children']))
                                                @foreach ($sub['children'] as $child)
                                                    <h5 class = "main_menu__subheading">
                                                        {{$child['title']}}
                                                    </h5>

                                                    @if (isset($child['children']))
                                                        @foreach ($child['children'] as $grandchild)
                                                            <a
                                                                class   = "sub_menu__sub_filter__link"
                                                                href    = "/{{$grandchild['url']}}"
                                                            >
                                                                {{$grandchild['title']}}
                                                            </a>
                                                        @endforeach
                                                    @endif

                                                @endforeach
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
</nav>
