<section class="main_slider">
    <ul class = "slider_slides" data-count = "{{ $count }}">
        @for ($i = 0; $i < $count; $i++)
            <li class="slider_slide__container slider_slide__container--{{$i}}" data-index = "{{ $i }}">
                <a href = "{{$banners[$i]->link}}">
                    <img
                        alt     = "{{$banners[$i]->title}}"
                        class   = "banner__image"
                        data-id = "{{$banners[$i]->id}}"
                        src     = "uploads_static/originals/{{$banners[$i]->image}}"
                    />
                </a>
            </li>
        @endfor
    </ul>

    <ul class="slider_nav__dots">
        @for ($i = 0 ; $i < $count; $i++)
            <li class = "slider_nav__dot_container slider_nav__dot_container--{{$i}}">
                <button
                    class       = "slider_nav__dot slider_nav__dot_button slider_nav__dot_button--{{$i}}"
                    data-index  = "{{ $i }}"
                    id          = "slider_nav__dot_label--{{$i}}"
                    tabindex="-1"
                >
                </button>
            </li>
        @endfor
    </ul>
        <label class="slider_nav__prev">&#x2039;</label>
        <label class="slider_nav__next">&#x203a;</label>
</section>
