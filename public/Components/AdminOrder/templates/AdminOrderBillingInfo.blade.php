<!-- Popup(Modal) za listanje liste zelja korisnika -->
<div
    aria-labelledby     = "admin_order__billing_info__label"
    class               = "modal fade"
    id                  = "admin_order__billing_info"
    role                = "dialog"
    tabindex            = "-1"
>
</div>


<script type="text/html" id="admin_order__address_billing_tmpl">
    <div class="modal-dialog" role="document">
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

                <h4 class="modal-title" id="admin_order__billing_info__label">Izmena podataka za plaćanje</h4>
            </div>

            <form
                action          = ""
                enctype         = "multipart/form-data"
                id              = "admin_order__billing_info_form"
                data-address_id = "<%= address.id %>"
                method          = "post"
                role            = "form"
            >
                {!! $csrf_field !!}
                <div class="modal-body admin_order__billing_info_body">
                    <label for = "admin_order__billing_info__name">Ime</label>
                    <input
                        class       = "admin_order__billing_info__name form-control"
                        id          = "admin_order__billing_info__name"
                        maxlength   = "63"
                        name        = "billing_info__name"
                        required    = "required"
                        type        = "text"
                        value       = "<%= address.contact_name? address.contact_name : '' %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
                    <label for = "admin_order__billing_info__surname">Prezime</label>
                    <input
                        class       = "admin_order__billing_info__surname form-control"
                        id          = "admin_order__billing_info__surname"
                        maxlength   = "63"
                        name        = "billing_info__surname"
                        required    = "required"
                        type        = "text"
                        value       = "<%= address.contact_surname? address.contact_surname : '' %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
                    <label for = "admin_order__billing_info__company">Naziv firme</label>
                    <input
                        class       = "admin_order__billing_info__company form-control"
                        id          = "admin_order__billing_info__company"
                        maxlength   = "63"
                        name        = "billing_info__company"
                        type        = "text"
                        value       = "<%= address.company? address.company : '' %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
                    <label for = "admin_order__billing_info__pib">PIB</label>
                    <input
                        class       = "admin_order__billing_info__pib form-control"
                        id          = "admin_order__billing_info__pib"
                        maxlength   = "63"
                        name        = "billing_info__pib"
                        type        = "text"
                        value       = "<%= address.pib? address.pib : '' %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
                    <label for = "admin_order__billing_info__address">Adresa</label>
                    <input
                        class       = "admin_order__billing_info__address form-control"
                        id          = "admin_order__billing_info__address"
                        maxlength   = "127"
                        name        = "billing_info__address"
                        required    = "required"
                        type        = "text"
                        value       = "<%= address.address? address.address : ''  %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
                    <label for = "admin_order__billing_info__phone_nr">Telefon</label>
                    <input
                        class       = "admin_order__billing_info__phone_nr form-control"
                        id          = "admin_order__billing_info__phone_nr"
                        name        = "billing_info__phone_nr"
                        pattern     = "^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$"
                        required    = "required"
                        type        = "tel"
                        value       = "<%= address.phone_nr? address.phone_nr : ''  %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />

                    <label for = "admin_order__billing_info__city">Grad</label>
                    <input
                        class       = "admin_order__billing_info__city form-control"
                        id          = "admin_order__billing_info__city"
                        name        = "billing_info__city"
                        required    = "required"
                        type        = "tel"
                        value       = "<%= address.city? address.city : ''  %>"
                        <% if (is_shop) {%>
                            disabled
                        <%}%>
                    />
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
                    <% if (!is_shop) {%>
                        <button
                            class           = "btn btn-success"
                            type            = "submit"
                        >
                            Sačuvaj
                        </button>
                    <%}%>
                </div>
            </form>
        </div>
    </div>

</script>
