(function () {
    "use strict"

    const elements = {
        add_button: ".configurator_setup__add_button",
        name:       ".configurator_setup__name_input",
        id:         ".configurator_setup__id",
        price:      ".configurator_setup__total_price_count",
        error:      ".configurator_setup__error",
        warning:    ".configurator_setup__warning",
        heading:    ".configurator_setup__instruction_headings",
        image:      ".configurator_setup__category_image",
    };

    function init() {
        registerElements();
        initListeners();
    };

    function initListeners() {
        getElement("name").addEventListener('input', updateName, false);
        document.addEventListener("Monitor.Configurator.ProductRemoved", productRemoved, false);
    };

    function registerElements() {
        Monitor.Main.DOM.register("ConfiguratorSetup", elements);
    };

    /**
     * Poziva se nakon dobijanja informacije da je korisnik zatrazio brisanje proizvoda iz konfiguracije
     * @param {CustomEvent} event Dogadjaj koji se okinuo
     */
    function productRemoved(event) {
        const detail = event.detail;
        const product_id = detail.product_id;
        const category_id = parseInt(detail.category_id, 10);
        const products_length = parseInt(detail.products_length, 10);
        const total_price = detail.data;
        const add_button = getElement("add_button", false, category_id.toString());
        const error = getElement('error', false, product_id);
        const warning = getElement('warning', false, category_id);
        const heading = getElement('heading', false, category_id);
        const image = getElement('image', false, category_id);
        if (error !== null) {
            error.remove();
        }
        if (warning !== null) {
            warning.remove();
        }
        getElement("price").textContent = total_price + ' RSD';

        if (products_length === 0) {
            add_button.classList.remove("common_landings__hidden");
        }
        heading.classList.remove('common_landings__hidden');
        image.classList.remove('common_landings__hidden');
    }

    /**
     * Poziva se nakon sto je zahtevana promena imena konfiguracije od strane korisnika
     * prosledjuje zahtev funkciji za generisanje ajax zahteva ka serveru
     */
    function updateName() {
        const name = getElement('name').value;
        const id = getElement('id').value;

        const id_value = id !== '' ? parseInt(id, 10) : null;

        updateSessionName(name, id_value);
    }


    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Element|NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    function getElement(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ConfiguratorSetup", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Element|NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    function getElementSelector(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("ConfiguratorSetup", element, query_all, modifier);
    };

    /**
     * Cuvanje imena u sesiji
     *
     * @param {String} name   Ime konfiguracije
     * @param {Number} id     Id konfiguracije
     */
    function updateSessionName(name, id) {
        Monitor.Main.Ajax(
            "ConfiguratorSetup",
            "updateName",
            {
                name,
                id,
            },
        );
    };









    document.addEventListener('DOMContentLoaded', init, false);
}());
