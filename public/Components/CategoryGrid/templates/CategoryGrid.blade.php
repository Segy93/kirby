<article class = "sale_categories">
    <section class = "sale_categories__header">

    </section>
    <section class = "sale_categories__body">
        @foreach ($categories as $category)
            <section class = "sale_categories__body_single">
                <a href = "{{$category['url']}}" class = "sale_categories__body_link">
                <img class = "sale_categories__body_image" src = "{{$category['img']}}" alt = "{{$category['title']}}" />
                <section class = "sale_categories__name">
                    <div class = "sale_categories__name_heading">
                        <h3 class = "sale_categories__name_heading_element">{{$category['title']}}</h3>
                    </div>
                </section>
                </a>
            </section>
        @endforeach
    </section>
</article>