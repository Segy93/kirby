@if ($show_category)
    <article class = "featured_products">
        <section class = "featured_products__category_info">
            <h2 class = "featured_products__category_name">
                {{$name}}
            </h2>

            <section class = "featured_products__category_filters">
                <ul class = "featured_products__filters_list">
                    @if ($categories)
                        @foreach ($categories as $category)
                            @if (!empty ($categories_products[$category]))
                                <li  class = "featured_products__filter_single">
                                    <label
                                        for         = "featured_products__category_products--{{str_replace(' ', '_', $category)}}"
                                        class       = "featured_products__filter_single__link"
                                    >
                                        {{$category}}
                                    </label>
                                </li>
                            @endif
                        @endforeach
                    @endif
                </ul>
            </section>
        </section>

        @if ($categories_products)
            <?php
                reset($categories_products);
            ?>

            <?php $i = 0 ?>
            <?php $count = 0 ?>
            @foreach ($categories_products as $key => $products)
                @if ($products && !empty($products))
                    <input
                        class   = "featured_products__category_products--active common_landings__visually_hidden"
                        id      = "featured_products__category_products--{{str_replace(' ', '_', $key)}}"
                        name    = "featured_products__category_products--active"
                        type    = "radio"
                        {{ $count === 0 ? 'checked' : '' }}
                    />
                    <section class = "featured_products__category_products">
                            @foreach($products as $product)
                                <section class = "featured_products__product__single">
                                    {!! $product_single__compact->renderHTML($product) !!}
                                </section>
                            @endforeach
                    </section>
                    <?php $count++ ?>
                @endif
                <?php $i++ ?>
            @endforeach
        @endif
    </article>
@endif