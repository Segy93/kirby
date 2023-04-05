<?php

use App\Providers\ShopService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertDataIntoPhoneNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $shop = ShopService::getShopById(1);
        if ($shop !== null) {
            $shop_id = $shop->id;
            DB::table('PhoneNumbers')->insert(
                [
                    [
                        'shop_id'   =>  $shop_id,
                        'phone_nr'  =>  '011/41-14-700',
                    ],
                    [
                        'shop_id'   =>  $shop_id,
                        'phone_nr'  =>  '011/41-14-800',
                    ],
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('PhoneNumbers')->truncate();
    }
}
