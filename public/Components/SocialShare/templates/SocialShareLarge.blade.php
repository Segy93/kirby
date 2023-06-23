{{--<ul
    class="ssk-group ssk-lg social_share social_share__xl"
    data-url=""
    data-text=""
    itemscope itemtype=“http://schema.org/Organization”
>
    <link itemprop="url" href="http://www.monitor.rs" />

    @foreach ($networks as $network)
        <li tabindex="0" class="ssk ssk-{{$network['name']}}">
            <a tabindex="-1" itemprop=“sameAs” href="{{$network['link']}}">
                <span class = "common_landings__visually_hidden">
                    Podeli na {{$network['label']}}-u
                </span>
            </a>
        </li>
    @endforeach
</ul>--}}
