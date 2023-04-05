<div class="navbar-inverse collapse navbar-collapse navbar-ex1-collapse d-print-none ">
    <ul class="nav navbar-nav side-nav">
        @foreach ($links as $link)
            @if ($link['url'] === $selected)
                <li class="active">
                    <a href="/admin/{{ $link['url'] }}">{{ $link['title'] }}</a>
                </li>
            @else
                <li>
                    <a href="/admin/{{ $link['url'] }}">{{ $link['title'] }}</a>
                </li>
            @endif
       @endforeach
    </ul>
</div>
