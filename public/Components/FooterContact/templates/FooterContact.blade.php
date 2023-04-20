<svg aria-hidden="true" class = "footer_contact__svg"  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <defs>
        <symbol id="footer_contact__icon--address" viewBox="0 0 32 32">
            <title>Adresa</title>
            <path d="M32 19l-6-6v-9h-4v5l-6-6-16 16v1h4v10h10v-6h4v6h10v-10h4z"></path>
        </symbol>
        <symbol id="footer_contact__icon--phone" viewBox="0 0 32 32">
            <title>Telefon</title>
            <path d="M22 20c-2 2-2 4-4 4s-4-2-6-4-4-4-4-6 2-2 4-4-4-8-6-8-6 6-6 6c0 4 4.109 12.109 8 16s12 8 16 8c0 0 6-4 6-6s-6-8-8-6z"></path>
        </symbol>
        <symbol id="footer_contact__icon--mail" viewBox="0 0 32 32">
            <title>Email</title>
            <path d="M26.667 0h-21.333c-2.934 0-5.334 2.4-5.334 5.334v21.332c0 2.936 2.4 5.334 5.334 5.334h21.333c2.934 0 5.333-2.398 5.333-5.334v-21.332c0-2.934-2.399-5.334-5.333-5.334zM5.707 27.707l-2.414-2.414 8-8 0.914 0.914-6.5 9.5zM4.793 6.207l0.914-0.914 10.293 8.293 10.293-8.293 0.914 0.914-11.207 13.207-11.207-13.207zM26.293 27.707l-6.5-9.5 0.914-0.914 8 8-2.414 2.414z"></path>
        </symbol>
        <symbol id="footer_contact__icon--clock" viewBox="0 0 32 32">
            <title>Radno vreme</title>
            <path d="M16 0c-8.837 0-16 7.163-16 16s7.163 16 16 16 16-7.163 16-16-7.163-16-16-16zM20.586 23.414l-6.586-6.586v-8.828h4v7.172l5.414 5.414-2.829 2.829z"></path>
        </symbol>
    </defs>
</svg>
<div itemscope itemtype = "http://schema.org/LocalBusiness">
    <meta itemprop="telephone" content="+381114114800">
    <meta itemprop="priceRange" content="$$">
    <meta itemprop = "currenciesAccepted" content = "RSD"/>
    <meta itemprop="name" content="Kese za Kirby">
    <link itemprop="image" href="/favicon-1024x1024.png" />

    <div
        class = "common_landings__visually_hidden"
        itemprop="address"
        itemscope
        itemtype="http://schema.org/PostalAddress"
    >
        <meta itemprop="streetAddress"      content="Kumanovska 14 Vračar">
        <meta itemprop="addressLocality"    content="Belgrade">
        <meta itemprop="postalCode"         content="11000">
        <meta itemprop="addressCountry"     content="RS">
    </div>
</div>

@foreach ($shops as $shop)
    <table class = "footer_contact__table" role = "presentation">
        <tr class = "footer_contact__row">
            <td>
                <svg class="common_landings__icon footer_contact__icon footer_contact__icon--address">
                    <use xlink:href="#footer_contact__icon--address"></use>
                </svg>
            </td>
            <td class = "footer_contact__cell">
                <a
                    itemprop = "hasMap"
                    href  = "https://www.google.rs/maps/place/{{$shop->address->address}}"
                    class = "footer_contact__item"
                    target= "_blank"
                    rel   = "noopener"
                >
                    <span class = "footer_contact__item_info">
                        Adresa: <span itemprop = "address">{{$shop->address->address}}</span>
                    </span>
                </a>
            </td>
        </tr>
        <tr class = "footer_contact__row">
            <td>
                <svg class="common_landings__icon footer_contact__icon footer_contact__icon--phone">
                    <use xlink:href="#footer_contact__icon--phone"></use>
                </svg>
            </td>
            <td class = "footer_contact__cell">
                @foreach ($shop->phones as $key => $phone)
                    <a
                        class = "footer_contact__item"
                        href  = "tel:{{ $phone->phone_nr_link }}"
                    >
                        <span class = "footer_contact__item_info">
                            <span
                                class = "
                                    footer_contact__item_phone_text
                                    @if ($key === 0)
                                        footer_contact__item_phone_text--first
                                    @endif
                                "
                            >
                                Telefoni:
                            </span>
                            <span
                                class    = "footer_contact__item_phone" 
                                itemprop = "telephone"
                            >
                                {{ $phone->phone_nr }}
                            </span>
                        </span>
                    </a>
                @endforeach
            </td>
        </tr>
        <tr class = "footer_contact__row">
            <td>
                <svg class="common_landings__icon footer_contact__icon footer_contact__icon--mail">
                    <use xlink:href="#footer_contact__icon--mail"></use>
                </svg>
            </td>
            <td class = "footer_contact__cell">
                <a
                    class = "footer_contact__item"
                    href  = "mailto:{{$shop->address->email}}"
                >
                    <span class = "footer_contact__item_info">
                        Email: <span itemprop = "email">{{$shop->address->email}}</span>
                    </span>
                </a>
            </td>
        </tr>
        <tr class = "footer_contact__row">
            <td>
                <svg class="common_landings__icon footer_contact__icon footer_contact__icon--clock">
                    <use xlink:href="#footer_contact__icon--clock"></use>
                </svg>
            </td>
            <td class = "footer_contact__cell">
                <div class = "footer_contact__item_open_hours footer_contact__item_info">
                {!!str_replace('\n', "<br/>",$shop->address->open_hours_field);!!}
                </div>
            </td>
        </tr>
    </table>
@endforeach