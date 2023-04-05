(function () {
    "use strict";

    var config = {
    };

    var elements = {
        address_view:           ".user_profile__address_view",
        address_edit:           ".user_profile__address_edit",
        address_cancel:         ".user_profile__address_create_button--cancel",
        address_create:         ".user_profile__address_create",
        address_add:            ".user_profile__add_address",
        address_create_form:    ".user_profile__address_create_form",
        address_create_address: "#user_profile__address_create_address",
        address_create_phone:   "#user_profile__address_create_phone",
        address_edit_form:      ".user_profile__address_edit_form",
        address_edit_address:   ".user_profile__address_edit_address",
        address_edit_phone:     ".user_profile__address_edit_phone",
        address_edit_select:    ".user_profile__address_edit_city",
        address_edit_cancel:    ".user_profile__address_button--cancel",
        address_delete_form:    ".user_profile__address_delete_form",
        address_input:          ".user_profile__address_create_address",
        address_delete:         ".user_profile__address_button--delete",
        addresses_body:         "#user_profile__addresses__body",
        addresses_list:         "#user_profile__addresses__list",
        addresses:              "#user_profile__addresses",
        addresses_error:        ".user_profile__addresses_error",
    };

    var templates = {
        main: function() {},
    };










    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initTemplates();
        initListeners();
    };

    /**
     * inicijalizuje osluskivace
     */
    var initListeners = function() {

        getElement("addresses_body").addEventListener("submit", submitAnything, false);
        getElement("addresses_body").addEventListener("change", isAddressDuplicate, false);
        getElement("addresses_body").addEventListener("change", isPhoneDuplicate, false);
        getElement("addresses_body").addEventListener("click", clickWithin, false);
        getElement("address_add").addEventListener("click", addAddressClicked, false);

        var address_create_form = getElement("address_create_form");

        if (address_create_form !== null) {

            address_create_form.elements.address.addEventListener("input", blurAddress);
            address_create_form.elements.phone.addEventListener("input", blurPhone);
        }
    };

    var initTemplates = function() {
        templates.main = _.template(document.getElementById("user_profile__addresses_tmpl").innerHTML);
    };

    /**
     * Registruje elemente koji se koriste u komponenti
     */
    var registerElements = function() {
        Monitor.Main.DOM.register("UserProfileAddresses", elements);
    };

    var blurAddress = function(event) {
        var selector_edit_form = getElementSelector("address_edit_form");
        var address_edit_form = event.target.closest(selector_edit_form);
        var address = event.target.value;
        if (address_edit_form !== null) {
            var address_id = address_edit_form.dataset.addressId;
        } else {
            var address_id = 0;
        }

        checkAddress(address_id, address);
    }


    var blurPhone = function(event) {
        var selector_edit_form = getElementSelector("address_edit_form");
        var address_edit_form = event.target.closest(selector_edit_form);
        var phone = event.target.value;
        if (address_edit_form !== null) {
            var address_id = address_edit_form.dataset.addressId;
        } else {
            var address_id = 0;
        }
        checkPhone(address_id, phone);
    }


    var clickWithin = function(event) {
        var selector_delete_button          = getElementSelector("address_delete");
        var selector_create_cancel_button   = getElementSelector("address_cancel");
        var selector_edit_cancel_button     = getElementSelector("address_edit_cancel");
        var selector_edit_button            = getElementSelector("address_view");
        var selector_add_button             = getElementSelector("address_add");
        if (event.target.closest(selector_delete_button) !== null) {
            var address_id = parseInt(event.target.closest(selector_delete_button).dataset.addressId, 10);
            clickDelete(address_id);
        } else if (event.target.closest(selector_create_cancel_button) !== null) {
            cancelCreateClicked();
        } else if (event.target.closest(selector_edit_cancel_button) !== null) {
            cancelEditClicked(event, selector_edit_cancel_button);
        } else if (event.target.closest(selector_edit_button) !== null) {
            editAddressClicked(event, selector_edit_button);
        } else if (event.target.closest(selector_add_button) !== null) {
            addAddressClicked();
        }
    }

    var submitAnything = function(event) {
        var selector_form_create = getElementSelector("address_create_form");
        var selector_form_edit   = getElementSelector("address_edit_form");
        var form = event.target.closest(selector_form_create);
        var form_edit = event.target.closest(selector_form_edit);

        if (form !== null) {
            event.preventDefault();
            createFormSubmitted(form);
        } else if (form_edit !== null) {
            event.preventDefault();
            editFormSubmitted(form_edit);
        }
    };

    var isAddressDuplicate = function(event) {
        var selector_edit_form = getElementSelector("address_edit_form");
        var address_edit_form  = event.target.closest(selector_edit_form);
        if (address_edit_form !== null) {
            address_edit_form.elements.address.addEventListener("input", blurAddress);
        }
    }

    var isPhoneDuplicate = function(event) {
        var selector_edit_form = getElementSelector("address_edit_form");
        var address_edit_form  = event.target.closest(selector_edit_form);
        if (address_edit_form !== null) {
            address_edit_form.elements.phone.addEventListener("input", blurPhone);
        }
    }

    var clickDelete = function(address_id) {
        var element = getElement("address_view", undefined, address_id);
        var confirm = window.confirm("Da li ste sigurni da želite da obrišete adresu?");

        if (confirm === true) {
            deleteAddress(address_id);
            element.remove();
        }
    };



    var editFormSubmitted = function(form) {
        var elements     = form.elements;
        var address_id   = form.dataset.addressId;

        var address     = elements.address.value;
        var name        = elements.contact_name.value;
        var surname     = elements.contact_surname.value;
        var company     = elements.company.value;
        var phone       = elements.phone.value;
        var city        = elements.city.value;
        var postal_code = elements.postal_code.value;
        var pib         = elements.pib.value;

        editAddress(address_id, address, name, surname, company, phone, city, postal_code, pib);
    };

    var createFormSubmitted = function(form) {
        var elements = form.elements;

        var address     = elements.address.value;
        var name        = elements.contact_name.value;
        var surname     = elements.contact_surname.value;
        var company     = elements.company.value;
        var phone       = elements.phone.value;
        var city        = elements.city.value;
        var postal_code = elements.postal_code.value;
        var pib         = elements.pib.value;

        getElement("address_create").classList.add("user_profile__hidden");

        createAddress(address, name, surname, company, phone, city, postal_code, pib);
    };

    var cancelCreateClicked = function() {
        getElement("address_create_form").reset();
        getElement("address_create").classList.add("user_profile__hidden");
        getElement("address_add").classList.remove("common_landings__display_none");
    };

    var cancelEditClicked = function(event, selector_cancel_edit_button) {
        var element = event.target.closest(selector_cancel_edit_button);
        var address_id      = element.dataset.addressId;
        var element_view    = getElement("address_view", false, address_id);
        var element_edit    = getElement("address_edit", false, address_id);
        var element_form    = getElement("address_edit_form", false, address_id);
        element_form.reset();
        element_view.classList.remove("user_profile__hidden");
        element_edit.classList.add("user_profile__hidden");
    };

    var editAddressClicked = function(event, selector_edit_button) {
        var element         = event.target.closest(selector_edit_button);
        var address_id      = element.dataset.addressId;
        var element_edit    = getElement("address_edit", false, address_id);
        var element_city    = getElement("address_edit_select", false, address_id);
        element_city.value  = element_city.dataset.city;

        element.classList.add("user_profile__hidden");
        element_edit.classList.remove("user_profile__hidden");
    }

    var addAddressClicked = function() {
        getElement("address_create").classList.remove("user_profile__hidden");
        getElement("address_add").classList.add("common_landings__display_none");
    };









    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement(
            "UserProfileAddresses", element, query_all, modifier, parent
        );
    };

    /**
    * Dohvatanje selektora za elementa, na osnovu lokalnog imena
    * @param   {String}    element   Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier  BEM modifier za selektor
    * @return  {Node/NodeList}       Vraca Node objekat ukoliko je query_all false, niz Node obj
    */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector(
            "UserProfileAddresses", element, query_all, modifier
        );
    };

    var render = function(data, product_id) {
        var container = getElement("addresses_list");
        var html = templates.main({
            addresses: data,
        });

        container.innerHTML = html;

        getElement("address_add").focus();
    };










    var fetchData = function() {
        Monitor.Main.Ajax(
            "UserProfile",
            "getAddresses",
            {},
            render
        );
    };

    var createAddress = function(address, name, surname, company, phone, city, postal_code, pib) {
        Monitor.Main.Ajax(
            "UserProfile",
            "createAddress",
            {
                address:        address,
                name:           name,
                surname:        surname,
                company:        company,
                phone:          phone,
                city:           city,
                postal_code:    postal_code,
                pib:            pib,
            },
            {
                success: function() {
                    getElement("addresses_error").textContent = "";
                    getElement("address_create_form").reset();
                    fetchData();
                },
                failure: function(response) {
                    getElement("addresses_error").textContent = response.message;
                },
            }
        );
    };

    var editAddress = function(address_id, address, name, surname, company, phone, city, postal_code, pib) {
        Monitor.Main.Ajax(
            "UserProfile",
            "editAddress",
            {
                address_id:     address_id,
                address:        address,
                name:           name,
                surname:        surname,
                company:        company,
                phone:          phone,
                city:           city,
                postal_code:    postal_code,
                pib:            pib,
            },
            {
                success: function() {
                    getElement("addresses_error").textContent = "";
                    fetchData();
                },
                failure: function(response) {
                    getElement("addresses_error").textContent = response.message;
                },
            }
        );
    };


    var deleteAddress = function (id) {
        Monitor.Main.Ajax(
            "UserProfile",
            "deleteAddress",
            {
                id: id,
            },
            fetchData
        );
    }

    var checkAddress = function(address_id, address) {
        Monitor.Main.Ajax(
            "UserProfile",
            "checkAddress",
            {
                address_id: address_id,
                address: address,
            },
            function(valid) {
                var validity = valid ? "Ovu adresu ste već uneli!" : "";
                var address_create = getElement("address_create_address");
                var address_edit = getElement("address_edit_address", false, address_id);
                if (address_edit !== null) {
                    if (address_edit.value === address) {
                        address_edit.setCustomValidity(validity);
                    }
                } else {
                    if (address_create.value === address) {
                        address_create.setCustomValidity(validity);
                    }
                }
            }
        )
    }

    var checkPhone = function(address_id, phone) {
        Monitor.Main.Ajax(
            "UserProfile",
            "checkPhone",
            {
                address_id: address_id,
                phone: phone,
            },
            function(valid) {
                var validity = valid ? "Ovaj telefon ste već uneli!" : "";
                var phone_create = getElement("address_create_phone");
                var phone_edit = getElement("address_edit_phone", false, address_id);
                if (phone_edit !== null) {
                    if (phone_edit.value === phone) {
                        phone_edit.setCustomValidity(validity);
                    }
                } else {
                    if (phone_create.value === phone) {
                        phone_create.setCustomValidity(validity);
                    }
                }
            }
        )
    }







    document.addEventListener("DOMContentLoaded", init);
}());
