<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayStatisticsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('play_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('student_id')->unsigned()->comment('学生ID');
            $table->foreign('student_id')->references('id')->on('students');

            $table->integer('teacher_id')->unsigned()->comment('讲师ID');
            $table->foreign('teacher_id')->references('id')->on('teachers');

            $table->integer('thyroid_class_phase_id')->unsigned()->comment('单元ID');
            $table->foreign('thyroid_class_phase_id')->references('id')->on('thyroid_class_phases');

            $table->integer('thyroid_class_course_id')->unsigned()->comment('课程ID');
            $table->foreign('thyroid_class_course_id')->references('id')->on('thyroid_class_courses');

            $table->integer('play_duration')->nullable()->comment('播放总时长,单位秒');
            $table->integer('play_times')->nullable()->comment('播放次数');

            $table->string('student_course_id');
            $table->unique('student_course_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('play_logs', function (Blueprint $table) {
            $table->dropForeign('play_logs_teacher_id_foreign');
            $table->dropForeign('play_logs_student_id_foreign');
            $table->dropForeign('play_courses_thyroid_class_phase_id_foreign');
            $table->dropForeign('play_courses_thyroid_class_course_id_foreign');
        });
        Schema::drop('play_logs');

    }
}
