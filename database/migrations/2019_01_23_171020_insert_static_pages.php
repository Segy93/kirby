<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertStaticPages extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        /** @var App\Models\StaticPages\Category|null */
        $info = DB::table('StaticPages__Categories')
            ->where('name', 'Info')
            ->first()
        ;

        /** @var App\Models\StaticPages\Category|null */
        $service = DB::table('StaticPages__Categories')
            ->where('name', 'Usluge')
            ->first()
        ;

        if ($info === null || $service === null) {
            throw new \Exception('Kategorija nije pronadjena');
        }

        DB::table('StaticPages__Main')->insert([
            [
                'title' => 'Opšti uslovi',
                'category_id' => $info->id,
                'order_page' => 1,
                'text' => view('pages/migrated-01-opsti-uslovi'),
            ],
            [
                'title' => 'Način isporuke',
                'category_id' => $info->id,
                'order_page' => 2,
                'text' => view('pages/migrated-02-nacin-isporuke'),
            ],
            [
                'title' => 'Način plaćanja',
                'category_id' => $info->id,
                'order_page' => 3,
                'text' => view('pages/migrated-03-nacin-placanja'),

            ],
            [
                'title' => 'Kako naručiti',
                'category_id' => $info->id,
                'order_page' => 4,
                'text' => view('pages/migrated-04-kako-naruciti'),
            ],
            [
                'title' => 'Korporativna prodaja',
                'category_id' => $info->id,
                'order_page' => 5,
                'text' => view('pages/migrated-05-korporativna-prodaja'),
            ],
            [
                'title' => 'Taxfree',
                'category_id' => $info->id,
                'order_page' => 6,
                'text' => view('pages/migrated-06-tax-free'),
            ],
            [
                'title' => 'Ugovor o prodaji',
                'category_id' => $info->id,
                'order_page' => 7,
                'text' => view('pages/migrated-07-ugovorne-odredbe'),
            ],
            [
                'title' => 'Uslovi garancije',
                'category_id' => $info->id,
                'order_page' => 8,
                'text' => view('pages/migrated-08-uslovi-garancije'),
            ],
            [
                'title' => 'Zaštita privatnosti',
                'category_id' => $info->id,
                'order_page' => 10,
                'text' => view('pages/migrated-09-zastita-privatnosti'),
            ],
            [
                'title' => 'Hosting',
                'category_id' => $service->id,
                'order_page' => 1,
                'text' => view('pages/migrated-10-hosting'),
            ],
            [
                'title' =>  'Kontakt',
                'category_id' => $service->id,
                'order_page' => 2,
                'text' => view('pages/migrated-11-kontakt'),
            ],
            [
                'title' => 'Servis',
                'category_id' => $service->id,
                'order_page' => 3,
                'text' => view('pages/migrated-12-servis'),
            ],
            [
                'title' =>  'Web programiranje',
                'category_id' => $service->id,
                'order_page' => 4,
                'text' => view('pages/migrated-13-web-programiranje'),
            ],
        ]);

        $page_conditions = DB::table('StaticPages__Main')->where('title', 'Opšti uslovi')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_conditions->id,
            'title'        => 'Opšti uslovi',
            'description'  => 'Koristeći web stranicu www.kesezakirby.rs prihvatate pravila propisana ovim Opštim uslovima. Ukoliko ne prihvatate ove uslove nemojte koristiti web sajt www.kesezakirby.rs u daljem tekstu Sajt“.',
            'url' => 'opsti-uslovi',
        ]);

        $page_delivery = DB::table('StaticPages__Main')->where('title', 'Način isporuke')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_delivery->id,
            'title' => 'Način isporuke',
            'description' => 'Isporuku robe vršimo na teritorije cele Srbije svakim radnim danom i subotom. Nedeljom isporuke nisu moguće. Dostava se vrši putem kurirske službe D Expres i prema opštim pravilima ove kurirske službe, kao i našim dostavnim vozilima',
            'url'          => 'nacin-isporuke',
        ]);

        $page_payment = DB::table('StaticPages__Main')->where('title', 'Način plaćanja')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_payment->id,
            'title' => 'Način plaćanja',
            'description' => 'Plaćanje možete izvršiti na više načina: Poručivanje na sajtu: 1. Plaćanje pouzećem gotovinski ( u ovoj opciji robu plaćate gotovinski kuriru prilikom preuzimanja paketa ) 2. Plaćanje na tekući račun',
            'url' => 'nacin-placanja',
        ]);

        $page_ordering = DB::table('StaticPages__Main')->where('title', 'Kako naručiti')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_ordering->id,
            'title' => 'Kako naručiti',
            'description' => 'Dolaskom na web sajt www.kesezakirby.rs odmah u gornjem delu samog sajta ugledćete meni sa ispisanim kategorijama. Klikom na sam meni otvara se opširnija ponuda. Izaberite vrstu uređaja i klikom na isti.',
            'url' => 'kako-naruciti',
        ]);

        $page_corporate = DB::table('StaticPages__Main')->where('title', 'Korporativna prodaja')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_corporate->id,
            'title' => 'Korporativna prodaja',
            'description' => 'Pravnim licima nudimo posebne uslove prodaje. Za veće kupovine kao što su kompletno opremanje firmi, velike nabavke ili projekte bilo koje vrste, odobravamo dodatne popuste i garantujemo najbolje cene na tržištu.',
            'url' => 'korporativna-prodaja',
        ]);

        $page_tax_free = DB::table('StaticPages__Main')->where('title', 'Taxfree')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_tax_free->id,
            'title' => 'Tax free',
            'description' => 'Tax free The procedure is very simple: You are required to bring 3 copies of LNPDV or SNPDV form',
            'url' => 'tax-free',
        ]);

        $page_contract = DB::table('StaticPages__Main')->where('title', 'Ugovor o prodaji')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_contract->id,
            'title' => 'Ugovorne odredbe',
            'description' => 'Član 1. Prihvatanjem ovih ugovornih uslova smatramo da je korisnik usluga upoznat sa prezentovanim obeležjima proizvoda koji se kupuje , o ceni istog , načinu plaćanja , rokovima isporuke i načinu izvršenja iste.Takođe smatramo da je korisnik usluga pravi',
            'url' => 'ugovorne-odredbe',
        ]);

        $page_warranty = DB::table('StaticPages__Main')->where('title', 'Uslovi garancije')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_warranty->id,
            'title' => 'Uslovi garancije',
            'description' => 'Izjavljujemo da će kupljeni proizvod za koji je izdata garancija o saobraznosti funkcionisati ispravno ukoliko se njima rukuje prema uputstvu i tehničkim specifikacijama koje na te proizvode daje proizvođač.',
            'url' => 'uslovi-garancije',
        ]);

        $page_privacy = DB::table('StaticPages__Main')->where('title', 'Zaštita privatnosti')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_privacy->id,
            'title' => 'Zaštita privatnosti',
            'description' => 'Ova Politika stupa na snagu dana 1. decembar 2019. godine. Politika privatnosti će biti predmet redovnih revizija, a svaku ažuriranu verziju ćemo postaviti na ovu internet stranicu.',
            'url' => 'zastita-privatnosti',
        ]);

        $page_hosting = DB::table('StaticPages__Main')->where('title', 'Hosting')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_hosting->id,
            'title' => 'Hosting',
            'description' => 'Naši serveri se nalaze u Nemačkoj što u kombinacji sa 100Mbit/s portovima daje izvrsnu i brzu komunikaciju sa serverom u svim smerovima.',
            'url' => 'hosting',
        ]);

        $page_contact = DB::table('StaticPages__Main')->where('title', 'Kontakt')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_contact->id,
            'title' => 'Kontakt',
            'description' => 'Monitor System d.o.o Kumanovska 14 11000 Beograd, Vračar Tel: +381 11 3086 979 Tel: +381 11 3086 979 Mob: +381 65 3086 979 Email: prodaja@kesezakirby.rs ; nabavka@kesezakirby.rs Web: www.kesezakirby.rs',
            'url' => 'kontakt',
        ]);

        $page_service = DB::table('StaticPages__Main')->where('title', 'Servis')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_service->id,
            'title' => 'Servis',
            'description' => 'Naš servisni tim čini grupa stručnjaka sa dugogodišnjim iskustvom u servisiranju i održavanju računara. Stalno usavršavanje i praćenje trendova iz oblasti IT-a daju nam za pravo da možemo reći da za nas ne postoji nerešiv problem.',
            'url' => 'servis',
        ]);


        $page_development = DB::table('StaticPages__Main')->where('title', 'Web programiranje')->first();
        DB::table('SEO')->insert([
            'machine_name' => "static_" . $page_development->id,
            'title' => 'Web programiranje',
            'description' => 'Uslužno programiranje prema potrebi korisnika. Programiranje i rad sa bazama. Projektovanje informacionih sistema. Online shopovi. Online komunikacija. Socijalne mreže. Katalozi proizvoda. CMS.',
            'url' => 'web-programiranje',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $last_conditions = DB::table('StaticPages__Main')->where('title', 'Opšti uslovi')->first();
        DB::table('SEO')->where('machine_name', "static_" . $last_conditions->id)->delete();

        $page_delivery = DB::table('StaticPages__Main')->where('title', 'Način isporuke')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_delivery->id)->delete();

        $page_payment = DB::table('StaticPages__Main')->where('title', 'Način plaćanja')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_payment->id)->delete();

        $page_ordering = DB::table('StaticPages__Main')->where('title', 'Kako naručiti')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_ordering->id)->delete();

        $page_corporate = DB::table('StaticPages__Main')->where('title', 'Korporativna prodaja')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_corporate->id)->delete();

        $page_tax_free = DB::table('StaticPages__Main')->where('title', 'Taxfree')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_tax_free->id)->delete();

        $page_contract = DB::table('StaticPages__Main')->where('title', 'Ugovor o prodaji')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_contract->id)->delete();

        $page_warranty = DB::table('StaticPages__Main')->where('title', 'Uslovi garancije')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_warranty->id)->delete();

        $page_privacy = DB::table('StaticPages__Main')->where('title', 'Zaštita privatnosti')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_privacy->id)->delete();

        $page_hosting = DB::table('StaticPages__Main')->where('title', 'Hosting')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_hosting->id)->delete();

        $page_contact = DB::table('StaticPages__Main')->where('title', 'Kontakt')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_contact->id)->delete();

        $page_service = DB::table('StaticPages__Main')->where('title', 'Servis')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_service->id)->delete();

        $page_development = DB::table('StaticPages__Main')->where('title', 'Web programiranje')->first();
        DB::table('SEO')->where('machine_name', "static_" . $page_development->id)->delete();

        DB::table('StaticPages__Main')->where('title', 'Opšti uslovi')->delete();
        DB::table('StaticPages__Main')->where('title', 'Način isporuke')->delete();
        DB::table('StaticPages__Main')->where('title', 'Način plaćanja')->delete();
        DB::table('StaticPages__Main')->where('title', 'Kako naručiti')->delete();
        DB::table('StaticPages__Main')->where('title', 'Korporativna prodaja')->delete();
        DB::table('StaticPages__Main')->where('title', 'Taxfree')->delete();
        DB::table('StaticPages__Main')->where('title', 'Ugovor o prodaji')->delete();
        DB::table('StaticPages__Main')->where('title', 'Uslovi garancije')->delete();
        DB::table('StaticPages__Main')->where('title', 'Zaštita privatnosti')->delete();
        DB::table('StaticPages__Main')->where('title', 'Hosting')->delete();
        DB::table('StaticPages__Main')->where('title', 'Kontakt')->delete();
        DB::table('StaticPages__Main')->where('title', 'Servis')->delete();
        DB::table('StaticPages__Main')->where('title', 'Web programiranje')->delete();
    }
}
