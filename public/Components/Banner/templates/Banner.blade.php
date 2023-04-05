@if (!$js_template)
    @if ($banners !== null && !empty($banners))
        @foreach ($banners as $banner)
            <a href = "{{$banner->link}}">
                <img
                    alt     = "{{$banner->title}}"
                    src     = "uploads_static/originals/{{$banner->image}}"
                    class   = "banner__image"
                    data-id = "{{$banner->id}}"
                />
            </a>
        @endforeach
    @endif
@else
    <% if (banner !== undefined) {%>
        <a href = "<%=banner.link%>">
            <img
                alt     = "<%= banner.title %>"
                class   = "banner__image"
                data-id = "<%= banner.id%>"
                src     = "uploads_static/originals/<%=banner.image%>"
            />
        </a>
    <%}%>
@endif
