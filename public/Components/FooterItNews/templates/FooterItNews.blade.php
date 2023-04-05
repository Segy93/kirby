@foreach ($categories as $category)
    <ul class = "footer_item__list">
        <li>
            <a
                class   = "footer_news__item"
                href    = "{{ $category->url }}"
            >
                {{ $category->name }}
            </a>
        </li>
    </ul>
@endforeach
