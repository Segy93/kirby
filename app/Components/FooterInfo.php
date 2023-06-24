<?php

namespace App\Components;

/**
*
*/
class FooterInfo extends BaseComponent {
    private $text = 'Prikazane cene su sa uračunatim PDV-om. eXelence d.o.o se maksimalno trudi da sve opise, slike i cene što je moguće tačnije prikaže. Uključujući sve resurse, a zbog komplikovanosti sistema online prodaje, ne možemo garantovati da su svi podaci na našem sajtu tačni. Za proveru stanja, opisa, cena ili bilo koje drugo pitanje, kontaktirajte nas na 063/22-32-42.';
    protected $css = ['FooterInfo/css/FooterInfo.css'];

    public function renderHTML() {
        $args = [
            'text'  =>  $this->text,
        ];

        return view('FooterInfo/templates/FooterInfo', $args);
    }
}
