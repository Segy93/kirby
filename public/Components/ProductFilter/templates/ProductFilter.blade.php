<div class = "product_filter">
    <h1 class = "product_filter__category_name">
        @if ($category->name === 'Elite PC')
            PC Desktop
        @else
            {{ $category->name }}
        @endif
        @if ($on_sale)
            <br />Na akciji
        @endif
    </h1>

    <ul class="product_filter__list">
        @foreach ($filters as $filter)
            @if ($filter['type'] === 'checkbox')
                <li
                    class             = "product_filter__wrapper_single"
                    data-label        = "{{ $filter['name_import'] }}"
                    data-machine_name = "{{ $filter['machine_name'] }}"
                    data-type         = "{{ $filter['type'] }}"
                >
                    <input
                        type    = "checkbox"
                        id      = "show_filter--{{ $filter['label'] }}"
                        class   = "common_landings__visually_hidden show_filter"
                        @if (
                            !empty($url) &&
                            !empty($url[str_replace(" ", "_", $filter['label'])])
                            && ($is_configurator === false
                            || $filter['label'] !== 'Proizvođač')
                        )
                            checked
                        @endif
                    />
                    <label
                        class   = "show_filter__label"
                        for     = "show_filter--{{$filter['label']}}"
                    >
                        {{ isset($filter['name']) ? $filter['name'] : $filter['label'] }}
                    </label>

                    <ul class = "product_filter__box">
                        <?php
                            $is_any_checked = false;
                            foreach ($filter['values'] as $value) {
                                if (
                                    !empty($url) &&
                                    !empty($url[str_replace(" ", "_", $filter['label'])])
                                    && (
                                        in_array(urlencode($value), $url[str_replace(" ", "_", $filter['label'])]) ||
                                        in_array(urldecode($value), $url[str_replace(" ", "_", $filter['label'])]) ||
                                        in_array($value, $url[str_replace(" ", "_", $filter['label'])])
                                    )
                                ) {
                                    $is_any_checked = true;
                                }
                            }
                        ?>
                        @foreach ($filter['values'] as $value)
                            <li class = "product_filter__filter_single">
                                <input
                                    class   = "product_filter product_filter__checkbox"
                                    id      = "product_filter__checkbox--{{$filter['machine_name']}}_{{$value}}"
                                    type    = "checkbox"
                                    name    = "{{$filter['label']}}"
                                    value   = "{{$value}}"
                                    @if (
                                        !empty($url) &&
                                        !empty($url[str_replace(" ", "_", $filter['label'])])
                                    )
                                        @if (
                                            in_array(urlencode($value), $url[str_replace(" ", "_", $filter['label'])]) ||
                                            in_array(urldecode($value), $url[str_replace(" ", "_", $filter['label'])]) ||
                                            in_array($value, $url[str_replace(" ", "_", $filter['label'])])
                                        )
                                            checked
                                        @endif
                                        @if ($is_configurator && $is_any_checked)
                                            disabled
                                        @endif
                                    @endif
                                >
                                <label
                                    class   = "product_filter__label"
                                    for     = "product_filter__checkbox--{{$filter['machine_name']}}_{{$value}}"
                                >
                                    {{$value}}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @elseif ($filter['type'] === 'slider')
                @if (array_key_exists('min' , $filter) && array_key_exists ('max', $filter ))
                    <?php
                        $min = $filter['min'];
                        $max = $filter['max'];
                    ?>
                @else
                    <?php
                        $min = current($filter['values']);
                        $max = end($filter['values']);
                    ?>
                @endif

                <li
                    class="product_filter__wrapper_single"
                    data-label          = "{{ $filter['name_import'] }}"
                    data-machine_name   = "{{ $filter['machine_name'] }}"
                    data-type           = "{{ $filter['type'] }}"
                >
                    <input
                        type    = "checkbox"
                        id      = "show_filter--{{$filter['label']}}"
                        class   = "common_landings__visually_hidden show_filter"
                        @if (
                            !empty($url) &&
                            !empty($url[$filter['label']])
                        )
                            checked
                        @endif
                    />
                    <label
                        class   = "show_filter__label"
                        for     = "show_filter--{{$filter['label']}}"
                    >
                        {{$filter['label']}}
                    </label>
                    <div class = "product_filter__box">
                        <div
                            id          = "product_filter__range_slider"
                            class       = "filter"
                            name        = "{{$filter['label']}}"
                            data-name   = "{{$filter['machine_name']}}"
                            data-min    = "{{$min}}"
                            data-max    = "{{$max}}"
                            @if (!empty($url))
                                @if(array_key_exists($filter['machine_name'], $url))
                                    @if($min !== $url[ $filter['machine_name']][0][0])
                                        data-set-min = "{{$url[ $filter['machine_name']][0][0]}}"
                                    @endif
                                    @if($max!== $url[ $filter['machine_name']][0][1])
                                        data-set-max = "{{$url[ $filter['machine_name']][0][1]}}"
                                    @endif
                                @endif
                            @endif
                        >
                        </div>
                        @if ($filter['label'] === 'Cena')
                            <div class = "product_filter__price_inputs">
                                <div class = product_filter__price--min>
                                    <label
                                        for =  "product_filter__price_input--min"
                                    >
                                        Od:
                                    </label>
                                    <input
                                        class       = "product_filter__price_input product_filter__price_input--min"
                                        data-type   = "min"
                                        id          = "product_filter__price_input--min"
                                        max         = "{{$max}}"
                                        min         = "{{$min}}"
                                        type        = "number"
                                    />
                                </div>
                                <div class = "product_filter__price--max">
                                    <label
                                        for =  "product_filter__price_input--max"
                                    >
                                        Do:
                                    </label>
                                    <input
                                        class       = "product_filter__price_input product_filter__price_input--max"
                                        data-type   = "max"
                                        id          = "product_filter__price_input--max"
                                        max         = "{{$max}}"
                                        min         = "{{$min}}"
                                        type        = "number"
                                    />
                                </div>
                            </div>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
</div>
