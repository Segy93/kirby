<ol class="breadcrumbs" itemtype="https://schema.org/BreadcrumbList">
    <?php 
        $i = 0;
        $count = count($links); 
    ?>
    @foreach ($links as $name => $link)
        <li class="breadcrumbs_single" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <meta itemprop="position" content="{{ $i++ }}" />
            @if ($loop->last || $name === 'Korisnik')
                <p
                    class="
                        breadcrumbs_item
                        breadcrumbs_item--text
                    "
                    itemprop="item"
                >
                    <span itemprop="name">{{ $name }}</span>
                </p>
            @else
                <a
                    itemprop="item"
                    class="
                        breadcrumbs_item
                        breadcrumbs_item--link
                    "
                    href="{{ $link }}"
                >
                    <span itemprop="name">{{ $name }}</span>
                </a>
            @endif
        </li>
    @endforeach
</ol>
