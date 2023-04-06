(function () {
    "use strict";

    var elements = {
        add_address:        ".checkout_page__add_address",
        address_created:    ".checkout_page__address_created",
        add_address_button: ".checkout_page___add_address__button",
        add_address_form:   "#checkout_page__add_address_form",
        button_cancel:      ".checkout_page__button--cancel",
        form:               "#checkout_page__form",
        diffrent_address:   "#checkout_page__nocharging_info",
        country:            ".checkout_page__country",
        cities:             ".checkout_page__city",
        shipping:           ".checkout_page__shipping",
        shops:              ".checkout_page__store_select",
        address_all:        ".checkout_page__address_list",
        temporary_email:    "#checkout_page__temporary_email",
        errors:             ".checkout_page__error_list",
        payment_method:     ".payment_method",
        price_discount:     ".checkout_page__price_discount",
        price_retail:       ".checkout_page__price_retail",
        total_discount:     ".checkout_page__price_all_discount",
        total_retail:       ".checkout_page__price_all_retail",
        radio_shop:         ".checkout_page__delivery_shop",
        radio_home:         ".checkout_page__delivery_home",
        shipping_dropdown:  ".checkout_page__shipping_info",
        billing_dropdown:   ".checkout_page__billing_info",
    };

    var init = function(event) {
        registerElements();
        initListeners();
        setVisibility();
    };

    var initListeners = function () {
        getElement("add_address_button").addEventListener("click", addressClicked, false);
        getElement("button_cancel").addEventListener("click", addressCanceled, false);
        getElement("add_address").addEventListener("submit", addAddressSubmited, false);
        getElement("radio_shop").addEventListener("change", deselectAll, false);
        getElement("radio_home").addEventListener("change", deselectAll, false);
        var radios = getElement("payment_method", true);

        // for (var i = 0, l = radios.length; i < l; i++) {
        //     var radio = radios[i];
        //     radio.addEventListener("change", paymentChanged, false);
        // }

        //Provera da li je korisnik prijavljen, ako nije postoji polje za unos emaila
        if (getElement("temporary_email")!== null) {
            getElement("temporary_email").addEventListener("blur", blurEmail, false);
        }
    };

    var registerElements = function () {
        Kirby.Main.Dom.register("CheckoutPage", elements);
    };

    var setVisibility = function() {
        var button = getElement("add_address_button");
        var form   = getElement("add_address");
        button.classList.remove("checkout_page__visibility_hidden");
        form.classList.add("checkout_page__visibility_hidden");
    };








    var addressClicked = function() {
        var form   = getElement("add_address");
        form.classList.remove("checkout_page__visibility_hidden");
        var added_address       = getElement("address_created");
        added_address.innerHTML = '';
    };

    var addressCanceled = function() {
        var form   = getElement("add_address");
        form.classList.add("checkout_page__visibility_hidden");
    }

    var addAddressHide = function () {
        var form   = getElement("add_address");
        form.classList.add("checkout_page__visibility_hidden");
        var added_address       = getElement("address_created");
        added_address.innerHTML = 'Adresa uspešno kreirana';
    }

    var addAddressSubmited = function(event) {
        var form        = getElement("add_address_form");
        var tmp_email   = getElement("temporary_email");
        var errors      = [];
        var focused     = false;

        var elms = form.elements;
        event.preventDefault();
        if (tmp_email !== null) {
            if (tmp_email.value === "") {
                errors.push("Unesite email adresu");
            }
        }

        var is_string = /[A-z]+/;
        var is_number = /[0-9]+/;
        var is_phone  = /^([+]?[\d]+[\/]?[-]{0,3}\s*){8,63}$/;

        if (elms.name.value !== "") {
            if(!elms.name.value.match(is_string)) {
                errors.push("Ime nije odgovarajuceg formata");
                if (focused === false) {
                    elms.name.focus();
                }
            }
        } else {
            errors.push("Morate uneti ime");
            if (focused === false) {
                elms.name.focus();
                focused = true;
            }
        }

        if (elms.surname.value !== "") {
            if(!elms.surname.value.match(is_string)) {
                errors.push("Prezime nije odgovarajuceg formata");
                if (focused === false) {
                    elms.surname.focus();
                    focused = true;
                }
            }
        } else {
            errors.push("Morate uneti prezime");
            if (focused === false) {
                elms.surname.focus();
                focused = true;
            }
        }

        if (elms.address.value === "") {
            errors.push("Morate uneti adresu");
            if (focused === false) {
                elms.address.focus();
                focused = true;
            }
        }
        if (elms.post_code.value !== "") {
            if(!elms.post_code.value.match(is_number)) {
                errors.push("Poštanski broj nije odgovarajuceg formata");
                if (focused === false) {
                    elms.post_code.focus();
                    focused = true;
                }
            }
        } else {
            errors.push("Morate uneti poštanski broj");
            if (focused === false) {
                elms.post_code.focus();
                focused = true;
            }
        }

        if (elms.phone.value !== "") {
            if(!elms.phone.value.match(is_phone)) {
                errors.push("Telefonski broj nije odgovarajuceg formata");
                if (focused === false) {
                    elms.phone.focus();
                    focused = true;
                }
            }
        } else {
            errors.push("Morate uneti telefonski broj");
            if (focused === false) {
                elms.phone.focus();
                focused = true;
            }
        }

        if (elms.city.value === "") {
            errors.push("Morate izabrati grad");
            if (focused === false) {
                elms.city.focus();
                focused = true;
            }
        }



        if (errors.length === 0) {
            var name        = elms.name.value;
            var surname     = elms.surname.value;
            var company     = elms.company.value;
            var address     = elms.address.value;
            var post_code   = elms.post_code.value;
            var phone       = elms.phone.value;
            var city        = elms.city.value;
            var pib         = elms.pib.value;
            createUserAddress(city, name, surname, address, post_code, phone, company, pib);
        } else {
            var error_element = getElement("errors");
            var error_list   = "";
            for (var i = 0, l = errors.length; i < l; i++) {
                error_list += errors[i];
                error_list += '</br>';
            }

            error_element.innerHTML = error_list;
        }
    };

    var paymentChanged = function() {
        var payments = getElement("payment_method", true);
        var payment  = 0;
        for (var i = 0, l = payments.length; i < l; i++) {
            if (payments[i].checked) {
                payment = payments[i];
            }
        }
        var price_discounts = getElement("price_discount", true);
        var price_retails = getElement("price_retail", true);
        var total_discount = getElement("total_discount");
        var total_retail = getElement("total_retail");

        if (payment.dataset.method === "Keš" || payment.dataset.method === "Virmanski") {
            price_discounts.forEach(function(price_discount) {
                price_discount.classList.remove("common_landings__display_none");
            });

            price_retails.forEach(function(price_retail) {
                price_retail.classList.add("common_landings__display_none");
            });

            total_discount.classList.remove("common_landings__display_none");

            total_retail.classList.add("common_landings__display_none");

        } else {
            price_discounts.forEach(function(price_discount) {
                price_discount.classList.add("common_landings__display_none");
            });

            price_retails.forEach(function(price_retail) {
                price_retail.classList.remove("common_landings__display_none");
            });

            total_discount.classList.add("common_landings__display_none");

            total_retail.classList.remove("common_landings__display_none");
        }

    }

    var deselectAll = function() {
        getElement("shipping_dropdown").value = "";
        getElement("billing_dropdown").value = "";
    };

    var blurEmail = function() {
        var input = getElement("temporary_email");
        var text = input.value;

        // Ako server sporo odgovara,
        // da korisnik ne dobija gresku dok cekamo odgovor na proveru
        input.setCustomValidity("");
        checkEmailTaken(text);
    };



    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element    Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all  Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier   BEM modifier za selektor
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("CheckoutPage", element, query_all, modifier, parent);
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("CheckoutPage", element, query_all, modifier);
    };

    var render = function (data) {
        var all_addresses = getElement("address_all", true);

        var option      = "<option class = 'checkout_page__info_addresses' selected value = '" + data.id + "'>"+ data.address +" | "+ data.contact_surname +" "+ data.contact_name +" </option>";

        all_addresses.forEach(function(address) {
            address.innerHTML += option;
        });
        getElement("radio_home").checked = true;
        addAddressHide();
    };

    var errorRender = function (data) {
        var error_element = getElement("errors");
        error_element.innerHTML = data.message;
    };


    var createUserAddress = function(city, name, surname, address, post_code, phone, company, pib) {
        Kirby.Main.Ajax(
            "CheckoutPage",
            "createUserAddress",
            {
                city:       city,
                name:       name,
                surname:    surname,
                address:    address,
                post_code:  post_code,
                phone:      phone,
                company:    company,
                pib:        pib,
            },
            [render, errorRender]
        );
    };

    var checkEmailTaken = function(email) {
        Kirby.Main.Ajax(
            "Login",
            "checkEmailTaken",
            {
                email: email,
            },
            function(taken) {
                var input = getElement("temporary_email");
                if (input.value === email) {
                    input.setCustomValidity(taken ? "Ova email adresa je zauzeta. Molimo pokušajte ponovo." : "");
                }
            }
        );
    };








    document.addEventListener("DOMContentLoaded", init);
}());
