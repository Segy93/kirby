<section class = "menu_slider__box_menu">
    {!! $main_menu->renderHTML() !!}
</section>
<div class = "menu_slider__box">
   <div class = "menu_slider__box_container">

        @if ($show_slider)
            <section
                class = "
                    menu_slider__box_slider
                    @if (!$expanded_menu)
                        menu_slider__box_slider--other
                    @endif
                "
            >
                {!! $slider->renderHTML() !!}
            </section>
        @endif
    </div>
</div>
