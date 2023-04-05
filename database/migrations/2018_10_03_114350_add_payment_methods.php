<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::table('PaymentMethods')->insert(
            [
                'method'          =>  'Keš',
                'label'           =>  'Gotovinom prilikom preuzimanja',
                'description'     =>  'Plaćanje prilikom pruzimanja ( plaćanje u gotovini , kuriru ili u maloprodajnom objektu ako lično pruzimate )',
            ]
        );

        DB::table('PaymentMethods')->insert(
            [
                'method'          =>  'Virmanski',
                'label'           =>  'Virmanski- želim da mi pošaljete predračun',
                'description'     =>  'Uplate fizičkih i pravnih lica proveravaju se svaka 3 sata. Vaša uplata mora biti evidentirana na našem tekućem računu kako bi poslati pošiljku.',
            ]
        );

        // DB::table('PaymentMethods')->insert(
        //     [
        //         'method'          =>  'Kartica',
        //         'description'     =>  '',
        //     ]
        // );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $methods_to_delete =
        [
            'Virmanski',
            'Gotovinom prilikom preuzimanja',
        ];

        DB::table('PaymentMethods')->whereIn('method', $methods_to_delete)->delete();
    }
}
