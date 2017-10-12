<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoToThyroidClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('thyroid_classes', function(Blueprint $table) {
            $table->string('qcloud_file_id')->comment('��Ѷ�� file_id');
            $table->string('qcloud_app_id')->comment('��Ѷ�� app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
