<!-- Popup(Modal) za login tabelu -->
<div
    aria-labelledby = "admin_users__modal_logins__label"
    class           = "modal fade"
    id              = "admin_users__modal_logins"
    role            = "dialog"
    tabindex        = "-1"
>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="admin_users__modal_logins__label">Promeni</h4>
            </div>

            <table class="modal-body table table-striped table-sm table-bordered table-hover" >
                <thead>
                    <th>IP</th>
                    <th>Datum</th>
                </thead>

                <tbody id="admin_users__modal_logins__content">
                </tbody>
            </table>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>


<script id="admin_users__modal_logins__tmpl" type="text/html">
    <% for (var i = 0, l = logins.length; i < l; i++) {%>
        <% var login = logins[i]; %>
        <tr>
            <td><%= login.ip_address %></td>
            <td><%= login.date %></td>
        </tr>
    <% } %>
</script>
