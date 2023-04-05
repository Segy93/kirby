<article class = "footer">
    <div class="footer_container">
        @foreach ($static_categories as $category)
            <nav class = "footer_item">
                <h3 class = "footer_heading">{{$category->name}}</h3>
                {!! $basic_information->renderHTML($category) !!}
            </nav>
        @endforeach

        <section class = "footer_item">
            <h3 class = "footer_heading footer_news__headline">It vesti</h3>
            {!! $news->renderHTML() !!}
        </section>

        <section class = "footer_item">
            <h3 class = "footer_heading footer_contact__headline" >Kontakt informacije</h3>
            {!! $contact->renderHTML() !!}
        </section>

        <!--<section class = "footer_item">
            <h3 class = "footer_heading footer_work__time_headline">Radno vreme</h3>
            {!! $worktime->renderHTML() !!}
        </section> !-->

        <section class = "footer_social footer_item">
            {!! $social_share_lg->renderHTML() !!}
        </section>

        {!! $info->renderHTML() !!}
    </div>
</article>
