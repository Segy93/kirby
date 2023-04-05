<section class="main_container">
    <div class = "main_container__banner">
        @foreach ($banner as $ban)
            <div class = "main_container__banner_section">
                {!! $ban->renderHTML() !!}
            </div>
        @endforeach
    </div>

    <div class = "main_container__content">
        @foreach ($content as $child)
            <div class = "main_container__content_section">
                {!! $child->renderHTML() !!}
            </div>
        @endforeach
    </div>

    <div class = "main_container__banner">
        @foreach ($banner as $ban)
            <div class = "main_container__banner_section">
                {!! $ban->renderHTML() !!}
            </div>
        @endforeach
    </div>
</section>
