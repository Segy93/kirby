<script src='https://www.google.com/recaptcha/api.js?hl=sr' nonce="{{$_SESSION['token']}}" ></script>
<section class="comments" id="comments">
    <div class="comments__display" id="comments__display">

        <ul class="comments__list" itemscope itemtype = "http://schema.org/UserComments">
            @foreach ($comments as $comment)
                {!!$comment_single->renderHTML($comment, $type)!!}
            @endforeach
        </ul>
    </div>

    @if ($comment_permission)
        <form action="/comment_post_new" class="comments__create" id="comments__create" method="post">
            {!! $csrf_field !!}
            <h3 class="comments__heading">
                <label for = "comments__post_text">Ostavi komentar:</label>
            </h3>


            <input
                id      = "comments_list__node_id"
                name    = "node_id"
                type    = "hidden"
                value   = "{{ $node_id }}"
            >


            <input
                id      = "comments_list__page_type"
                name    = "type"
                type    = "hidden"
                value   = "{{ $type }}"
            >
            <textarea class="comments__post_text" id = "comments__post_text" maxlength="8000" name="text" placeholder="Ostavi komentar" required="required"></textarea>

            <div class="g-recaptcha login_form__captcha" data-sitekey="{{ $site_key }}" data-theme="dark"></div>
            <button class = "comments__post" type = "submit">
                <svg class="comments__post_icon">
                    <use xlink:href="#comments__post_icon--oblacic"></use>
                </svg>
                <span class="comments__post_text_button">
                    Pošalji
                </span>
            </button>

            @if (isset($error_comment) && $error_comment === 3)
                <p class="comments_form__error" role="alert" >Tekst komentara nije odgovarajućeg formata</p>
            @endif
        </form>
    @else
        <input type="hidden" id = "comments_list__node_id" name="node_id" value="{{ $node_id }}" />
        <input type="hidden" id = "comments_list__page_type" name="type" value="{{ $type }}" />
        <p class="comments_list__login_notification">
            Da biste mogli da ostavite komentar, morate da se <a class="common_landing__link" href="/prijava">prijavite</a> ili da se <a class="common_landing__link" href="/registracija">registrujete</a> na sajt <a class="common_landing__link" href="/">monitor.rs</a>
        </p>
    @endif

    <input
        class   = "comments__post comments__more {{$more ? '' : 'common_landings__visually_hidden'}}"
        type    = "button"
        value   = "Učitaj još"
    />

    <script type="text/html" id="comment_list__tmpl">
        <%for (var i = 0, l = comments.length; i < l; i++) {%>
            <%var comment = comments[i];%>
            <%var node_id = {{ $node_id }};%>
            {!! $comment_single->renderHTML(null, $type) !!}
        <%}%>
    </script>
</section>
