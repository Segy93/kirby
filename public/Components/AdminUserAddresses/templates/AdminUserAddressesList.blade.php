<div class="row">
    <!--<div class="col-md-4">
        <button class="btn btn-success" id="admin_address__list__csv" type="button">Prebaci u tabelu</button>
    </div>-->

    <div class="col-md-4">
        <form class="input-group" id="admin_addresses__list__search">
            {!! $csrf_field !!}
            <input
                class       = "form-control admin_addresses__search"
                name        = "input"
                placeholder = "Pretraga adresa"
                type        = "search"
            />

            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">Pretraga</button>
            </span>
        </form>
    </div>

    <div id="admin_address__stats" class="col-md-4">
    </div>
</div>

<?php /* Tabela za korisnike */ ?>
<table class="admin_address__list__table table table-striped table-sm table-bordered table-hover">
    <thead>
        @if ($permissions['address_update'])
            <th class="col-md-1">Ime</th>
            <th class="col-md-1">Prezime</th>
            <th class="col-md-1">Telefon</th>
            <th class="col-md-1">Naziv firme</th>
            <th class="col-md-1">PIB</th>
            <th class="col-md-1">Ulica</th>
            <th class="col-md-1">Poštanski broj</th>
            <th class="col-md-1">Grad</th>
            <th class="col-md-1">Info</th>
            <th class="col-md-1">Obriši</th>
        @endif
    </thead>

    <input type = "hidden" class = "admin_addresses__user_id" data-user-id = "{{$id}}"/>
    <tbody id="admin_addresses__list__content">
    </tbody>
</table>
{{--
    Stranicenje za slucaj da bude potrebno
<nav aria-label="Page navigation" class="center-block text-center clearfix" role="group">
    <button type="button" class="btn btn-default invisible" id="admin_addresses__list__prev">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_addresses__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
    </button>
</nav> --}}




<script type="text/html" id="admin_address__list__tmpl">
    @if ($permissions['address_read'])

        <%if (addresses.length != 0) {%>
            <%for(var i = 0, l = addresses.length; i < l; i++) {%>
                <%var address = addresses[i];%>
                <tr>
                    <td class="col-md-1 vert-align">
                        <%= address.contact_name %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.contact_surname %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.phone_nr %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.company %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.pib %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.address %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.postal_code %>
                    </td>
                    <td class="col-md-1 vert-align">
                        <%= address.city %>
                    </td>
                    <td class="col-md-1 vert-align">
                        @if ($permissions['address_update'])
                            <% if (address.status === 0 || address.status === 1) {%>
                                <button
                                    class           = "btn btn-warning vert-align admin_address__list__edit"
                                    data-address-id = "<%= address.id %>"
                                    data-target     = "#admin_address__modal_edit"
                                    data-toggle     = "modal"
                                    type            = "button"
                                >
                                    Izmeni
                                </button>
                            <%} else {%>
                                Adresa je iskorišćena za naručivanje.
                            <%} %>
                        @endif
                    </td>
                    <td class="col-md-1 vert-align">
                        @if ($permissions['address_delete'])
                            <% if (address.status === 0 || address.status === 1) {%>
                            <button
                                class           = "btn btn-danger vert-align admin_address__list__delete"
                                data-target     = "#admin_address__modal_delete"
                                data-toggle     = "modal"
                                data-address-id = "<%= address.id %>"
                                type            = "button"
                            >
                                Obriši
                            </button>
                            <%} else {%>
                                Adresa je iskorišćena za naručivanje.
                            <%} %>
                        @endif
                    </td>
                </tr>

            <% } %>
        <% } else {%>
            <tr>
                <td class="col-md-1 vert-align" colspan="8">
                    Korisnik nema adresa.
                </td>
            </tr>
        <% }%>
    @endif
</script>
