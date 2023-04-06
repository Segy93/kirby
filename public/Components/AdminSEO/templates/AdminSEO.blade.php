@if ($show_form === 'both' || $show_form === 'create')
    @if ($permissions['seo_create'])
        <form action="" id="admin_seo__form" type="post">
            {!! $csrf_field !!}
            <div class="form-group">
                <h2>SEO</h2>

                <label for="admin_seo__title">Naslov</label>
                <input class="form-control admin_seo__title" id="admin_seo__title" name="title" maxlength="255" placeholder="Naslov" required="required" type="text" />

                <label for="admin_seo__url">
                    Url<br />
                    -URL ukucan ovde će biti automatski nadovezan na www.kesezakirby.rs. <br />
                    -Ne sme biti razmaka (reči razdvajati sa "-")<br />
                    -Nije dozvoljen znak "/".<br />
                    -Koristiti samo slova engleske abecede.<br />
                </label>

                <input class="form-control admin_seo__url" id="admin_seo__url" name="url" maxlength="255" pattern="^[a-zA-Z0-9\-]+$" placeholder="Url" required="required" type="text" />

                <label for="admin_seo__image">Slika (preporučene dimenzije: 1200x630px)</label>
                <input class="form-control admin_seo__image" id="admin_seo__image" name="image" type="file" />

                <label for="admin_seo__description">Opis</label>
                <textarea class="form-control admin_seo__description" id="admin_seo__description" name="description" placeholder="Opis" required="required" type="text"></textarea>

                <label for="admin_seo__keywords">Ključne reči</label>
                <input class="form-control admin_seo__keywords" id="admin_seo__keywords" maxlength="255" name="keywords" placeholder="Ključne reči" required="required" type="text" />

                <input id="admin_seo__machine_name" name="machine_name" type="hidden" />

                <input class="admin_seo__submit" id="admin_seo__submit" type="submit" />
            </div>
        </form>
    @endif
@endif









@if ($show_form === 'both' || $show_form === 'update')
    <!-- Popup(Modal) za izmenu seo -->
    <div role="dialog" tabindex="-1" id="admin_seo__modal_update" class="modal fade">
        <div role="document" class="modal-dialog">
            <div class="modal-content">
                <form action="" method="post" id="admin_seo__form_update" role="form">
                    {!! $csrf_field !!}
                    <div class="modal-header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title">Promeni</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input id="admin_seo__form_input_machine_name" name="machine_name" type="hidden" />
                            <label for="admin_seo__form_input_title">Naslov</label>
                            @if ($permissions['seo_update'])
                                <input autofocus="autofocus" type="text" required="required" placeholder="Naslov" name="name" maxlength="255" id="admin_seo__form_input_title" class="form-control" />
                            @else
                                <input type="text" required="required" placeholder="Naslov" name="name" maxlength="255" id="admin_seo__form_input_title" class="form-control" disabled />
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="admin_seo__form__url">Url</label>
                            <input class="form-control" id="admin_seo__form__url" maxlength="255" name="Url" required="required" placeholder="Url" type="text" disabled/>
                            <button class="admin_seo__form__url_button" type="button">
                                <span class="glyphicon glyphicon-lock"></span>
                            </button>
                            <p>Putanje nije preporučljivo menjati.</p>
                            <p> URL ukucan ovde će biti automatski nadovezan na www.kesezakirby.rs.</p>
                            <p>Ne sme biti razmaka (reči razdvajati sa "-") niti "/" znak. Koristiti samo slova engleske abecede.</p>
                        </div>
                        <div class="form-group">
                            <label for="admin_seo__form_input_description">Opis</label>
                            @if ($permissions['seo_update'])
                                <textarea type="text" required="required" placeholder="Opis" name="name" id="admin_seo__edit_form_input_description" class="form-control"></textarea>
                            @else
                                <textarea type="text" required="required" placeholder="Opis" name="name" id="admin_seo__edit_form_input_description" class="form-control" disabled></textarea>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="admin_seo__form_input_keywords">Ključne reči</label>
                            @if ($permissions['seo_update'])
                                <input type="text" required="required" placeholder="Ključne reči" name="name" maxlength="255" id="admin_seo__edit_form_input_keywords" class="form-control" />
                            @else
                                <input type="text" required="required" placeholder="Ključne reči" name="name" maxlength="255" id="admin_seo__edit_form_input_keywords" class="form-control" disabled />
                            @endif
                        </div>
                        <div class="admin_seo__image_wrapper">
                            <div>
                                <label for="admin_seo__thumbnail_twitter">Promena slike</label>
                                <input type="file" class="admin_seo__change__thumbnail_twitter">
                                <img alt="image_twitter" src="/Components/AdminSEO/img/add.png" class="admin_seo__thumbnail_twitter"/>
                            </div>
                            {{-- <div>
                                <label for="admin_seo__image_twitter">Image twitter</label>
                                <input type="file" class="admin_seo__change__image_twitter">
                                <img alt="image_twitter" src="/Components/AdminSEO/img/add.png" class="admin_seo__image_twitter"/>
                            </div>
                            <div>
                                <label for="admin_seo__image_open_graph">Image open graph</label>
                                <input type="file" class="admin_seo__change__image_open_graph">
                                <img alt="image_twitter" src="/Components/AdminSEO/img/add.png" class="admin_seo__image_open_graph"/>
                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" id="admin_seo__form_update__button_close" type="button">Zatvori</button>
                        @if($permissions['seo_update'])
                            <button id="admin_seo__form_save" class="btn btn-primary" type="submit">Sačuvaj</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<!-- Popup(Modal) da pita korisnika da li je siguran da izadje iz modala za promenu SEO ako je napravio izmene -->
<!--<div id="admin_seo__modal_warning" class="modal fade" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <p> Da li ste sigurni da zelite da poništite promene? </p>
                <button type="button" class="btn btn-danger" id="admin_seo__modal_warning__button_close">Da</button>
                <button data-dismiss="modal" type="button" class="btn btn-primary">Ne</button>


            </div>
        </div>
    </div>
</div>-->
