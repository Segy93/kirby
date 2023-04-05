{{-- Pravljenje kategorija --}}
@if ($permissions['category_create'])
    <form
        action=""
        class=""
        enctype="multypart/form-data"
        id="admin_categories__create_form"
        method="post"
    >
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_categories__input_name">Ime kategorije</label>

            <input
                class="form-control"
                id="admin_categories__input_name"
                maxlength="63"
                name="name"
                placeholder="Ime kategorije"
                required="required"
                type="text"
            />

            <label for="admin_categories__input_image">Slika</label>

            <input
                class="form-control"
                id="admin_categories__input_image"
                name="image"
                required="required"
                type="file"
            />

            <input
                class="btn btn-default"
                id="admin_categories__button_submit"
                type="submit"
                value="Napravi"
            />
        </div>
    </form>
@endif