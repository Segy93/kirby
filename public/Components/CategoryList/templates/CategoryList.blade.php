<nav class="category_list__content">
    <div class = "category_list__header_container">
        <h2 class = "category_list__header">Kategorije</h2>
    </div>
    <ul
        class   = "category_list__content_list" itemscope
        itemtype="http://schema.org/WebPage"
    >
        @foreach ($categories as $category)
            <li class = "category_list__content_item">
                <div class = "category_list__item_single">
                    <a
                        href    = "/{{ $category->url }}"
                        class   = "category_list__content_link"
                        title   = "{{ $category->name }}"
                        itemprop= "significantLink"
                    >
                        <span class = "category_list__name">
                            {{ $category->name }}
                        </span>

                        <span class = "category_list__count">
                            &nbsp;({{ $category->articles->count() }})
                        </span>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>
</nav>
