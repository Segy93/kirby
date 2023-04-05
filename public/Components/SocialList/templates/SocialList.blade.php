@foreach ($social_items as $social_item)
    <div class = "social_list__item_container">
        <label
            for     = "social_list__{{ $social_item['name'] }}"
            class   = "common_landings__visually_hidden"
        >
            {{ $social_item['name'] }}
        </label>
        <a
            aria-label  = "{{$social_item['name']}}"
            class       = "social_list__network ssk ssk-{{ $social_item['name'] }} "
            content     = "{{ $social_item['name'] }}"
            href        = "{{ $social_item['link'] }}"
            id          = "social_list__{{ $social_item['name'] }}"
            itemprop    = "name"
            rel         = "noreferrer"
            target      = "_blank"
            >
        </a>
    </div>
@endforeach
