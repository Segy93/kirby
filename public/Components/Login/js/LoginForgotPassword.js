(function () {
    "use strict";

    var elements = { // Selektori za elemente koji ce biti korišćeni u komponenti
        form_forgot:      ".login_form__form--forgottenpword",  // Forma za unos mejla za slanje reset lozinke
    };










    /**
     * Inicijalizacija osluškivača u okviru komponente, kao i funkcija koje reaguju na njih
     * @param   {Object}    event           JavaScript event objekat
     * @return  {Object}                    Monitor.Login objekat, za ulančavanje funkcija
     */
    var initListeners = function() {
        var form_forgot = getElement("form_forgot");
        if (form_forgot !== null) {
            elements["forgot_email"] = form_forgot.elements.email;
            form_forgot.elements.email.addEventListener("blur", blurEmail, false);
        }
    };

    /**
     * Registracija elemenata u upotrebi od strane komponente
     * @return  {Object}                    Monitor.Login objekat, za ulančavanje funkcija
     */
    var registerElements = function() {
        Monitor.Main.DOM.register("LoginForgotPassword", elements);
    };










    /**
     * Provera da li korisnik sa datim email-om vec postoji
     * @param  {Object}     event           JavaScript event objekat
     */
    var blurEmail = function(event) {
        checkEmailTaken(event.target.value);
    };










    /**
     * Dohvatanje elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElement = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElement("Login", element, query_all, modifier);
    };

    /**
     * Dohvatanje selektora za elementa, na osnovu lokalnog imena
     * @param   {String}    element         Lokalno ime elementa (definisano u elements sekciji na vrhu)
     * @param   {Boolean}   query_all       Da li nam treba jedan element ili svi koji odgovaraju upitu
     * @param   {String}    modifier        BEM modifier za selektor
     * @return  {Node/NodeList}             Vraca Node objekat ukoliko je query_all false, niz Node objekata inace
     */
    var getElementSelector = function(element, query_all, modifier) {
        return Monitor.Main.DOM.getElementSelector("Login", element, query_all, modifier);
    };










    var checkEmailTaken = function(email) {
        Monitor.Main.Ajax(
            "Login",
            "checkEmailTaken",
            {
                "email": email,
            },
            function(taken) {
                elements.forgot_email.setCustomValidity(taken ? "" : "Nepostojeća adresa");
            }
        );
    };









    registerElements();
    initListeners();
}());