
<table class="admin_comments__filters table table-striped">
    <h3>Filteri</h3>
    <thead>
        <th>Neobjavljeni | Svi</th>
        <th>Tip</th>
        <th>Pretraga</th>
    </thead>
    <tbody id="admin_comments__filters_content">
        <tr>
            <td>
                <label class="admin_comments__switch">
                    <input
                        id = "admin_comments__published_toggle"
                        class = "admin_comments__input"
                        type="checkbox" checked
                    />
                    <span class="admin_comments__slider admin_comments__round"></span>
                </label>
            </td>
            <td>          
                <select class = "form-control" id = "admin_comments__type" name = "type">
                    <option value = "Article">Članci</option>
                    <option value = "Product">Proizvodi</option>
                </select>
            </td>
            <td>
                <form class="admin_comments__search_form" id = "admin_comments__search_form">
                    {!! $csrf_field !!}
                    <input
                        id          = "admin_comments__search"
                        class       = "admin_comments__search form-control"
                        placeholder = "Pretraga"
                        name        = "search"
                        type        = "text"
                    >
                </form>
            </td>
        </tr>
    </tbody>
</table>
<table class="admin_comments__table table table-striped">
    <thead>
        <th>Tekst</th>
        <th>Datum</th>
        <th>Korisnik</th>
        <th>Proizvod/Članak</th>
        <th>Status</th>
        <th>Obriši</th>
    </thead>
    <tbody id="admin_comments__table_content"></tbody>
</table>

<nav
    aria-label  = "Strane"
    class       = "center-block text-center clearfix"
    role        = "group"
>
    <button
        class="btn btn-default invisible"
        id="admin_comments__list__prev"
        type="button"
    >
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Nazad</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_comments__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Napred</span>
    </button>
</nav>


<script type="text/html" id="admin_comments__table_temp" >
    @if ($permissions['comment_read'])
        <%var timezone_offset = new Date().getTimezoneOffset();%>
        <%for(var i = 0, l = comments.length; i < l; i++) {%>
            <%var comment = comments[i];%>

            <tr class = "admin_comment__row_<%= comment.id %>">


                <?php /*naslov*/ ?>
                <td>
                    <%= comment.text %>
                </td>

                <?php /*Datum*/?>
                <td>
                    <%= comment.date.date.substr(0,19) %>
                </td>

                <?php /*Korisnik*/?>
                <td>
                    <%= comment.user.username %>
                </td>


                <?php /*Otvori*/ ?>
                <td>
                    <a class="btn btn-warning" href="/<%= comment.product ? comment.product.url : comment.article.url %>">Otvori</a>
                </td>

                <?php /*Status*/?>
                <td>        
                    <input
                        class           = "
                            admin_comment__status
                            btn
                            <%= comment.approved? 'btn-danger': 'btn-success' %>
                        "
                        data-comment-id = <%= comment.id%> 
                        data-status     = <%= comment.approved? 1:0%>
                        type            = "button"
                        value           = "<%= comment.approved? 'Povuci' : 'Odobri' %>"
                    />
                </td>

                <?php /*Obriši*/?>
                <td>
                    <button
                        class           = "btn btn-danger admin_comments__modal_delete"
                        type            = "button"
                        data-target     = "#admin_comments__modal_delete"
                        data-toggle     = "modal"
                        data-comment-id = "<%= comment.id %>"
                    >
                        Obriši
                    </button>
                </td>

            </tr>
        <%}%>
    @endif
</script>

