"use strict";
if (typeof Monitor === "undefined") var Monitor = {};

Kirby.AdminErrorModal = {
    config: {
        error_codes: {
            "role": {
                15001: "Nemate dozvolu za kreiranje uloga",
                15002: "Nemate dozvolu za čitanje uloga",
                15003: "Nemate dozvolu za dohvatanje uloge po id-u",
                15004: "Nemate dozvolu za izmenu uloge",
                15005: "Uloga sa tim id-om nije pronađena",
                15006: "Opis kategorije nije odgovarajućeg formata",
                15007: "Nemate dozvolu za brisanje uloge",
            },
            "address": {
                1001: "Nemate dozvolu za kreiranje adrese radnje",
                1002: "Nemate dozvolu za kreiranje adrese korisnika",
                1003: "Grad sa tim id-om nije pronađen",
                1004: "Adresa nije odgovarajućeg formata",
                1005: "Poštanski broj nije odgovarajućeg formata",
                1006: "Email nije odgovarajućeg formata",
                1007: "Fax nije odgovarajućeg formata",
                1008: "Radni sati nisu odgovarajućeg formata",
                1009: "Ime nije odgovarajućeg formata",
                1010: "Prezime nije odgovarajućeg formata",
                1011: "Kompanija nije odgovarajućeg formata",
                1012: "Broj telefona nije odgovarajućeg formata",
                1013: "Nemate dozvolu za dohvatanje adrese korisnika",
                1014: "Nemate dozvolu za dohvatanje adresa korisnika",
                1015: "Adresa sa tim id-om nije pronađena",
                1016: "Nemate dozvolu za izmenu adrese korisnika",
                1017: "Nemate dozvolu za izmenu adrese radnje",
                1018: "Grad pod tim id-om nije pronađen",
                1019: "Ime nije odgovarajućeg formata",
                1020: "Prezime nije odgovarajućeg formata",
                1021: "Adresa nije odgovarajućeg formata",
                1022: "Poštanski broj nije odgovarajućeg formata",
                1023: "Telefonski broj nije odgovarajućeg formata",
                1024: "Email adresa nije odgovarajućeg formata",
                1025: "Fax nije odgovarajućeg formata",
                1026: "Radno vreme nije odgovarajućeg formata",
                1027: "Nemate dozvolu za brisanje adrese korisnika",
                1028: "Nemate dozvolu za brisanje adrese radnje",
                1029: "Naziv kompanije nije odgovarajućeg formata",
            },
            "admin": {
                1001: "Nemate dozvolu za kreiranje administratora",
                1002: "Id uloge nije odgovarajućeg formata",
                1003: "Korisničko ime nije odgovarajućeg formata",
                1004: "Email nije odgovarajućeg formata",
                1005: "Lozinka nije odgovarajućeg formata",
                1006: "Administrator sa tim korisničkim imenom ili email adresom nije pronađen",
                1007: "Pogrešna lozinka",
                1008: "Nemate dozvolu za dohvatanje trenutno prijavljenog administratora",
                1009: "Nemate dozvolu za pretragu administratora",
                1010: "Nemate dozvolu za dohvatanje administratora po id-u",
                1011: "Nemate dozvolu za kreiranje administratora",
                1012: "Administrator pod tim id-om nije pronađen",
                1013: "Id uloge nije odgovarajućeg formata",
                1014: "Uloga pod tim id-om nije pronađena",
                1015: "Korsničko ime nije odgovarajućeg formata",
                1016: "Email nije odgovarajućeg formata",
                1017: "Lozinka nije odgovarajućeg formata",
                1018: "Nemate dozvolu za brisanje administrator",
                1019: "Administrator pod tim id-om nije pronađen",
            },
            "articleCategory": {
                3001: "Nemate dozvolu za kreiranje kategorija članaka",
                3002: "Ime kategorije nije odgovarajućeg formata",
                3003: "Nemate dozvolu za izmenu kategorije članaka",
                3004: "Ime kategorije nije odgovarajućeg formata",
                3005: "Nemate dozvolu za izmenu redosleda kategorije članaka",
                3006: "Nemate dozvolu za brisanje kategorije članaka",
                3007: "Ime kategorije već postoji",
            },
            "article": {
                4001: "Nemate dozvolu za kreiranje članka",
                4002: "Naslov članka nije odgovarajućeg formata",
                4003: "Tekst članka nije odgovarajućeg formata",
                4004: "Isečak nije odgovarajućeg formata",
                4005: "Datum nije odgovarajućeg formata",
                4006: "Id članka nije odgovarajućeg formata",
                4007: "Nemate dozvolu za dohvatanje članka",
                4008: "Člank nije pronađen",
                4009: "Nemate dozvolu za izmenu članka",
                4010: "Kategorija nije pronađena",
                4011: "Nemate dozvolu za izmenu autora",
                4012: "Autor nije pronađen",
                4013: "Naslov nije odgovarajućeg formata",
                4014: "Tekst nije odgovarajućeg formata",
                4015: "Isečak nije odgovarajućeg formata",
                4016: "Datum nije odgovarajućeg formata",
                4017: "Status nije odgovarajućeg formata",
                4018: "Nemate dozvolu za postavljanje taga",
                4019: "Nemate dozvolu za brisanje članka",
            },
            "banner": {
                5001: "Nemate dozvolu za kreiranje banera",
                5002: "Id pozicije nije odgovarajućeg formata",
                5003: "Naslov nije odgovarajućeg formata",
                5004: "Ime slike nije odgovarajućeg formata",
                5005: "Link nije odgovarajućeg formata",
                5006: "Linkovi gde te baner da se pojavljuje nisu odgovarajućeg formata",
                5007: "Nemate dozvolu za kreiranje pozicija banera",
                5008: "Id tipa stranice nije odgovarajućeg formata",
                5009: "Naziv pozicije nije odgovarajućeg formata",
                5010: "Širina slike nije odgovarajućeg formata",
                5011: "Visina slike nije odgovarajućeg formata",
                5012: "Nemate dozvolu za kreiranje tipova strana",
                5013: "Tip strane nije odgovarajućeg formata",
                5014: "Nemate dozvolu za izmenu banera",
                5015: "Baner sa tim id-om nije pronađen",
                5016: "Id pozicije nije odgovarajućeg fomrata",
                5017: "Pozicija sa tim id-om ne postoji",
                5018: "Naslov nije odgovarajućeg formata",
                5019: "Ime slike nije odgovarajućeg formata",
                5020: "Link nije odgovarajućeg formata",
                5021: "Linkovi na kojima treba da se pojavljuje baner nije odgovarajućeg formata",
                5022: "Baner sa tim id-om nije pronađen",
                5023: "Nemate dozvolu za izmenu pozicije za banere",
                5024: "Pozicija sa tim id-om nije pronađena",
                5025: "Id tipa stane nije odgovarajućeg formata",
                5026: "Tip strane sa tim id-om nije pronađen",
                5027: "Naziv pozicije nije odgovarajućeg formata",
                5028: "Širina slike nije odgovarajućeg formata",
                5029: "Visina slike nije odgovarajućeg formata",
                5030: "Nemate dozvolu za izmenu tipe strane",
                5031: "Tip stranice sa tim id-om nije pronađen",
                5032: "Tip stranice nije odgovarajućeg formata",
                5033: "Nemate dozvolu za brisanje banera",
                5034: "Banner sa tim id-om nije pronađen",
                5035: "Nemate dozvolu za brisanje pozicija",
                5036: "Pozicija sa tim id-om nije pronađen",
                5037: "Nemate dozvolu za brisanje tipova strana",
                5038: "PageType sa tim id-om nije pronađen",
            },
            "base": {
            },
            "category": {
            },
            "comment": {
                8001: "Tekst komentara nije odgovarajućeg formata",
                8002: "Ne odgovoriti na odgovor",
                8003: "Nemate dozvolu za izmenu komentara",
                8004: "Tekst komentara nije odgovarajućeg formata",
                8005: "Nemate dozvolu za odobravanje komentara",
                8006: "Nemate dozvolu za izmenu komentara",
            },
            "email": {
            },
            "folder": {
            },
            "image": {
                11001: "Greška pri upisu slike",
            },
            "import": {	
                12001: "Nemate dozvolu za unos proizvoda",
                12002:"Nemate dozvolu za unos proizvoda",
                12003:" Proizvod nije pronađen",
                12004:" --Poruka greške se kreira iz ProductService--",
                12005:" --Poruka se generiše u drugoj metodi--",
                12006:" Nemate dozvolu za dohvatanje kategorije",
                12007:" Nemate dozvolu za dohvatanje kategorije",
                12008:" Kategorija nije pronađena",
                12009:" Nemate dozvolu za izmenu proizvoda",
                12010:" Nemate dozvolu za izmenu proizvoda",
                12011:" Proizvod nije pronađen",
                12012:" Izmene za proizvod nisu pronađene",
                12013:" Prouzvod pod id-om je obrisan",
                12014:" --Poruka greške se generiše u Productservice--",
                12015:" --Poruka se generiše u drugoj metodi--",
            },
            "permission": {
                13001: "Nemate dozvolu za dodeljivanje dozvole",
                13002: "Nemate dozvolu za dodavanje dozvola drugim ulogama",
                13003: "Nemate dozvolu za pretragu dozvola",
                13004: "Nemate dozvolu za dohvatanje dozvole po id-u",
            },
            "product": {
                14001: "Proizvod sa tim id-om nije pronađen",
            },
            "role": {
                15001: "Nemate dozvolu za kreiranje uloga",
                15002: "Nemate dozvolu za čitanje uloga",
                15003: "Nemate dozvolu za dohvatanje uloge po id-u",
                15004: "Nemate dozvolu za izmenu uloge",
                15005: "Uloga sa tim id-om nije pronađena",
                15006: "Opis kategorije nije odgovarajućeg formata",
                15007: "Nemate dozvolu za brisanje uloge",
            },
            "search": {
            },
            "seo": {
                17001: "Nemate dozvolu da menjate SEO",
                17002: "Ključne reči nisu odgovarajućeg formata",
                17003: "Opis nije odgovarajućeg formata",
                17004: "Naslov nije odgovarajućeg formata",
                17005: "Url nije odgovarajućeg formata",
                17006: "Url je zauzet",
                17007: "emate dozvolu za brisanje SEO-a",
                17008: "SEO nije pronađen",
            },
            "shipping": {
            },
            "shop": 
            {
                19001: "Nemate dozvolu za dohvatanje korpe korisnika",
                19002: "Korisnik sa tim id-om nije pronađen",
                19003: "Način plaćanja nije pronađen",
                19004: "Adresa dostave sa tim id-om nije pronađena",
                19005: "Adresa plaćanja sa tim id-om nije pronađena",
                19006: "Token nije odgovarajućeg formata",
                19007: "Datum dostave nije odgovarajućeg formata",
                19008: "Napomena nije odgovarajućeg formata",
                19009: "Korpa je prazna",
                19010: "Korisnik sa tim id-om nije pronađen",
                19011: "Proizvod pod tim id-om nije pronađen",
                19012: "Količina nije odgovarajućeg formata",
                19013: "Porudžbenica sa tim id-om nije pronađena",
                19014: "Nemate dozvolu za pretragu narudžbina po id-u",
                19015: "Proizvod iz korpe sa tim id-om nije pronađen",
                19016: "Nemate dozvolu za dohvatanje proizvoda iz korpe po id-u",
                19017: "Nemate dozvolu za pretragu narudžbina",
                19018: "Nemate dozvolu za pretragu narudžbina po id-u korisnika",
                19019: "Nemate dozvolu za dohvatanje korpe",
                19020: "Nemate dozvolu za dohvatanje ukupne cene korpe",
                19021: "Nemate dozvolu za izmenu narudžbine",
                19022: "Način plaćanja pod tim id-om nije pronađen",
                19023: "Adresa dostave sa tim id-om nije pronađena",
                19024: "Adresa naplate sa tim id-om nije pronađena",
                19025: "Datum dostave nije odgovarajućeg formata",
                19026: "Napomena nije odgovarajućeg formata",
                19027: "Poštarina nije odgovarajućeg formata",
                19028: "Nemate dozvolu za izmenu statusa porudđbenice",
                19029: "Korpa sa tim id-om nije pronađena",
                19030: "Nemate dozvolu za izmenu korpe",
                19031: "Porudžbenica sa tim id-om nije pronađena",
                19032: "Nemate dozvolu za brisanje porudžbenice",
                19033: "Proizvod iz korpe sa tim id-om nije pronađena",
                19034: "Nemate dozvolu za brisanje proizvoda iz korpe",
                19035: "Nemate dozvolu da kreirate promenu statusa",
                19036: "Admin nije pronađen",
                19037: "Status nije odgovarajućeg formata",
                19038: "Komentar interni nije odgovarajućeg formata",
                19039: "Komentar korisniku nije odgovarajućeg formata",
                19040: "Nemate dozvolu za promenu stanja proizvoda u porudžbenici",
                19041: "Stavka iz narudžbenice pod tim id-om nije pronađena",
                19042: "Količina nije odgovataućeg formata",
                19043: "Nemate dozvolu za brisanje stavke iz porudžbenice",
                19044: "Nemate dozvolu da vidite statuse porudžbenice",
                19045: "Nemate dozvolu za poništavanje porudžbenice",
            },

            "staticPage": {
                20001: "Nemate dozvolu za kreiranje kategorija statičkih strana",
                20002: "Naziv kategorije nije odgovarajućeg formata",
                20003: "Nemate dozvolu za kreiranje statičke strane",
                20004: "Naslov strane nije odgovarajućeg formata",
                20005: "Sadržaj nije odgovarajućeg fomrata",
                20006: "Nemate dozvolu za izmene kategorije statičkih strana",
                20007: "Ime kategorije nije odgovarajućeg formata",
                20008: "Nemate dozvolu za izmenu statičkih strana",
                20009: "Kategorija sa tim id-om nije pronađena",
                20010: "Naslov stranice nije odgovarajućeg formata",
                20011: "Tekst nije odgovarajućeg formata",
                20012: "Nemate dozvolu za izmenu redosleda statičkih stranica",
                20013: "Nemate dozvolu za brisanje kategorija statičkih stranica",
                20014: "Nemate dozvolu za brisanje statičkih stranica",
            },
            "tag": {
                21001: "Nemate dozvolu za kreiranje taga",
                21002: "Ime taga nije odgovarajućeg formata",
                21003: "Nemate dozvolu za izmenu taga",
                21004: "Ime taga nije odgovarajućeg formata",
                21005: "Nemate dozvolu za brisanje taga",
            },
            "user": {	
                22001: "Korisničko ime nije odgovarajućeg formata",
                22002: "Email nije odgovarajućeg formata",
                22003: "Ime nije odgovarajućeg formata",
                22004: "Prezime nije odgovarajućeg formata",
                22005: "Broj telefona nije odgovarajućeg formata",
                22006: "Profilna slika nije odgovarajućeg formata",
                22007: "Email nije odgovarajućeg formata",
                22008: "Nemate dozvolu za dohvatanje korisnika po id-u",
                22009: "Nemate dozvolu za dohvatanje korisnika",
                22010: "Nemate dozvolu za dohvatanje broj korisnika",
                22011: "Korisnik sa tim korisničkim imenom ili email-om nije pronađe",
                22012: "Pogrešna lozinka",
                22013: "Nemate dozvolu za izmenu korisnika",
                22014: "Korisnik sa tim id-om nije pronađen",
                22015: "Korisničko ime nije odgovarajućeg formata",
                22016: "Email nije odgovarajućeg formata",
                22017: "Lozinka nije odgovarajućeg formata",
                22018: "Ime nije odgovarajućeg formata",
                22019: "Prezime nije odgovarajućeg formata",
                22020: "Kućna adresa nije odgovarajućeg formata",
                22021: "Adresa dostave nije odgovarajućeg formata",
                22022: "Broj telefona nije odgovarajućeg formata",
                22023: "Profilna slika nije odgovarajućeg formata",
                22024: "Korisnik sa tim email-om nije pronađen",
                22025: "Korisnik sa tim tokenom nije pronađen",
                22026: "Token je istekao, ponovo pošaljite zahtev za reset lozinke",
                22027: "Lozinke se ne podudaraju",
                22028: "Korisnik sa tim tokenom nije pronađen",
                22029: "Token je istekao, ponovo pošalji te zahtev za aktivaciju email-a",
                22030: "Korisnik sa tim email-om nije pronađen",
                22031: "Email neuspešno poslat",
                22032: "Nemate dozvolu da banujete korisnika",
                22033: "Korisnik sa tim id-om nepostoji",
                22034: "Nemate dozvolu za brisanje korisnika",
                22035: "Korisnik pod tim id-om nije pronađen",
                22036: "Ne može se obrisati korisnik koji ima narudžbinu",
            },
            "validation": {
                23001: "Lozinka je prekratka",
                23002: "Lozinka ne sadrži nijedan broj",
                23003: "Lozinka ne sadrži nijedno malo slovo",
                23004: "Lozinka ne sadrži nijedno veliko slovo",
                23005: "Lozinka je predugačka",
            },
            "wishlist": {
                24001: "Proizvod sa tim id-om nije pronađen",
                24002: "Korisnik sa tim id-om nije pronađen",
                24003: "Element u listi sa tim id-om nije pronađen",
                24004: "Element u listi nije pronađen",
            },
        },

        error_types: {
            1: "address",
            2: "admin",
            3: "articleCategorie",
            4: "article",
            5: "banner",
            6: "base",
            7: "category",
            8: "comment",
            9: "email",
            10: "folder",
            11: "image",
            12: "import",
            13: "permission",
            14: "product",
            15: "role",
            16: "search",
            17: "seo",
            18: "shipping",
            19: "shop",
            20: "staticPage",
            21: "tag",
            22: "user",
            23: "validation",
            24: "wishlist",
        }
    },

    elements: {
        "error_message":         "#admin_error_modal__error_message",
        "wrapper":               "#admin_error_modal",
    },










    init: function(event) {
        this
            .registerElements()
            .initListeners()
        ;
    },

    initListeners: function() {
        document.addEventListener("Kirby.Error", this.errorOccured.bind(this), false);
        return this;
    },

    registerElements: function() {
        Kirby.Main.Dom.register("AdminErrorModal", this.elements);
        return this;
    },










    errorOccured: function(event) { 
        var error_message = 'Nepoznata greška';
        var error_code = event.error_code;
        var error_type  = this.config.error_types[(error_code-(error_code%1000))/1000];

        if (this.config.error_codes[error_type] !== undefined) {
            if (this.config.error_codes[error_type][error_code] !== undefined) {
                error_message = this.config.error_codes[error_type][error_code];
            } else {
                error_message = "Nepoznata greška";
            }
        } 
        this.showComponent(error_message);
    },










    showComponent: function(message) {
        this.getElement("error_message").textContent = message;
        $(this.getElementSelector("wrapper")).modal("show");
        return this;
    },

    getElement: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElement("AdminErrorModal", element, query_all, modifier);
    },

    getElementSelector: function(element, query_all, modifier) {
        return Kirby.Main.Dom.getElementSelector("AdminErrorModal", element, query_all, modifier);
    },
};

document.addEventListener('DOMContentLoaded', Kirby.AdminErrorModal.init.bind(Kirby.AdminErrorModal), false);
