<div class="row">
    <!--<div class="col-md-4">
        <button class="btn btn-success" id="admin_users__list__csv" type="button">Prebaci u tabelu</button>
    </div>-->

    <div class="col-md-4">
        <form class="input-group" id="admin_users__list__search">
            <input
                class       = "form-control admin_users__search"
                name        = "input"
                placeholder = "Pretraga korisnika po korisničkom imenu"
                type        = "search"
            />
            {!! $csrf_field !!}
            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">Pretraga</button>
            </span>
        </form>
    </div>

    <div id="admin_users__stats" class="col-md-4">
    </div>
</div>

<?php /* Tabela za korisnike */ ?>
<table class="admin_users__list__table table table-striped table-sm table-bordered table-hover">
    <thead>
        @if ($permissions['user_update'])
            <th class="col-md-1">Profilna slika</th>
            <th class="col-md-1">Korisničko ime</th>
            <th class="col-md-1">Datum registracije</th>
            <th class="col-md-1">Poslednja prijava</th>
            <th class="col-md-1">Informacije</th>
            <th colspan = "2" class="col-md-1">Akcije</th>
        @endif
    </thead>


    <tbody id="admin_users__list__content">
    </tbody>
</table>

<nav aria-label="Page navigation" class="center-block text-center clearfix" role="group">
    <button type="button" class="btn btn-default invisible" id="admin_users__list__prev">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_users__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
    </button>
</nav>





<script type="text/html" id="admin_users__list__tmpl">
    @if ($permissions['user_read'])
        <%for(var i = 0, l = users.length; i < l; i++) {%>
            <%var user = users[i];%>
            <tr>
                <?php /* Slika */ ?>
                @if ($permissions['user_update'])
                    <td class="col-md-1 vert-align">
                        @if ($permissions['user_update'])
                            <input class="hidden admin_users__list__avatar_input" data-user-id="<%= user.id %>" type="file">
                        @endif

                        <img
                            alt             = "<%= user.name %>"
                            class           = "admin_users__list__avatar"
                            height          = "30"
                            src             = "<%= user.profile_picture_full %>"
                        />
                    </td>
                @endif
                <td class="col-md-1 vert-align">
                    <%= user.username %>
                </td>
                <td class="col-md-1 vert-align">
                    <%= user.registration_date.date.slice(0,-7) %>
                </td>
                <td class="col-md-1 vert-align">
                    <% if(user.last_visited !== null ) {%>
                    <%= user.last_visited.date.slice(0,-7) %>
                    <% } %>
                </td>
                <td class="col-md-1 vert-align">
                        <button
                            class           = "btn btn-warning vert-align admin_users__list__edit"
                            data-target     = "#admin_users__modal_edit"
                            data-toggle     = "modal"
                            data-user-id    = "<%= user.id%>"
                            type            = "button"
                        >
                            Info
                        </button>
                        <a class           = "btn btn-warning vert-align" href = "admin/korisnici/adrese/<%= user.id%>">Adrese</a>
                </td>
                <td class="col-md-2 vert-align">
                    @if ($permissions['user_update'])
                        <button
                            class           = "btn btn-warning vert-align admin_users__list__wishlist"
                            data-target     = "#admin_users__list__wishlist"
                            data-toggle     = "modal"
                            data-user-id    = "<%= user.id %>"
                            type            = "button"
                        >
                            Lista želja
                        </button>
                    @endif
                    @if ($permissions['user_update'])
                        <button
                            class           = "btn btn-warning vert-align admin_users__list__cart"
                            data-target     = "#admin_users__list__cart"
                            data-toggle     = "modal"
                            data-user-id    = "<%= user.id %>"
                            type            = "button"
                        >
                            Korpa
                        </button>
                    @endif
                    @if ($permissions['user_read'])
                        <a
                            class   = "btn btn-warning vert-align admin_users__list__cart"
                            href    = "/admin/narudzbine/?search=<%= user.id %>"
                        >
                            Narudžbine
                        </a>
                    @endif
                    @if ($permissions['user_update'])
                        <!-- Ban lista -->
                        <div class="btn-group admin_users__btn_group">
                            <div class="btn-group">
                                <% if (user.status === 1 && user.banned === null || user.banned !== null && new Date(user.banned.date).getTime() > new Date().getTime()) { %>
                                    <button
                                        class           = "btn btn-danger admin_users__list__ban_permanent"
                                        data-status     = "false"
                                        data-user-id    = "<%= user.id %>"
                                        type            = "button"
                                    >
                                        Skini ban
                                    </button>
                                <% } else { %>
                                    <button
                                        class           = "btn btn-danger admin_users__list__ban_permanent"
                                        data-status     = "true"
                                        data-user-id    = "<%= user.id %>"
                                        type            = "button"
                                    >
                                        Definitivan ban
                                    </button>

                                    <button
                                        type            = "button"
                                        class           = "btn btn-danger dropdown-toggle"
                                        data-toggle     = "dropdown"
                                        aria-haspopup   = "true"
                                        aria-expanded   = "false"
                                    >
                                        <span class="caret"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a
                                                class               = "admin_users__list__ban_temporary"
                                                data-ban-length     = "1"
                                                data-user-id        = "<%= user.id %>"
                                                href                = "#"
                                            >
                                                1 dan
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                class               = "admin_users__list__ban_temporary"
                                                data-ban-length     = "7"
                                                data-user-id        = "<%= user.id %>"
                                                href                = "#"
                                            >
                                                7 dana
                                            </a>
                                        </li>

                                        <li>
                                            <a
                                                class               = "admin_users__list__ban_temporary"
                                                data-ban-length     = "30"
                                                data-user-id        = "<%= user.id %>"
                                                href                = "#"
                                            >
                                                1 mesec
                                            </a>
                                        </li>
                                    </ul>
                                    <?php /* Ban informacije */ ?>
                    
                                </div>
                            <% } %>
                            <div>
                                <% if (user.status === 1 && user.banned === null) {%>
                                    <span class="label label-danger">Banovan je za stalno</span>
                                <% } else if (user.banned !== null && new Date(user.banned.date).getTime() > new Date().getTime()) { %>
                                    <span class="label label-danger">Korisnik je banovan do: <%= user.banned.date.slice(0,-7) %></span>
                                <% } %>
                            </div>
                        </div>
                    @endif
                    </td>
                    <td class="col-md-1 vert-align">
                        <button
                            class           = "btn btn-warning vert-align admin_users__list__activation admin_users__list__activation--<%= user.id %>"
                            data-user-id    = "<%= user.id %>"
                            type            = "button"
                        >
                            Aktivacioni email
                        </button>
                        @if ($permissions['user_update'])
                        <button
                            class           = "btn btn-warning vert-align admin_users__list__password_email admin_users__list__password_email--<%= user.id %>"
                            data-user-id    = "<%= user.id %>"
                            type            = "button"
                        >
                            Slanje email-a za reset lozinke
                        </button>
                    @endif
                    @if ($permissions['user_delete'])
                        <button
                            class           = "btn btn-danger vert-align admin_users__list__delete"
                            data-target     = "#admin_users__modal_delete"
                            data-toggle     = "modal"
                            data-user-id    = "<%= user.id %>"
                            type            = "button"
                        >
                            Obriši
                        </button>
                    @endif
                    
                </td>
            </tr>
        <% } %>
    @endif
</script>
