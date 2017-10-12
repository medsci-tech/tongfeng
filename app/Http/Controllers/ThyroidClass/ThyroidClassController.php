<?php

namespace App\Http\Controllers\ThyroidClass;

use App\Http\Controllers\WebController;
use App\Models\Student;
use App\Models\Banner;
use App\Models\PlayLog;
use App\Models\Teacher;
use App\Models\ThyroidClass;
use App\Models\ThyroidClassCourse;
use App\Models\ThyroidClassPhase;
use App\Models\Questionnaire;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * Class ThyroidClassController
 * @package App\Http\Controllers\ThyroidClass
 */
class ThyroidClassController extends WebController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $aciviveNaire = Questionnaire::where('n_status',1)->first();

        return view('thyroid-class.index', [
            'teachers' => Teacher::all(),
            'thyroidClass' => ThyroidClass::all()->first(),
            'thyroidClassPhases' => ThyroidClassPhase::where('is_show', 1)->get(),
            'studentCount' => Student::count(),
            'playCount' => \Redis::command('GET', ['play_count']),
            'banners' => Banner::where('page', 'index')->where('status', 1)->orderBy('weight', 'desc')->get(),
            'aciviveNaire'=>$aciviveNaire
        ]);
    }

    /**
     * @param Request $request
     */
    public function teachers(Request $request)





    {

    }

    /**
     * @param Request $request
     */
    public function questions(Request $request)
    {

    }

    /**
     * @param Request $request
     */
    public function phases(Request $request)
    {

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function enter()
    {
        $student = Student::find(\Session::get('studentId'));
        if ($student->entered_at) {
            return response()->json(['success' => false, 'error_message' => '已报名']);
        } else {
            $student->entered_at = Carbon::now();
            $student->save();
            \Redis::command('INCR', ['enter_count']);
            return response()->json(['success' => true]);
        }
    }

    function updateStatistics()
    {
        \Redis::command('SET', ['enter_count', Student::whereNotNull('entered_at')->count()]);
        \Redis::command('SET', ['student_count', Student::all()->count()]);


        $courses = ThyroidClassCourse::all();


        foreach ($courses as $course) {
            $playCount = PlayLog::where('thyroid_class_course_id', $course->id)->sum('play_times');
            \Redis::command('HSET', ['course_play_count', $course->id, $playCount]);
        }

        $playCount = 0;
        foreach ($courses as $course) {
            $playCount += \Redis::command('HGET', ['course_play_count', $course->id]);
        }

        \Redis::command('SET', ['play_count', $playCount]);

//        $phases = ThyroidClassPhase::all();
//        foreach ($phases as $phase) {
//            $coursePlayCount = 0;
//            foreach ($phase->thyroidClassCourses as $course) {
//                $coursePlayCount += \Redis::command('HGET', ['course_play_count', $course->id]);
//            }
//
//            \Redis::command('HSET', ['phase_play_count', $phase->id, $coursePlayCount]);
//        }
    }
}
