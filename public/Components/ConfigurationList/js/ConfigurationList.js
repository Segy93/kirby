(function () {
    "use strict"

    const elements = {
        form: ".configuration_list__delete_form",
        configuration_single: ".configuration_list__single_wrapper",
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
        Monitor.Main.DOM.register("ConfigurationList", elements);
    };






    /**
     * Pritisnuto je dugme za brisanje proizvoda iz liste konfiguratora
     * @param {Event} event okinuti dogadjaj
     */
    function removeClicked(event) {
        event.preventDefault();
        const element           = event.currentTarget;
        const elements          = element.elements;
        const configuration_id  = parseInt(elements.configuration_id.value, 10);
        Swal.fire({
            title: 'Da li ste sigurni da želite da uklonite konfiguraciju?',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#b1003f',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ukloni',
            cancelButtonText: 'Odustani',
        }).then((result) => {
            if (result.value) {
                deleteConfiguration(configuration_id);
            }
        });
    }

    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Element|NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    function getElement(element, query_all, modifier, parent) {
        return Monitor.Main.DOM.getElement("ConfigurationList", element, query_all, modifier, parent);
    };








    /**
     * Salje zahtev da se obrise konfiguracija
     * @param {Number} configuration_id identifikator konfiguracije
     */
    function deleteConfiguration(configuration_id) {
        Monitor.Main.Ajax(
            "ConfigurationList",
            "deleteConfiguration",
            {
                configuration_id,
            },
            {
                success:function() {
                    const wrapper = getElement("configuration_single", false, configuration_id.toString());
                    wrapper.remove();
                }
            }
        );
    };



    document.addEventListener('DOMContentLoaded', init);
}());
