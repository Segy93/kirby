<ul class = "footer_item__list">
    @foreach ($category->pages as $page)
        <li>
            <a class = "footer_basic__information_item" href = "{{$page->url}}">{{$page->title}}</a>
        </li>
    @endforeach
</ul>
