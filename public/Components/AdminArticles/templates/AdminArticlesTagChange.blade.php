<div
    area-labelledby = "admin_articles__modal_tag__label"
    class           = "modal fade"
    id              = "admin_articles__modal_tag"
    role            = "dialog"
>
    <div class="modal-dialog" id="admin_article__tag_modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    area-label   = "Close"
                    class        = "close"
                    data-dismiss = "modal"
                    type         = "button"
                    tabindex     = "-1"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-tittle" id="admin_articles__modal_tag__label">
                    Izmena tagova
                </h4>
            </div>

            <form action="" id="admin_article__tag__form" role="form">
                {!! $csrf_field !!}
                <div class="modal-body">
                    <div class="btn-group">
                        @foreach ($tags as $tag)
                            <input
                                class   = "
                                    sr-only
                                    admin_articles__change_tag
                                    admin_articles__change_tag--{{ $tag->id }}
                                "
                                id      = "admin_articles__change_tag--{{ $tag->id }}"
                                name    = "admin_articles__change_tag"
                                type    = "checkbox"
                                value   = "{{ $tag->id }}"
                            />

                            <label
                                class   = "
                                    btn btn-primary
                                    admin_articles__change_tag__label
                                "
                                for = "admin_articles__change_tag--{{ $tag->id }}"
                            >

                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="modal-footer">
                    <input
                        class           = "btn btn-primary"
                        data-dismiss    = "modal"
                        id              = "admin_articles__modal_tag__confirm"
                        type            = "button"
                        value           = "Zatvori"
                    />
                </div>
            </form>
        </div>
    </div>
</div>
