<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThyroidClassCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('thyroid_class_courses', function (Blueprint $table) {
            $table->increments('id');

            $table->string('sequence');
            $table->string('title')->comment('学期title');
            $table->string('comment')->comment('学期简介');
            $table->string('logo_url')->comment('logo');
            $table->string('video_url')->comment('video');

            $table->integer('teacher_id')->nullbale()->unsigned()->default(null)->comment('教师ID');
            $table->foreign('teacher_id')->references('id')->on('teachers');

            $table->integer('thyroid_class_phase_id')->unsigned()->comment('学期ID');
            $table->foreign('thyroid_class_phase_id')->references('id')->on('thyroid_class_phases');

            $table->string('qcloud_file_id')->comment('腾讯云 file_id');
            $table->string('qcloud_app_id')->comment('腾讯云 app_id');

            $table->tinyInteger('is_show')->default(0)->comment('是否显示');
            $table->integer('play_count')->nullable()->default(0)->comment('播放次数');

            $table->timestamps();
            $table->softDeletes();
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
//        Schema::table('thyroid_class_courses', function (Blueprint $table) {
//            $table->dropForeign('thyroid_class_courses_teacher_id_foreign');
//            $table->dropForeign('thyroid_class_courses_phase_id_foreign');
//        });
        Schema::drop('thyroid_class_courses');
    }
}
