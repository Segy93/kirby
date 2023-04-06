<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddAddressForShop extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $shop = DB::table('Shops')
            ->where('name', '=', 'Kumanovska 14, Vračar')
            ->first()
        ;

        DB::table('Addresses__Main')->insert(
            [
                'city'          =>  'Beograd',
                'address'       =>  'Kumanovska 14, Vračar',
                'postal_code'   =>  '11000',
                'discr'         =>  'shop',
            ]
        );

        $address = DB::table('Addresses__Main')
            ->where('address', '=', 'Kumanovska 14, Vračar')
            ->first()
        ;

        DB::table('Addresses__Shop')->insert(
            [
                'id'            =>  $address->id,
                'shop_id'       =>  $shop->id,
                'email'         =>  'prodaja@kesezakirby.rs',
                'open_hours'    =>  'Radnim danima od 09-20 časova \nSubotom od 10-15 časova',
                'fax'           =>  '011/41-14-800',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        $address = DB::table('Addresses__Main')
            ->where('address', '=', 'Kumanovska 14, Vračar')
            ->first()
        ;

        DB::table('Orders')->where('billing_address_id', $address->id)->delete();
        DB::table('Addresses__Shop')->where('email', 'prodaja@kesezakirby.rs')->delete();
        DB::table('Addresses__Main')->where('address', 'Kumanovska 14, Vračar')->delete();
    }
}
