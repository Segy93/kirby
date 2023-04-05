<li class="comment_single" id = "comment_single__ancor_{!!$js_template? '<%= comment.id%>' : $comment->id !!}">
    <input
        class           = "comment_single__reply_toggle comment_single__reply_toggle--{!!$js_template? '<%= comment.id%>' : $comment->id !!} common_landings__visually_hidden"
        id              = "comment_single__reply_toggle--{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
        type            = "checkbox"
        data-comment-id = "{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
    />
    <div class="comment_single__heading">
        <h5 class="comment_single__author" itemprop = "creator">
            {!!$js_template? '<%= comment.user.username%>' : $comment->user->username !!}
        </h5>

        <time
            itemprop    = "commentTime"
            class       = "comment_single__time"
            datetime    = "{!!$js_template? '<%= comment.date_time%>' : $comment->dateTime() !!}"
        >
            {!!$js_template? '<%= comment.date_formatted %>' : $comment->formattedDate() !!}
        </time>

        @if ($is_logged)
            <label
                class   = "comment_single__reply"
                for     = "comment_single__reply_toggle--{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
            >
                <svg
                    class   = "icon comments__post_icon--send"
                >
                    <use
                        xlink:href="#comments__post_icon--send"
                    >
                    </use>
                </svg>
                odgovori
            </label>
        @endif
    </div>

    <img
        alt     = "{!!$js_template? '<%= comment.user.username%>' : $comment->user->username !!}"
        class   = "comment_single__avatar"{
        src     = "{!!$js_template? '<%= comment.user.profile_picture_small%>' : $comment->user->profile_picture_small !!}"
    />

    <p class="comment_single__text" itemprop = "commentText">
        {!!$js_template? '<%= comment.text%>' : $comment->text !!}
    </p>

    @if ($comment_read && $comment_update)
        <button
            class           = "comments__post comment_single__approve"
            data-comment-id = "{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
            data-status     = "{!!$js_template? '<%= comment.approved? 1:0%>' : $comment->approved !!}"
            type            = "button"
        >
            @if ($js_template)
                <%if (comment.approved === false) {%>
                    <svg class="comments__post_icon comments__post_icon--allow">
                        <use xlink:href="#comments__post_icon--allow"></use>
                    </svg>
                    <span class="comments__post_text_button comments__post_text_button--allow">
                        Odobri
                    </span>
                    <% } else {%>
                    <svg class="comments__post_icon comments__post_icon--disallow">
                        <use xlink:href="#comments__post_icon--disallow"></use>
                    </svg>
                    <span class="comments__post_text_button comments__post_text_button--disallow">
                        Povuci
                    </span>
                <%}%>
            @else
                @if ($comment->approved === false)
                    <svg class="comments__post_icon comments__post_icon--allow">
                        <use xlink:href="#comments__post_icon--allow"></use>
                    </svg>
                    <span class="comments__post_text_button comments__post_text_button--allow">
                        Odobri
                    </span>
                @else
                    <svg class="comments__post_icon comments__post_icon--disallow">
                        <use xlink:href="#comments__post_icon--disallow"></use>
                    </svg>
                    <span class="comments__post_text_button comments__post_text_button--disallow">
                        Povuci
                    </span>
                @endif
            @endif
        </button>
    @endif

    @if ($is_logged)
        <form
            action      = "/comment_post_new"
            class       = "comment_single__reply_form"
            id          = "comment_single__reply_form"
            method      = "post"
            itemprop    = "replyToUrl"
        >
            {!! $csrf_field !!}

            <textarea
                class="comment_single__reply_text comment_single__reply_text--{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
                maxlength="8000"
                name="text"
                placeholder="Odgovori"
                required="required"
            ></textarea>

            <input
                name="node_id"
                type="hidden"
                value="{!!$js_template? '<%= node_id%>' : $node_id !!}"
            />

            <input type="hidden" name="type" value="{{ $type }}">

            <input
                name="parent_id"
                type="hidden"
                value="{!!$js_template? '<%= comment.id%>' : $comment->id !!}"
            />

            <button class="comments__post comment_single__reply_submit" type="submit">
                <svg class="comments__post_icon">
                    <use xlink:href="#comments__post_icon--oblacic"></use>
                </svg>
                <span class="comments__post_text_button">
                    Odgovori
                </span>
            </button>
        </form>

        @if (isset($error_reply) && $error_reply_id === $comment->id)
            @if ($error_reply === 3)
                <p class="comment_single__form__error" role="alert">Tekst komentara nije odgovarajućeg formata.</p>
            @endif
        @endif
    @endif

    <ul class="comment_single__replies">
        @if ($js_template)
            <?php // i_single i l_single se koriste zato sto spoljna petlja koristi i i l pa je dolazilo do gazenja promenljivih ?>
            <%for(var i_single = 0, l_single = comment.replies.length; i_single < l_single; i_single++) {%>
                <%var reply = comment.replies[i_single];%>
                <li class="comment_single__reply_single">
                    <div class="comment_single__heading">
                        <h5
                            class       = "comment_single__author"
                            itemprop    = "creator"
                        >
                            <%= comment.user.username%>
                        </h5>
                        <time
                            itemprop    = "commentTime"
                            class       = "comment_single__time"
                            datetime    = "<%= reply.date_time %>"
                        >
                            <%= reply.date_formatted %>
                        </time>
                    </div>

                    <img
                        alt     = "<%= reply.user.username%>"
                        class   = "comment_single__avatar"
                        src     = "<%= reply.user.profile_picture_small %>"
                    />

                    <p class="comment_single__text">
                        <%= reply.text%>
                    </p>

                    @if ($comment_read && $comment_update)
                        <button
                            class           = "comments__post comment_single__approve"
                            data-comment-id = "{!! '<%= reply.id%>'!!}"
                            data-status     = "{!! '<%= reply.approved? 1:0%>' !!}"
                            type            = "button"
                        >
                            @if ($js_template)
                                <%if (reply.approved === false) {%>
                                    <svg class="comments__post_icon comments__post_icon--allow">
                                        <use xlink:href="#comments__post_icon--allow"></use>
                                    </svg>
                                    <span class="comments__post_text_button comments__post_text_button--allow">
                                        Odobri
                                    </span>
                                    <% } else {%>
                                    <svg class="comments__post_icon comments__post_icon--disallow">
                                        <use xlink:href="#comments__post_icon--disallow"></use>
                                    </svg>
                                    <span class="comments__post_text_button comments__post_text_button--disallow">
                                        Povuci
                                    </span>
                                <%}%>
                            @else
                                @if ($comment->approved === false)
                                    <svg class="comments__post_icon comments__post_icon--allow">
                                        <use xlink:href="#comments__post_icon--allow"></use>
                                    </svg>
                                    <span class="comments__post_text_button comments__post_text_button--allow">
                                        Odobri
                                    </span>
                                @else
                                    <svg class="comments__post_icon comments__post_icon--disallow">
                                        <use xlink:href="#comments__post_icon--disallow"></use>
                                    </svg>
                                    <span class="comments__post_text_button comments__post_text_button--disallow">
                                        Povuci
                                    </span>
                                @endif
                            @endif
                        </button>
                    @endif
                </li>
            <%}%>
        @else
            @foreach ($replies as $reply)
                <li class="comment_single__reply_single">
                    <div class="comment_single__heading">
                        <h5
                            class       = "comment_single__author"
                            itemprop    = "creator"
                        >
                            {{$reply->user->username}}
                        </h5>
                        <time class="comment_single__time" datetime="{{$reply->dateTime()}}">{{$reply->formattedDate()}}</time>
                    </div>

                    <img
                        alt     = "{{$reply->user->username}}"
                        class   = "comment_single__avatar"
                        src     = "{{$reply->user->profile_picture_small}}"
                    />

                    <p class="comment_single__text"  itemprop = "commentText">
                        {{$reply->text}}
                    </p>

                    @if ($comment_read && $comment_update)
                        <button
                            class           = "comments__post comment_single__approve"
                            data-comment-id = "{!! $reply->id !!}"
                            data-status     = "{!! $reply->approved !!}"
                            type            = "button"
                        >
                            @if ($js_template)
                                <%if (reply.approved === false) {%>
                                <svg class="comments__post_icon comments__post_icon--allow">
                                    <use xlink:href="#comments__post_icon--allow"></use>
                                </svg>
                                <span class="comments__post_text_button comments__post_text_button--allow">
                                    Odobri
                                </span>
                                <% } else {%>
                                <svg class="comments__post_icon comments__post_icon--disallow">
                                    <use xlink:href="#comments__post_icon--disallow"></use>
                                </svg>
                                <span class="comments__post_text_button comments__post_text_button--disallow">
                                    Povuci
                                </span>
                                <%}%>
                            @else
                                @if ($reply->approved === false)
                                <svg class="comments__post_icon comments__post_icon--allow">
                                    <use xlink:href="#comments__post_icon--allow"></use>
                                </svg>
                                <span class="comments__post_text_button comments__post_text_button--allow">
                                    Odobri
                                </span>
                                @else
                                <svg class="comments__post_icon comments__post_icon--disallow">
                                    <use xlink:href="#comments__post_icon--disallow"></use>
                                </svg>
                                <span class="comments__post_text_button comments__post_text_button--disallow">
                                    Povuci
                                </span>
                                @endif
                            @endif
                        </button>
                    @endif
                </li>
            @endforeach
        @endif
    </ul>
</li>
