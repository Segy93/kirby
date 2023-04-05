<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStoImportName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('Categories')->where('name', 'Sto I Stolice')->update([
            'name_import'   =>  'Sto',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('Categories')->where('name', 'Sto I Stolice')->update([
            'name_import'   =>  'Dopuni',
        ]);
    }
}
