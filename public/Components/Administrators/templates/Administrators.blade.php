<?php /*Forma za kreiranje admina */ ?>
@if ($permissions['admin_create'] && $permissions['admin_role_set'])
    <form id="admin_admins__create__form" action="" method="post" class="">
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_admins__create__input">Korisničko ime</label>
            <input
                class="form-control"
                id="admin_admins__create__input"
                maxlength="63"
                name="name"
                placeholder="Korisničko ime"
                required="required"
                type="text"
            />

            <label for="admin_admins__input__email">Email</label>
            <input
                class="form-control"
                id="admin_admins__input__email"
                maxlength="63"
                name="email"
                placeholder="Email"
                required="required"
                type="email"
            />

            <label for="admin_admins__create__role">Uloga</label>
                <select class="form-control" id="admin_admins__create__role" required="required">
                    @foreach($roles as $role)
                        @if($role->id !== 1)
                            <option value="{{ $role->id }}">
                                {{ $role->description }}
                            </option>
                        @endif
                    @endforeach
                </select>
            <input id="admin_admins__create__submit" type="submit" class="btn btn-default" value="Napravi">
        </div>
    </form>
@else
    <p><strong>
        Morate imati dozvolu za kreiranje administratora i dodeljivanje uloge
        administratorima da biste napravili novog admina
    </strong></p>
@endif










<?php /* Tabela za admine */ ?>
<table class="table table-striped table-sm table-bordered table-hover" id="admin_admins__list">
</table>










<!-- Popup(Modal) za izmenu -->
<div
    aria-labelledby="admin_admins__modal__label"
    class="modal fade"
    id="admin_admins__modal"
    role="dialog"
    tabindex="-1"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" id="admin_admins__form_edit" method="post" action="">
                {!! $csrf_field !!}
                <div class="modal-header">
                    <button
                        aria-label="Close"
                        class="close"
                        data-dismiss="modal"
                        type="button"
                    ><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="admin_admins__modal__label">Promeni</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_admins__edit__input_change_name">Korisničko ime</label>
                        <input
                            autofocus="autofocus"
                            class="form-control"
                            id="admin_admins__edit__input_change_name"
                            maxlength="63"
                            name="name"
                            placeholder="Korisničko ime"
                            required="required"
                            type="text"
                        />
                    </div>
                    <div class="form-group">
                        <label for="admin_admins__edit__input_change_email">Email</label>
                        <input
                            class="form-control"
                            id="admin_admins__edit__input_change_email"
                            maxlength="63"
                            name="email"
                            placeholder="Email"
                            required="required"
                            type="email"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <input id="admin_admins__edit__save" type="submit" class="btn btn-primary" value="Sačuvaj">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup(Modal) za brisanje admina -->
<div class="modal fade" id="admin_admins__modal_delete" tabindex="-1" role="dialog" aria-labelledby="admin_admins__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="admin_admins__modal__label">Brisanje administratora</h4>
            </div>
            <div class="modal-body">
                <p>Ovime ćete obrisati administratora. Podatke nije moguće povratiti. Da li želite da nastavite?</p>
            </div>
            <div class="modal-footer">
                <button autofocus="autofocus" type="button" class="btn btn-default" data-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="admin_admins__edit__delete">Obriši</button>
            </div>
        </div>
    </div>
</div>










<?php /* Lista admina sa mogucnoscu promene uloge razredu, promene sifre, izmene imena i email i brisanje admina*/ ?>
<script type="text/html" id="admin_admins__list__tmpl">
    <thead>
        <th class="col-md-2">Korisničko ime</th>
        <th class="col-md-2">Email</th>
        <th class="col-md-2">Uloga</th>

        @if ($permissions['admin_update'])
            <th class="col-md-1">Promena šifre</th>
            <th class="col-md-1">Promena imena i emaila</th>
        @endif

        @if ($permissions['admin_delete'])
            <th class="col-md-1">Brisanje Administratora</th>
        @endif
    </thead>
    <tbody>
        <%for(var i = 0, l = admins.length; i < l; i++) {%>
            <%var admin = admins[i];%>
            <tr>
                <td class="admin_admins__name admin_admins__name--<%= admin.id %> vert-align" data-admin_id="<%= admin.id %>">
                    <%= admin.username %>
                </td>
                <td class="admin_admins__email admin_admins__email--<%= admin.id %> vert-align" data-admin_id="<%= admin.id %>">
                    <%= admin.email %>
                </td>
                <td class="vert-align">
                    @if ($permissions['admin_role_set'])
                        <%if (admin.id !== 1) {%>
                            <select class="form-control admin_admins__dropdown__roles" data-admin_id="<%= admin.id %>">
                                <%for (var j = 0, li = roles.length; j < li; j++) {%>
                                    <%var role = roles[j];%>
                                    <%var selected = role.id === admin.role_id ? "selected='selected'" : "";%>
                                    <%if (role.id !== 1) {%>
                                        <option <%= selected %> value="<%= role.id %>"><%= role.description %></option>
                                    <%}%>
                                <%}%>
                            </select>
                        <%}%>
                    @endif
                </td>
                @if ($permissions['admin_update'])
                    <td class="vert-align">
                            <%if (show_password.id === admin.id) {%>
                                <%= show_password.password %>
                            <%} else {%>
                                <button class="btn btn-warning admin_admins__edit__button_reset admin_admins__edit__button_reset--<%= admin.id %> vert-align" data-admin_id="<%= admin.id %>" name="reset" type="button">Promeni</button>
                            <%}%>
                            <span class="admin_admins__edit__new_password admin_admins__edit__new_password--<%= admin.id %> vert-align"></span>
                    </td>
                    <td class="vert-align">
                        <!-- Button trigger modal -->
                        <button type="button" class="admin_admins__edit__button_change btn btn-warning vert-align" data-admin_id="<%= admin.id %>" data-toggle="modal" data-target="#admin_admins__modal">Promeni</button>
                    </td>
                @endif

                @if ($permissions['admin_delete'])
                    <td class="vert-align">
                        <%if (admin.id !== 1) {%>
                            <button class="btn btn-danger admin_admins__edit__button_delete vert-align" data-admin_id="<%= admin.id %>" name="delete" type="button" data-toggle="modal" data-target="#admin_admins__modal_delete">Obriši</button>
                        <%}%>
                    </td>
                @endif
            </tr>
        <%};%>
    </tbody>
</script>
