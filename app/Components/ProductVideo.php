<?php

namespace App\Components;

use Illuminate\Support\Facades\Log;

/**
* Koristi se za prikaz videa o proizvodu sa YouTube-a
*/
class ProductVideo extends BaseComponent {

    protected $css          = ['ProductVideo/css/ProductVideo.css'];

    private $video_provider = '';

    private $video_id       = '';

    /**
     * Zapis greske prilikom obrade
     *
     * @param string $field_value Smesta se vrednost youtube polja iz baze
     */
    private function productVideoErrorLog($field_value) {
        Log::warning('Greska u dekodiranju YouTube polja: ' . $field_value);
    }

    private function parseURL($product_video) {
        if (stripos($product_video, 'youtube') !== false || stripos($product_video, 'youtu.be') !== false) {
            $this->video_provider = 'youtube';

            // sledeci if provera da li je u bazu unet iframe tag
            if (stripos($product_video, 'https://www.youtube.com/embed/') !== false ||
                stripos($product_video, 'http://www.youtube.com/embed/') !== false ||
                stripos($product_video, 'www.youtube.com/embed/') !== false ||
                stripos($product_video, 'youtube.com/embed/') !== false ||
                stripos($product_video, 'https://www.youtube-nocookie.com/embed/') !== false ||
                stripos($product_video, 'http://www.youtube-nocookie.com/embed/') !== false ||
                stripos($product_video, 'www.youtube-nocookie.com/embed/') !== false ||
                stripos($product_video, 'youtube-nocookie.com/embed/') !== false
            ) {
                // u $position_start_1 se smesta pozicija na kojoj pocinje substring 'embed/' u okviru $product_video
                $position_start_1 = stripos($product_video, 'embed/');

                // $start je pocetna pozicija za video ID koji ide odmah posle 'embed/'.
                // Zato se na $position_start_1 dodaje broj karaktera koje 'embed/' zauzima (a to je 6)
                $start = $position_start_1 + 6;

                // sledeci kod sve do pozicije $position_start_2 se moze koristiti
                // u slucaju da zelimo da klip uvek ide od nulte startne pozicije
                // bez obzira da li je u bazi stavljeno da pocne sa neke pomerene startne pozicije

                // u $position_start_2 se smesta pozicija na kojoj pocinje substring 'frameborder' u okviru $product_video
                $position_start_2 = stripos($product_video, 'frameborder');

                // $end je krajnja pozicija za video ID tj. 2 karaktera pre nego sto pocne 'frameborder'. Zato se od $position_start_2 oduzimaju dva karaktera (koji su zapravo: (" ) navodnik i prazno mesto).
                $end = $position_start_2 - 2;
                $length = $end > $start ? ($end - $start) : $start - $end;
                // $end-$start predstavlja duzinu video ID-a koja zbog ovog moze biti dinamicka
                $this->video_id = substr($product_video, $start, $length);

            // sledeci if proverava da li je u bazu unet youtu.be link
            } elseif (stripos($product_video, 'https://youtu.be/') !== false ||
                stripos($product_video, 'http://youtu.be/') !== false ||
                stripos($product_video, 'https://www.youtu.be/') !== false ||
                stripos($product_video, 'http://www.youtu.be/') !== false ||
                stripos($product_video, 'www.youtu.be/') !== false ||
                stripos($product_video, 'youtu.be/') !== false
            ) {
                // za $position_start_1 i $start je primenjen isti princip kao iznad
                $position_start_1 = stripos($product_video, '.be/');
                $start = $position_start_1 + 4;

                // sledeci if proverava da li klip pocinje sa neke pomerene startne pozicije
                if (stripos($product_video, 't=') !== false) {
                    // u sledecem redu startnu poziciju za broj sekundi za koje je klip pomeren
                    // dobijam iz ->stripos($product_video, 't=')+2,
                    // (dodajem 2 jer mi ne treba startna pozicija za t= vec za broj sekundi koji ide iza toga)
                    $moved_start = substr($product_video, stripos($product_video, 't=') + 2);

                    // moramo da uvedemo i end poziciju da bi izbacili '?t=' iz stringa
                    $end = stripos($product_video, '?t=');

                    // u sledecem redu dobijamo ID videa cija je startna pozicija pomerena za odredjen broj sekundi
                    $this->video_id = substr($product_video, $start, ($end - $start)) . '?start=' . $moved_start;
                } else {
                    // ukoliko klip ne pocinje sa pomerene startne pozicije
                    // jednostavno uzimamo sve posle '.be/' i smestamo u video_id
                    // ovde dole se izostavlja treci parametar koji predstavlja duzinu ID-a da bi bilo dinamicko
                    $this->video_id = substr($product_video, $start);
                }
            // sledeci if proverava da li je u bazu unet klasican URL
            } elseif (stripos($product_video, 'https://www.youtube.com/watch?v=') !== false ||
                stripos($product_video, 'http://www.youtube.com/watch?v=') !== false ||
                stripos($product_video, 'www.youtube.com/watch?v=') !== false ||
                stripos($product_video, 'https://youtube.com/watch?v=') !== false ||
                stripos($product_video, 'http://youtube.com/watch?v=') !== false ||
                stripos($product_video, 'youtube.com/watch?v=') !== false
            ) {
                $position_start_1 = stripos($product_video, '?v=');
                $start = $position_start_1 + 3;

                // sledeci if proverava da li klip pocinje sa neke pomerene startne pozicije
                if ((stripos($product_video, '&feature') !== false)) {
                    // u sledecem redu startnu poziciju za broj sekundi za koje je klip pomeren dobijam iz ->stripos($product_video, '.be&t=')+6, (dodajem 6 jer mi ne treba startna pozicija za .be&t= vec za broj sekundi koji ide iza toga)
                    $moved_start = substr($product_video, (stripos($product_video, '.be&t=') + 6));

                    // moramo da uvedemo i end poziciju da bi izbacili sve posle '&feature' iz stringa
                    $end = stripos($product_video, '&feature');

                    // u sledecem redu dobijamo ID videa cija je startna pozicija pomerena za odredjen broj sekundi
                    $this->video_id = substr($product_video, $start, ($end - $start)) . '?start=' . $moved_start;
                } elseif ((stripos($product_video, '&t=') !== false)) {
                    // u sledecem redu startnu poziciju za broj sekundi za koje je klip pomeren
                    // dobijam iz ->stripos($product_video, '&t=')+3, (dodajem 3
                    // jer mi ne treba startna pozicija za &t= vec za broj sekundi koji ide iza toga)
                    $moved_start = substr($product_video, (stripos($product_video, '&t=') + 3));

                    // ovde moramo da ocistimo $moved_start jer na kraju moze imati s kao ovde: https://www.youtube.com/watch?v=E44fvv_Jvuo&t=38s
                    $moved_start_cleaned = preg_replace('/[^0-9]/', '', $moved_start);

                    // moramo da uvedemo i end poziciju
                    $end = stripos($product_video, '&t=');

                    // u sledecem redu dobijamo ID videa cija je startna pozicija pomerena za odredjen broj sekundi
                    $this->video_id = substr(
                        $product_video,
                        $start,
                        ($end - $start)
                    ) . '?start=' . $moved_start_cleaned;
                } else {
                    // ukoliko klip ne pocinje sa pomerene startne pozicije
                    // jednostavno uzimamo sve posle '?v=' i smestamo u video_id
                    // ovde dole se izostavlja treci parametar koji predstavlja duzinu ID-a da bi bilo dinamicko
                    $this->video_id = substr($product_video, $start);
                }
            }
        // ovo je u slucaju da u bazi stoji samo ID klipa; po defaultu ga vezujem za youtube trenutno
        } elseif (stripos($product_video, 'www') === false && stripos($product_video, 'http') === false) {
            $this->video_provider = 'youtube';
            $this->video_id = $product_video;
        } else {
            $this->productVideoErrorLog($product_video);
        }
    }

    public function renderHTML($url = '') {
        $this->parseURL($url);

        $args = [
            'id'        => $this->video_id,
            'provider'  => $this->video_provider,
        ];

        return view('ProductVideo/templates/ProductVideo', $args);
    }
}
