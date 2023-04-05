
<div
    aria-labelledby     = "admin_order__add_product__label"
    class               = "modal fade"
    id                  = "admin_order__add_product"
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
                    id      = "admin_order__add_product__label"
                >
                    Dodaj proizvod
                </h4>
            </div>
            <div class="modal-body admin_order__add_product_body">
            <form action = "" class = "admin_order__add_product__find">
                <label
                    for = "admin_order__find_product"
                >
                    ArtId ili naziv proizvoda
                </label>
                <input
                    type    = "text"
                    class   = "admin_order__find_product form-control"
                    id      = "admin_order__find_product"
                    name    = "admin_order__find_product"
                />

                <input type = "submit" class = "btn btn-primary" value = "Pretraga"/>
            </form>
            </div>
            <div
                id = "admin_order__add_product__wrapper"
            >
            </div>
        </div>
    </div>
</div>


<script type="text/html" id="admin_order__add_products_tmpl">
    <form
        action          = ""
        enctype         = "multipart/form-data"
        id              = "admin_order__product_add"
        method          = "post"
        role            = "form"
    >
        {!! $csrf_field !!}
        <div class = "modal-body">
            <table class = "table">
                <thead>
                    <th>Naziv<th>
                    <th>Količina</th>
                </thead>
                <tbody>
                    <% if(typeof queried_products !== 'undefined') {%>
                        <%for( var i = 0, l = queried_products.length; i < l; i++ ) {%>
                        <% var product = queried_products[i] %>
                            <tr>
                                <td>
                                    <%= product.name %>
                                <td>
                                <td>
                                    <input
                                        class           = "admin_order__find_product__single form-control"
                                        data-product_id = "<%= product.id %>"
                                        id              = "admin_order__find_product__single"
                                        min             = "1"
                                        name            = "admin_order__find_product__single"
                                        oninput         = "this.value = Math.abs(this.value)"
                                        type            = "number"
                                        value           = "1"
                                    />
                                </td>
                            </tr>
                    <% }} %>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button
                autofocus       = "autofocus"
                class           = "btn btn-default"
                data-dismiss    = "modal"
                type            = "button"
            >
                Napusti
            </button>
            <button
                class           = "btn btn-success"
                type            = "submit"
            >
                Sačuvaj
            </button>
        </div>
    </form>
</script>
