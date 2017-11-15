<?php

namespace App\Http\Controllers\Admin;

use App\Models\PrivateStudent;
use App\Models\Student;
use App\Http\Controllers\Controller;
use App\Models\ThyroidClassCourse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $courseArray = [];
        foreach (ThyroidClassCourse::all() as $course) {
            $courseArray[$course->id] = $course->title;
        }
        if ($request->has('search')) {
            $search = $request->input('search');
            $query = Student::search($search);
        } else {
            $search = '';
            $query= new Student;
        }
        if ($request->has('filterByPromo') && $request->input('filterByPromo')=='on'){
            $query = $query->whereNotNull('promo_code')->where('promo_code','!=','');
        }
        return view('backend.tables.student', [
            'students' => $query->paginate('10'),
            'courseArray' => $courseArray,
            'search' => $search,
            'filterByPromo'=> $request->has('filterByPromo') 
        ]);
    }

    /**
     *
     */
    public function Logs2Excel()
    {
        $keys = \Redis::command('keys ', ["student_course_id*"]);
        $logs = \Redis::command('HGETALL ', $keys);
        dd($logs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPwd(Request $request)
    {
        $phone = $request->input('phone');
        $password = \Hash::make(substr($phone, -6));
        $student = Student::where('phone', $phone)->first();
        $student->password = $password;

        return response()->json([
            'success' => $student->save()
        ]);
    }

    public function updateDate(Request $request){
        if($request->isMethod('POST')){
            $file = $request->file('file')->getRealPath();
            //dd($file);
            \Excel::load($file,function($reader){
                $datas = $reader->skip(2)->all()->toArray();//dd($datas);
                foreach ($datas as $data){
                    $id = (int)$data[0];
                    $info = Student::find($id);
                    $info->name = $data[2];
                    $info->province = $data[3];
                    $info->city = $data[4];
                    $info->area = $data[5];
                    $info->hospital_name = $data[6];
                    $info->hospital_level = $data[7];
                    $info->office = $data[8];
                    $info->title = $data[9];
                    $info->email = $data[10];
                    $info->sex = ($data[11]=='男'?1:0);
                    //dd($info);
                    $info->save();
                }
                dd('导入成功');
            });

        }
        return view('backend.tables.update');
    }

    public function privateinfo(){
        return view('backend.tables.private_student',['students'=>PrivateStudent::paginate(10)]);
    }
}
