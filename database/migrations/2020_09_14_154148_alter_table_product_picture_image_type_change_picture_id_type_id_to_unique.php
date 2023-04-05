<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AlterTableProductPictureImageTypeChangePictureIdTypeIdToUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::delete('DELETE t1 FROM ProductPictureImageType t1, ProductPictureImageType t2
        WHERE t1.id > t2.id AND t1.picture_id = t2.picture_id AND t1.type_id = t2.type_id');

        Schema::table('ProductPictureImageType', function(Blueprint $table)
        {
            $table->unique(['picture_id', 'type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ProductPictureImageType', function (Blueprint $table) {
            $table->dropUnique('productpictureimagetype_picture_id_type_id_unique');
        });
    }
}
