<ul class = "product_gallery__wrapper">
    @foreach ($pictures as $index => $picture)
        <li class="product_gallery__item">
            <img
                alt = "Slika {{ $product_name }}"
                class = "product_gallery__thumbnail"
                data-jslghtbx = "{{ $full_images[$index] }}"
                data-jslghtbx-group="grupa1"
                src = "{{ $picture }}"
                tabindex = "0"
            />
        </li>
    @endforeach
</ul>
