@if ($permission)
    <table class="table table-striped table-sm table-bordered table-hover" id="admin_permissions__list">
        <thead>
        </thead>
        <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>
                        <?= $permission->description; ?>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

<script type="text/html" id="admin_permissions__list__tmpl">
    <table class="table table-striped table-sm table-bordered table-hover">
        <thead>
        </thead>
        <tbody>
            <%_.each(permissions, function(permission, id) {%>
                <tr>
                    <td>
                        <%= permission.description %>
                    </td>
                    <td>
                        <button class="btn btn-danger admin_permissions__edit__button_delete" data-permission_id="<%= permission.id %>" name="delete" type="button">Obriši</button>
                    </td>
                </tr>
            <%});%>
        </tbody>
    </table>
</script>
