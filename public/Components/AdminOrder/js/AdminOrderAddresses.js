"use strict";

if (typeof Kirby             === "undefined") var Kirby           = {};
if (typeof Kirby.AdminOrderPage  === "undefined") Kirby.AdminOrder    = {};

Kirby.AdminOrder.Addresses = {
    config: {
        delivery_address_type: "shop",
        billing_address_type: "shop",
    },

    elements: {
        delivery_form:          "#admin_order_addresses__delivery_address_form",
        billing_form:           "#admin_order_address__billing_address__form",
        notify:                 ".admin_order__addresses_notify",
        dropdown_delivery:      "#admin_order_addresses__delivery_address_dropdown",
        dropdown_billing:       "#admin_order_addresses__billing_address_dropdown",
        button_delivery_open:   "#admin_order_addresses__button_delivery_open",
        button_billing_open:    "#admin_order_addresses__button_billing_open",
        delivery_address_cont:  ".admin_order__delivery_addresses",
        billing_address_cont:   ".admin_order__billing_addresses",
        // addresses_info__name:   ".admin_order__addresses_name",
        // addresses_info__surname:".admin_order__addresses_surname",
        // addresses_info__company:".admin_order__addresses_company",
        // addresses_info__address:".admin_order__addresses_address",
        // addresses_info__phone:  ".admin_order__addresses_phone",
        // addresses_info__city:   ".admin_order__addresses_city",
        addresses_delivery__name:   ".admin_order__delivery_name",
        addresses_delivery__surname:".admin_order__delivery_surname",
        addresses_delivery__company:".admin_order__delivery_company",
        addresses_delivery__pib:    ".admin_order__delivery_pib",
        addresses_delivery__address:".admin_order__delivery_address",
        addresses_delivery__phone:  ".admin_order__delivery_phone",
        addresses_delivery__city:   ".admin_order__delivery_city",
        addresses_billing__name:    ".admin_order__billing_name",
        addresses_billing__surname: ".admin_order__billing_surname",
        addresses_billing__company: ".admin_order__billing_company",
        addresses_billing__pib:     ".admin_order__billing_pib",
        addresses_billing__address: ".admin_order__billing_address",
        addresses_billing__phone:   ".admin_order__billing_phone",
        addresses_billing__city:    ".admin_order__billing_city",
        addresses_company__row:     ".admin_order__addresses_company",
        addresses_pib__row:         ".admin_order__addresses_pib",
        addresses__row:             ".admin_order__addresses_address",
        addresses_city__row:        ".admin_order__addresses_city",
        delivery_type:              ".admin_order_addresses__delivery_type",
    },










    /**
    * Inicijalizacija komponente
    * @param   {Object}    event           JavaScript event objekat
    */
    init: function() {
        this
            .registerElements()
            .initListeners()
        ;
    },

    /**
    * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
    * Prvo idu osluskivaci na wrapera onda idu van wrapera i nakon toga idu sa uslovima
    * @return  {Object}       Kirby.AdminOrder.Addresses objekat, za ulančavanje funkcija
    */
    initListeners: function() {
        this.getElement("delivery_form").addEventListener("submit", this.formSubmitted.bind(this), false);
        this.getElement("billing_form").addEventListener("submit", this.formSubmitted.bind(this), false);
        this.getElement("dropdown_delivery").addEventListener("change", this.dropdownDeliveryChanged.bind(this), false);
        this.getElement("dropdown_billing").addEventListener("change", this.dropdownBillingChanged.bind(this), false);
        document.addEventListener("Kirby.AdminOrder.Address.Changed", this.changeOccurred.bind(this), false);
        return this;
    },

    /**
    * Registracija elemenata u upotrebi od strane komponente
    * @return  {Object} Kirby.AdminOrder.Addresses objekat, za ulančavanje funkcija
    */
    registerElements: function() {
        Kirby.Main.Dom.register("AdminOrderAddresses", this.elements);
        return this;
    },

    /**
    * Forma za promenu adrese submitovana.
    * @param event javascript event objekat
    * @return {Boolean} false
    */
    formSubmitted: function(event) {
        var form        = event.currentTarget;
        var elements    = form.elements;
        var type        = form.dataset.type;
        var address_id  = elements.address_id.value;
        var order_id    = form.dataset.order_id;

        event.preventDefault();

        this.changeAddress(type, address_id, order_id);
        return false;
    },

    dropdownDeliveryChanged: function(event) {
        var type = event.currentTarget.selectedOptions[0].dataset.type;
        var button_open = this.getElement("button_delivery_open");
        button_open.classList[type === "shop" ? "add" : "remove"]("common_landings__display_none");
    },

    dropdownBillingChanged: function(event) {
        var type = event.currentTarget.selectedOptions[0].dataset.type;
        var button_open = this.getElement("button_billing_open");
        button_open.classList[type === "shop" ? "add" : "remove"]("common_landings__display_none");
    },










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat  query_all false, niz Node objekata inace
     */
    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminOrderAddresses", element, query_all, modifier);
    },

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier  BEM modifier za selektor
     * @return  {Node/NodeList}       Vraca Node objekat je query_all false, niz Node objekata inace
     */
    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminOrderAddresses", element, query_all, modifier);
    },

    changeOccurred: function(event) {
        var element = this.getElement('notify');
        element.innerHTML = "<p style = 'color:green;' >Uspešno promenjena adresa</p>";
        setTimeout(function() {
            element.innerHTML = "";
        }, 5000);
        var form = this.getElement('delivery_form');
        var order_id = form.dataset.order_id;

        this.refreshData(order_id);
    },

    render: function(data) {
        if (data) {
            this.changeOccurred();
        }
    },

    renderData: function(data) {
        var addresses = data.addresses;
        var order     = data.order;
        var html_delivery = '';
        var html_billing  = '';
        var optgroup_delivery = this.getElement('delivery_address_cont');
        var optgroup_billing = this.getElement('billing_address_cont');
        var billing_address = null;
        var delivery_address = null;
        for (var i = 0, l = addresses.length; i < l; i++) {
            var address  = addresses[i];
            var is_delivery = order.delivery_address_id === address.id;
            var is_billing  = order.billing_address_id === address.id;
            if (is_delivery) {
                delivery_address = address;
            }

            if (is_billing) {
                billing_address = address;
            }

            html_delivery += `
                <option
                    data-type = "user"
                    value = "${address.id}"
                    ${is_delivery ? 'selected' : ''}
                >
                    ${address.contact_name } | ${address.address }
                </option>
            `;

            html_billing += `
                <option
                    data-type = "user"
                    value = "${address.id}"
                    ${is_billing ? 'selected' : ''}
                >
                    ${address.contact_name } | ${address.address }
                </option>
            `;
        }

        optgroup_billing.innerHTML  = html_billing;
        optgroup_delivery.innerHTML = html_delivery;
        this.renderAddressInfo(delivery_address, billing_address, order);
    },

    renderAddressInfo: function (delivery_address, billing_address, order) {
        // var name_element    = this.getElement("addresses_info__name");
        // var surname_element = this.getElement("addresses_info__surname");
        // var company_element = this.getElement("addresses_info__company");
        // var address_element = this.getElement("addresses_info__address");
        // var phone_element   = this.getElement("addresses_info__phone");
        // var city_element    = this.getElement("addresses_info__city");
        var delivery_name = this.getElement("addresses_delivery__name");
        var delivery_company = this.getElement("addresses_delivery__company");
        var delivery_pib = this.getElement("addresses_delivery__pib");
        var delivery_address_element = this.getElement("addresses_delivery__address");
        var delivery_phone = this.getElement("addresses_delivery__phone");
        var delivery_city = this.getElement("addresses_delivery__city");
        var billing_name = this.getElement("addresses_billing__name");
        var billing_company = this.getElement("addresses_billing__company");
        var billing_pib = this.getElement("addresses_billing__pib");
        var billing_address_element = this.getElement("addresses_billing__address");
        var billing_phone = this.getElement("addresses_billing__phone");
        var billing_city = this.getElement("addresses_billing__city");
        var company_row = this.getElement("addresses_company__row");
        var pib_row = this.getElement("addresses_pib__row");
        var address_row = this.getElement("addresses__row");
        var city_row = this.getElement("addresses_city__row");
        var delivery_type = this.getElement("delivery_type");
        if (delivery_address !== null && delivery_address.address_type !== "shop") {
            delivery_type.textContent = "Dostava na kućnu adresu";
            company_row.classList.remove("common_landings__display_none");
            pib_row.classList.remove("common_landings__display_none");
            address_row.classList.remove("common_landings__display_none");
            city_row.classList.remove("common_landings__display_none");
            this.config.delivery_address_type = "user";
            delivery_name.innerHTML = `
                ${delivery_address.contact_name}
                ${delivery_address.contact_surname}
            `;
            if (delivery_address.company !== null) {
                delivery_company.innerHTML = `
                    ${delivery_address.company}
                `;
            }

            if (delivery_address.pib !== null) {
                delivery_pib.innerHTML = `
                    ${delivery_address.pib}
                `;
            }
            delivery_address_element.innerHTML = `
                ${delivery_address.address}
            `;
            delivery_phone.innerHTML = `
                ${delivery_address.phone_nr}
            `;
            delivery_city.innerHTML = `
                ${delivery_address.city}
            `;
        } else {
            delivery_type.textContent = "Dostava u radnji";
            this.config.delivery_address_type = "shop";
            delivery_name.innerHTML = `
                ${order.user.name}
                ${order.user.surname}
            `;
            delivery_company.innerHTML = "";
            delivery_pib.innerHTML = "";
            delivery_address_element.innerHTML = "";
            delivery_phone.innerHTML = `
                ${order.user.phone_nr}
            `;
            delivery_city.innerHTML = "";
        }

        if (billing_address !== null && billing_address.address_type !== "shop") {
            this.config.billing_address_type = "user";
            company_row.classList.remove("common_landings__display_none");
            pib_row.classList.remove("common_landings__display_none");
            address_row.classList.remove("common_landings__display_none");
            city_row.classList.remove("common_landings__display_none");
            billing_name.innerHTML = `
                ${billing_address.contact_name}
                ${billing_address.contact_surname}
            `;
            if (billing_address.company !== null) {
                billing_company.innerHTML = `
                    ${billing_address.company}
                `;
            }
            if (billing_address.pib !== null) {
                billing_pib.innerHTML = `
                    ${billing_address.pib}
                `;
            }
            billing_address_element.innerHTML = `
                ${billing_address.address}
            `;
            billing_phone.innerHTML = `
                ${billing_address.phone_nr}
            `;
            billing_city.innerHTML = `
                ${billing_address.city}
            `;
        } else {
            this.config.billing_address_type = "shop";
            billing_name.innerHTML = `
                ${order.user.name}
                ${order.user.surname}
            `;
            billing_company.innerHTML = "";
            billing_pib.innerHTML = "";
            billing_address_element.innerHTML = "";
            billing_phone.innerHTML = `
                ${order.user.phone_nr}
            `;
            billing_city.innerHTML = "";
        }

        if (this.config.delivery_address_type === "shop" && this.config.billing_address_type === "shop") {
            company_row.classList.add("common_landings__display_none");
            pib_row.classList.add("common_landings__display_none");
            address_row.classList.add("common_landings__display_none");
            city_row.classList.add("common_landings__display_none");
        }
    },



    refreshData: function(order_id) {
        Kirby.Main.Ajax(
            'AdminOrder',
            'getData', {
                order_id: order_id,
            },
            this.renderData.bind(this)
        )
    },



    /**
    * Poziv ajax funkciji za promenu adrese.
    * @param type - tip adrese (delivery || billing)
    * @param address_id identifikacioni parametar adrese
    * @param order_id identifikacioni parametar narudzbine
    * @return  {Object} Kirby.AdminOrder.Addresses objekat, za ulančavanje funkcija
    */
    changeAddress: function(type, address_id, order_id) {
        Kirby.Main.Ajax(
            "AdminOrder",
            "changeAddress",
            {
                type:       type,
                address_id: address_id,
                order_id:   order_id,
            },
            function(data) {
                this.render(data);
                document.dispatchEvent(new CustomEvent("Kirby.Order.Address.Update"));
            }
            .bind(this)
        );
        return this;
    }
};

document.addEventListener("DOMContentLoaded", Kirby.AdminOrder.Addresses.init.bind(Kirby.AdminOrder.Addresses), false);
