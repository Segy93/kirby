<div  itemscope itemtype = "http://schema.org/LocalBusiness">
    @foreach ($shops as $shop)
        <ul class = "footer_work__time_list">
            <li
                class = "footer_work__time_item"
            >
                Adresa:
                <span
                    itemprop = "address"
                >
                    {{$shop->address->address}}
                </span>
            </li>
            <li
                class       = "footer_work__time_item"
                itemprop    = "openingHours"
            >
                {!!str_replace('\n', "<br/>",$shop->address->open_hours_field);!!}
            </li>
        </ul>
    @endforeach

    <div class = "common_landings__visually_hidden" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <meta itemprop="streetAddress"      content="Kumanovska 14 Vračar">
        <meta itemprop="addressLocality"    content="Belgrade">
        <meta itemprop="postalCode"         content="11000">
        <meta itemprop="addressCountry"     content="RS">
    </div>
</div>
