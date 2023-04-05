<?php /*Forma za kreiranje predmeta */ ?>
@if ($permissions['role_create'])
    <form id="admin_roles__create__form" action="" method="post" class="">
        {!! $csrf_field !!}
        <div class="form-group">
            <label for="admin_roles__create__input">Naziv uloge</label>
            <input
                class="form-control"
                id="admin_roles__create__input"
                maxlength="255"
                name="name"
                placeholder="Naziv uloge"
                required="required"
                type="text"
            />

            <input
                class="btn btn-default"
                id="admin_roles__create__submit"
                type="submit"
                value="Napravi"
            />
        </div>
    </form>
@endif









<?php /* Tabela za uloge */ ?>
<table class="table table-striped table-sm table-bordered table-hover" id="admin_roles__list">
</table>










<!-- Popup(Modal) za izmenu -->
<div class="modal fade" id="admin_roles__modal" tabindex="-1" role="dialog" aria-labelledby="admin_roles__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form role="form" id="admin_roles__form_edit" method="post" action="">
                {!! $csrf_field !!}
                <div class="modal-header">
                    <button
                        aria-label="Close"
                        class="close"
                        data-dismiss="modal"
                        type="button"
                    ><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="admin_roles__modal__label">Promeni</h4>
                </div>
                <div class="modal-body">
                    <label for="admin_roles__edit__input_change">Naziv uloge</label>
                    <input
                        autofocus="autofocus"
                        class="form-control"
                        id="admin_roles__edit__input_change"
                        maxlength="255"
                        name="name"
                        placeholder="Naziv uloge"
                        required="required"
                        type="text"
                    />

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
                    <button type="submit" class="btn btn-primary" id="admin_roles__edit__save">Sačuvaj</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Popup(Modal) za brisanje uloga -->
<div class="modal fade" id="admin_roles__modal_delete" tabindex="-1" role="dialog" aria-labelledby="admin_grades__modal__label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button
                    aria-label="Close"
                    class="close"
                    data-dismiss="modal"
                    type="button"
                ><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="admin_grades__modal__label">Brisanje uloge</h4>
            </div>
            <div class="modal-body">
                <p>Ovime ćete obrisati ulogu. Podatke nije moguće povratiti. Da li želite da nastavite?</p>
            </div>
            <div class="modal-footer">
                <button
                    autofocus="autofocus"
                    class="btn btn-default"
                    data-dismiss="modal"
                    type="button"
                >Odustani</button>
                <button
                    class="btn btn-danger"
                    data-dismiss="modal"
                    id="admin_roles__edit__delete"
                    type="button"
                >Obriši</button>
            </div>
        </div>
    </div>
</div>










<?php /* Lista u kojoj se ulogama mogu dodeliti dozvole*/ ?>
<script type="text/html" id="admin_roles__list__tmpl">
    <thead>
        @if ($permissions['permission_read'])
            <th>Dozvola</th>
        @endif

        @if ($permissions['role_read'])
            <%for (var i = 0, l = roles.length; i < l; i++) {%>
                <th
                    class="
                        admin_roles__description
                        admin_roles__description--<%= roles[i].id %>
                    "
                    data-role_id="<%= roles[i].id %>"
                >
                    <%= roles[i].description %>
                </th>
            <%}%>
        @endif
    </thead>

    <tbody>
        <%for (var i = 0, lo = permissions.length; i < lo; i++) {%>
            @if($permissions['permission_read'])
                <tr>
                    <td><%= permissions[i].description %></td>
                    <%for (var j = 0, li = roles.length; j < li; j++) {%>
                        <%var checked = roles[j].permissions.some(function(elem){ return elem.id === permissions[i].id; });%>
                        @if($permissions['role_read'])
                            <td>
                                @if($permissions['permission_assign'])
                                <input
                                    <%if (checked || roles[j].id === 1) {%>checked="checked"<%}%>
                                    <%if (roles[j].id === 1) {%>disabled="disabled"<%}%>
                                    class="btn btn-default admin_roles__permission"
                                    data-role_id="<%= roles[j].id %>"
                                    data-permission_id="<%= permissions[i].id %>"
                                    type="checkbox"
                                />
                                @else
                                <input
                                    <%if (checked || roles[j].id === 1) {%>checked="checked"<%}%>
                                    <%if (roles[j].id === 1) {%>disabled="disabled"<%}%>
                                    class="btn btn-default admin_roles__permission"
                                    data-role_id="<%= roles[j].id %>"
                                    data-permission_id="<%= permissions[i].id %>"
                                    type="checkbox" disabled
                                    />
                                @endif
                            </td>
                        @endif
                    <%}%>
                @endif
            </tr>
        <%}%>
        @if ($permissions['role_update'] && $permissions['role_read'] || $permissions['role_delete'] && $permissions['role_read'])
            <tr>
                <td>&nbsp;</td>
                <%for (var j = 0, li = roles.length; j < li; j++) {%>
                    <td>
                        <%if (roles[j].id !== 1) {%>
                            <!-- Button trigger modal -->
                            <div class="row admin_roles__buttons__wrapper">
                                <div class="col-sm-3">
                                    @if($permissions['role_update'])
                                        <button type="button" class="admin_roles__edit__button_change btn btn-warning" data-role_id="<%= roles[j].id %>" data-toggle="modal" data-target="#admin_roles__modal">Promeni</button>
                                    @endif
                                </div>
                                <div class="col-sm-3">
                                    @if($permissions['role_delete'])
                                        <button class="btn btn-danger admin_roles__edit__button_delete" data-role_id="<%= roles[j].id %>" type="button" data-toggle="modal" data-target="#admin_roles__modal_delete">Ukloni</button>
                                    @endif
                                </div>
                            </div>
                        <%}%>
                    </td>
                <%}%>
            </tr>
        @endif
    </tbody>
</script>
