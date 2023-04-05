<?php /*Pravljenje kategorija*/ ?>
@if ($permissions['page_static_create'])
    <form
        id      = "{{ $page === null ? 'admin_pages__static_create__form' : 'admin_pages__static_change__form'}}"
        action  = ""
        class   = ""
        enctype = "multypart/form-data"
        method  = "post"
    >
        @if($page !== null)
            <input
                type    = "hidden"
                name    = "page_id"
                class   = "admin_pages__static_change__page_id"
                value   = {{$page->id}}
            />
        @endif
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_pages__static_input_name">Ime strane</label>
            <input
                class       = "form-control"
                id          = "admin_pages__static_input__name"
                maxlength   = "63"
                name        = "name"
                placeholder = "Ime strane"
                required    = "required"
                type        = "text"
            />
        </div>
        <div class="form-group">
            <label for="admin_pages__static_input_category">Kategorija</label>
            <select
                class       = "form-control"
                id          ="admin_pages__static_input__category" 
                name        = "category"
                required    = "required"
            >
                @foreach ($categories as $category)
                    <option
                        value = "{{$category->id}}"
                    >
                        {{$category->name}}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="admin_pages__static_input_text">Text</label>
            <input
                class       = "form-control"
                id          = "admin_pages__static_input__text"
                name        = "text"
                placeholder = "Ime strane"
                type        = "text"
            />
        </div>
        <div class="form-group">
            <input
                id      = "admin_pages__static_button__submit"
                type    = "submit"
                class   = "btn btn-default"
                value   = "{{$page === null ? 'Napravi' : 'Sačuvaj' }}"
            >
        </div>
    </form>
@endif