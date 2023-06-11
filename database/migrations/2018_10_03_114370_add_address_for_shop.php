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
            ->where('name', '=', 'Kraljice Katarine 55, Čukarica')
            ->first()
        ;

        DB::table('Addresses__Main')->insert(
            [
                'city'          =>  'Beograd',
                'address'       =>  'Kraljice Katarine 55, Čukarica',
                'postal_code'   =>  '11030',
                'discr'         =>  'shop',
            ]
        );

        $address = DB::table('Addresses__Main')
            ->where('address', '=', 'Kraljice Katarine 55, Čukarica')
            ->first()
        ;

        DB::table('Addresses__Shop')->insert(
            [
                'id'            =>  $address->id,
                'shop_id'       =>  $shop->id,
                'email'         =>  'prodaja@kesezakirby.rs',
                'open_hours'    =>  'Radnim danima od 09-20 časova \nSubotom od 10-15 časova',
                'fax'           =>  '011/2544-660',
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
            ->where('address', '=', 'Kraljice Katarine 55, Čukarica')
            ->first()
        ;

        DB::table('Orders')->where('billing_address_id', $address->id)->delete();
        DB::table('Addresses__Shop')->where('email', 'prodaja@kesezakirby.rs')->delete();
        DB::table('Addresses__Main')->where('address', 'Kraljice Katarine 55, Čukarica')->delete();
    }
}
