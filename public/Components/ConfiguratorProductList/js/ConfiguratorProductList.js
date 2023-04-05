(function () {
    "use strict"

    const elements = {
        remove_button: ".configurator_product_list__delete_item",
        product_single: ".configurator_product_list__single_wrapper",
        specs: '.configurator_product_list__single_wrapper_specs',
        product_single_category: ".configurator_product_list__single_wrapper_category_id",
        form: ".configurator_product_list__delete_item_form",
    };

    function init ()  {
        registerElements();
        initListeners();
    };

    function initListeners () {
        const form = getElement('form', true);
        form.forEach(form => {
            form.addEventListener("click", removeClicked, false);
        });
    };

    function registerElements() {
        Monitor.Main.DOM.register("ConfiguratorProductList", elements);
    };






    /**
     * Pritisnuto je dugme za brisanje proizvoda iz liste konfiguratora
     * @param {Event} event okinuti dogadjaj
     */
    function removeClicked(event) {
        event.preventDefault();
        const element       = event.currentTarget;
        const elements      = element.elements;
        const product_id    = parseInt(elements.product_id.value, 10);
        const category_id   = parseInt(elements.category_id.value, 10);
        const configuration_name    = elements.configuration_name.value;
        Swal.fire({
            title: 'Da li ste sigurni da želite da uklonite proizvod?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#b1003f',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ukloni',
            cancelButtonText: 'Odustani',
        }).then((result) => {
            if (result.value) {
                removeProduct(product_id);
                removeProductFromConfiguratorList(product_id, configuration_name, category_id);
            }
        });
    }

    /**
     * Sklanja proizvod iz liste
     * @param {Number} product_id identifikator proizvoda
     */
    function removeProduct(product_id) {
        const wrapper = getElement("product_single", false, product_id.toString());
        const specs = getElement("specs", false, product_id.toString());
        wrapper.remove();
        const wrappers = getElement("product_single", true, product_id.toString());
        if (specs !== null && wrappers.length === 0) {
            specs.remove();
        }
    }

    /**
     * Salje dogadjaj o tome da je proizvod obrisan
     * @param {Number} product_id identifikator proizvoda
     * @param {Number} category_id identifikator kategorije proizvoda
     * @param {String} data        Nova ukupna cena
     */
    function sendEventProductRemoved(product_id, category_id, data) {
        const category_elements = getElement("product_single_category", true, category_id.toString());
        const products_length   = category_elements.length;
        const detail = {
            product_id,
            category_id,
            products_length,
            data
        };
        const event   = new CustomEvent("Monitor.Configurator.ProductRemoved", {detail});
        document.dispatchEvent(event);
    }



    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Element|NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    function getElement(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ConfiguratorProductList", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Element|NodeList}         Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    function getElementSelector(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("ConfiguratorProductList", element, query_all, modifier);
    };








    /**
     * Salje zahtev da se obrise proizvod iz liste proizvoda
     * @param {Number} product_id         identifikator proizvoda
     * @param {String} configuration_name Naziv konfiguracije
     * @param {Number} category_id        Id kategorije
     */
    function removeProductFromConfiguratorList(product_id, configuration_name, category_id) {
        Monitor.Main.Ajax(
            "ConfiguratorProductList",
            "removeProductFromConfiguratorList",
            {
                product_id,
                configuration_name,
            },
            {
                success:function(data) {
                    sendEventProductRemoved(product_id, category_id, data);
                }
            }
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
