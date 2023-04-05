<div class="admin_banners__filter">
</div>

<table class="admin_banners__list_table table table-striped table-sm table-bordered table-hover">
    <thead>
        <th>Slika</th>
        <th>Naziv</th>
        <th>Strana </th>
        <th>Pozicija</th>
        <th>Broj klikova</th>
        <th>Otvori</th>
        <th>Status</th>
        <th>Promeni</th>
        <th>Obriši</th>
    </thead>
    <tbody id="admin_banners__list_content"></tbody>
</table>

<nav
    aria-label  = "Strane"
    class       = "center-block text-center clearfix"
    role        = "group"
>
    <button
        class="btn btn-default invisible"
        id="admin_banners__list__prev"
        type="button"
    >
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Nazad</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_banners__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Napred</span>
    </button>
</nav>

<script type="text/html" id="admin_banners__list_temp" >
    @if ($permissions['banner_read'])
        <%for(var i = 0, l = banners.length; i < l; i++) {%>
            <%var banner = banners[i];%>
            <tr class = "admin_banner__row_<%= banner.id %>">
                <?php /*Slika*/ ?>
                <td>
                    <input
                        class           = "hidden"
                        data-banner-id = "<%= banner.id %>"
                        id              = "admin_banners__image_change"
                        type            = "file"
                    />
                    <img
                        alt             = "<%= banner.title %> picture"
                        class           = "admin_banners__list_picture"
                        src             = "/uploads_static/originals/<%= banner.image %>"
                        width           = "100"
                    />
                </td>

                <?php /*naziv*/ ?>
                <td>
                    <%= banner.title %>
                </td>

                <?php /*pozicija*/ ?>
                <td>

                        <%= banner.position.page_type.type %>
                </td>
                <td>
                        <%= banner.position.position %>
                </td>

                <?php /*Broj pregleda*/?>
                <td>
                    <%= banner.nr_clicks %>
                </td>

                <?php /*Otvori*/ ?>
                <td>
                    <a class="btn btn-warning" href="/<%= banner.url %>">Otvori</a>
                </td>

                <?php /*Status*/?>
                <td>
                    <% if (!banner.status) {%>
                        <button
                            class           = "btn btn-success admin_banner__button_publish"
                            data-banner-id = "<%= banner.id %>"
                            type            = "button"
                        >
                            Objavi
                        </button>
                    <%} else {%>
                         <button
                            class           ="btn btn-danger admin_banner__button_return"
                            type            = "button"
                            data-banner-id = "<%= banner.id %>"
                        >
                            Povuci
                        </button>
                    <% } %>
                </td>

                <?php /*izmeni*/?>
                <td>
                    <button
                        class           = "btn btn-warning admin_banners__modal_change"
                        type            = "button"
                        data-target     = "#admin_banners__modal_change"
                        data-toggle     = "modal"
                        data-banner-id = "<%= banner.id %>"
                    >
                        Promeni
                    </button>
                </td>
                <?php /*Obriši*/?>
                <td>
                    <button
                        class           = "btn btn-danger admin_banners__modal_delete"
                        type            = "button"
                        data-target     = "#admin_banners__modal_delete"
                        data-toggle     = "modal"
                        data-banner-id = "<%= banner.id %>"
                    >
                        Obriši
                    </button>
                </td>

            </tr>
        <%}%>
    @endif
</script>
