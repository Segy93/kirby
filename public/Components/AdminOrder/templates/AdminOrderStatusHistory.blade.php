
<div
    aria-labelledby     = "admin_orders__status_history__label"
    class               = "modal fade"
    id                  = "admin_orders__status_history"
    role                = "dialog"
    tabindex            = "-1"
>
    <div
        class   = "modal-dialog"
        role    = "document"
    >
        <div class="modal-content">
            <div class="modal-header">
                <button
                    aria-label      = "Close"
                    class           = "close"
                    data-dismiss    = "modal"
                    type            = "button"
                >
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4
                    class   = "modal-title"
                    id      = "admin_orders__status_history__label"
                >
                    Istorija statusa
                </h4>
            </div>
            <div class="modal-body admin_orders__status_history_body">

            </div>
        </div>
    </div>
</div>


<script type="text/html" id="admin_orders__status_history_tmpl">
    <form
        action          = ""
        class           = ""
        id              = ""
    >
        {!! $csrf_field !!}
        <table class = "table">
            <thead>
                <th>Datum</th>
                <th>Status</th>
                <th>Komentar admin</th>
                <th>Komentar korisnik</th>
                <th>Autor</th>
            </thead>
            <tbody>
                <% if(typeof statuses !== 'undefined') {%>
                    <%for( var i = 0, l = statuses.length; i < l; i++ ) {%>
                    <% var status = statuses[i] %>
                        <tr>
                            <td>
                                <%= status.date_formatted %>
                            </td>
                            <td>
                                <%= status.status %>
                            </td>
                            <td>
                                <%= status.comment_admin %>
                            </td>
                            <td>
                                <%= status.comment_user %>
                            </td>
                            <td>
                                <%= status.admin? status.admin: status.user %>
                            </td>
                        </tr>
                <% }} %>
            </tbody>
        </table>
        <div class="modal-footer">
            <button
                autofocus       = "autofocus"
                class           = "btn btn-default"
                data-dismiss    = "modal"
                type            = "button"
            >
                Napusti
            </button>
        </div>
    </form>
</script>
