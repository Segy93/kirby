<div class="product_video">
    <iframe
        @if ($provider === 'youtube')
            allow="autoplay; encrypted-media"
            allowfullscreen
            frameborder="0"
            src="https://www.youtube.com/embed/{{ $id }}"
        @endif
    >
    </iframe>
</div>