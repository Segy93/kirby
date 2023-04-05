<div class="row">
    <!--<div class="col-md-4">
        <button class="btn btn-success" id="admin_orders__list__csv" type="button">Prebaci u tabelu</button>
    </div>-->

    <div class="col-md-4">
        <form class="input-group" id="admin_orders__list__search">
            {!! $csrf_field !!}
            <input
                class       = "form-control admin_orders__list__search_input"
                name        = "input"
                placeholder = "Pretraga prema ID-u narudžbine"
                type        = "search"
                value       = "{{$search}}"
            />

            <span class="input-group-btn">
                <button class="btn btn-success" type="submit">Pretraga</button>
            </span>
        </form>
    </div>

    <div class="col-md-4">
        <select class = "form-control admin_orders__list__filter_statuses">
            <option value = "">Izaberi filter za status...</option>
            @foreach ($statuses as $key => $status)
                <option value = "{{$key}}">
                    {{ $status }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<?php /* Tabela za narudzbine */ ?>
<table class="admin_orders__list__table table table-striped table-sm table-bordered table-hover">
    <thead>
        @if ($permissions['order_update'])

            <th class="col-md-1">ID narudžbine</th>
            <th class="col-md-1">Ime i prezime</th>
            <th class="col-md-1">Korisnik</th>
            <th class="col-md-1">Cena</th>
            <th class="col-md-1">Datum narudžbine</th>
            <th class="col-md-1">Status</th>
            <th class="col-md-1">Otvori</th>
        @endif
    </thead>


    <tbody id="admin_orders__list__content">
    </tbody>
</table>

<nav aria-label="Page navigation" class="center-block text-center clearfix" role="group">
    <button type="button" class="btn btn-default invisible" id="admin_orders__list__prev">
        <span aria-hidden="true">&laquo;</span>
        <span class="sr-only">Previous</span>
    </button>

    <button type="button" class="btn btn-default" id="admin_orders__list__next">
        <span aria-hidden="true">&raquo;</span>
        <span class="sr-only">Next</span>
    </button>
</nav>





<script type="text/html" id="admin_orders__list__tmpl">
    @if ($permissions['order_read'])
        <%for(var i = 0, l = orders.length; i < l; i++) {%>
            <%var order = orders[i];%>
            <tr>
            <td><%= order.id %></td>
            <td><%= order.user.name %>&nbsp<%= order.user.surname %></td>
            <td><a href = "/admin/narudzbine/?search=<%= order.user.id %>"><%= order.user.username %></a></td>
            <td class = "admin_orders__list__content_price"><%= order.total_price_formatted %> RSD</td>
            <td><%= order.date_order.date.slice(0,-7) %></td>
            <td>
                <%=  order.last_update.status %>
            </td>
            <td>
                <a
                    href    = "admin/narudzbina/<%= order.id %>"
                    class   = "btn btn-success"
                >
                    Otvori
                </a>
            </td>
            </tr>
        <% } %>
    @endif
</script>
