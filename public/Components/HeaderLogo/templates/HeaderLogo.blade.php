<a
    class = "
        header_logo__link
        @if ($print_only === true)
            header_logo__link--print
        @endif
    "
    href = "/"
    itemprop="url"
    itemscope
>
    <img
        alt         = "{{$alt}}"
        class       = "header_logo header_logo--view"
        itemprop    = "logo"
        src         = "{{$image_view}}"
    />
    <img
        alt         = "{{$alt}}"
        class       = "header_logo header_logo--print"
        itemprop    = "logo"
        src         = "{{$image_print}}"
    />
</a>
