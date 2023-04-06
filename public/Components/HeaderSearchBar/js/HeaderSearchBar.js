(function ()  {








    var elements = {
        form:           ".header_search__bar",
        search_query:   ".header_search__bar_input",
    };


    var config = {
        current_term: "",
    }








    /**
     * Inicijalizacija komponente
     * @param   {Object}    event           DOMContentReady dogadjaj
     */
    var init = function(event) {
        registerElements();
        initListeners();
        initAutocomplete();
    };

    var initListeners = function() {
        getElement("form").addEventListener("submit", submittedForm, false);
    };

    var registerElements = function() {
        Kirby.Main.Dom.register("HeaderSearchBar", elements);
    };
    









    var submittedForm = function(event) {
        var form = event.currentTarget;
        var selector_autocomplete = ".autocomplete-suggestion.selected";

        if (document.querySelector(selector_autocomplete) !== null) {
            event.preventDefault();
        }
    };








    /**
    * Dohvatanje elementa, na osnovu lokalnog imena
    * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
    * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
    * @param   {String}    modifier        BEM modifier za selektor
    * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
    */
    var getElement = function(element, query_all, modifier, parent) {
        return Kirby.Main.Dom.getElement("HeaderSearchBar", element, query_all, modifier, parent);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("HeaderSearchBar", element, query_all, modifier);
    };








    var initAutocomplete = function () {
        var element = getElement("search_query");
        var autocomplete = new autoComplete({
            selector: ".header_search__bar_input",
            minChars: 3,
            source: function(term, response) {
                config.current_term = element.value;
                getSearchResults(term, response);
            }
        });
    };









    var getSearchResults = function(query, response) {
        Kirby.Main.Ajax(
            "HeaderSearchBar",
            "getSearchResults",
            {
                query: query,
            }, function(data) {
                if (query === config.current_term) {
                    response(data);
                }
            }
        );
    };

    document.addEventListener("DOMContentLoaded", init);
}());
