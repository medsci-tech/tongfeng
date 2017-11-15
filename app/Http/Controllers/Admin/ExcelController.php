<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlayLog;
use App\Models\Student;
use App\Models\ThyroidClassCourse;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * Class ExcelController
 * @package App\Http\Controllers\Admin
 */
class ExcelController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function excelForm()
    {
        return view('admin.excel');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function student(Request $request)
    {
        $excel = $request->file('excel');
        \Excel::load($excel, function ($reader) use ($excel) {
            $excelData = \Excel::load($excel)->get()->toArray();
            foreach ($excelData as $data) {
                $data['password'] = \Hash::make(substr($data['phone'], -6));
                Student::create($data);
            }
        });
        return redirect('/admin/excel');
    }

    /**
     * 导出学生信息
     */
    public function exportStudent(Request $request){
        $head1 = ['学生信息','','','','','','','','','','','','','',''];
        $head2= ['id', '手机号', '姓名','省', '市', '区', '医院', '医院级别' ,'科室', '职称', '邮箱', '性别','年龄','二维码注册','报名时间'];
        $fixLength = count($head2);
        $courses = ThyroidClassCourse::all();
        $coursesArray = array();
        foreach ($courses as $course) {
            $coursesArray[$course->id] = $course->title;
            $head1[]= $course->title;
            $head1[]= '';
            $head2[] = '学习时长/min';
            $head2[] = '点击次数/次';
        }
        $head1[]= '课程统计';
        $head1[]= '';
        $head2[] = '总学习时长/min';
        $head2[] = '总点击次数/次';


        $students = Student::all();
        $cellData = [$head1,$head2];//dd($cellData);
        foreach ($students as $student) {
                $old = '';
                if (!empty($student->birthday)){
                    $dt = Carbon::parse($student->birthday);                   
                    $old = Carbon::now()->diffInYears($dt);
                }
                $totalDuration = 0;
                $totalCount = 0;
                $playArr = [];
                
                foreach($student->playLogs as $log) {
                    foreach($log->details as $key => $value) {
                        if (array_key_exists($coursesArray[$log->thyroid_class_course_id],$playArr)){
                            $play = &$playArr[$coursesArray[$log->thyroid_class_course_id]];
                            $play ['duration'] = $play ['duration'] + $value;
                            $play ['count']++ ;
                        }else{
                            $play =[
                                'duration'=> $value,
                                'count'=>1
                            ];
                            $playArr[$coursesArray[$log->thyroid_class_course_id]] = $play;
                        }
                        $totalDuration +=$value;
                        unset($play);  
                    }
                    $totalCount += count($log->details);
                 }

                $item = [$student->id, $student->phone,$student->name, 
                             $student->province, $student->city, 
                            $student->area, $student->hospital_name,$student->hospital_level,
                            $student->office, $student->title, $student->email,$student->sex==0? '女':'男',$old,
                            empty($student->promo_code)?'否':'是',$student->entered_at
                        ];
                for ($i=$fixLength; $i<count($head1)-2;$i=$i+2){
                    if (array_key_exists($head1[$i],$playArr)){
                        $item[] = $playArr[$head1[$i]]['duration']/60;
                        $item[] = $playArr[$head1[$i]]['count'];
                    }else{
                        $item[] = 0;
                        $item[] = 0;
                    }
                }
                $item[] = $totalDuration/60;
                $item[] = $totalCount;
                array_push($cellData, $item);

        }
        \Excel::create('学员信息', function ($excel) use ($cellData, $head2, $fixLength) {
            $excel->sheet(date('Y-M-D'), function ($sheet) use ($cellData, $head2, $fixLength) {
                //$sheet->appendRow(['学生信息','','','','','','','','','','','']);
                $sheet->rows($cellData);
                $sheet->mergeCells('A1:O1');
                for ($i=$fixLength; $i<count($head2);$i=$i+2){
                    $start = 65 + $i;
                    $sheet->mergeCells(chr($start). '1:'.chr($start+1).'1');

                    $sheet->cell(chr($start). '1', function($cell) {
                        $cell->setAlignment('center'); 
                        // $cell->setBackground('#ff0000');
                    });

                    // $sheet->cell(chr($start). '2:'.chr($start+1).'2', function($cell) {
                    //     $cell->setBackground('#ff0000');
                    // });
                }

                $sheet->cell('A1', function($cell) {
                        $cell->setAlignment('center'); 
                        $cell->setBorder('solid');
                        // $cell->setBackground('#00df00');
                });
                // $sheet->cell('A2:M2', function($cell) {
                //     $cell->setBackground('#00df00');
                // });
                // $sheet->cell(chr(count($head2)-2 + 65). '1', function($cell) {
                //     $cell->setBackground('#0000ff');
                // });
                // $sheet->cell(chr(count($head2)-2 + 65). '2:'.chr(count($head2)-1 + 65).'2', function($cell) {
                //     $cell->setBackground('#0000ff');
                // });
                // $sheet->setBorder('A1', 'thin');
                // $sheet->setAllBorders('thin');
            });
        })->export('xls');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function playLog(Request $request)
    {
        $excel = $request->file('excel');
        \Excel::load($excel, function ($reader) use ($excel) {
            $excelData = \Excel::load($excel)->get()->toArray();

            $students = Student::get(['id', 'phone']);
            $studentsArray = array();
            foreach ($students as $student) {
                $studentsArray[$student->phone] = $student->id;
            }

            foreach ($excelData as $data) {
                $logData = [
                    'student_id' => $studentsArray[$data['phone']],
                    'thyroid_class_phase_id' => $data['thyroid_class_phase_id'],
                    'thyroid_class_course_id' => $data['thyroid_class_course_id'],
                    'play_times' => $data['play_times'],
                    'play_duration' => $data['play_duration'],
                    'student_course_id' => $studentsArray[$data['phone']] . '-' . $data['thyroid_class_course_id']
                ];
                PlayLog::create($logData);
            }
        });
        return redirect('/admin/excel');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function playLogDetail(Request $request)
    {
        $excel = $request->file('excel');
        \Excel::load($excel, function ($reader) use ($excel) {
            $excelData = \Excel::load($excel)->get()->toArray();

            $students = Student::get(['id', 'phone']);
            $studentsArray = array();
            foreach ($students as $student) {
                $studentsArray[$student->phone] = $student->id;
            }

            foreach ($excelData as $data) {
                $logId = 'student_course_id:' . $studentsArray[$data['phone']] . '-' . $data['thyroid_class_course_id'];
                $date = $data['clicked_at'];
                $this->getClickedAt($logId, $date);
                \Redis::command('HSET', [$logId, $date, $data['play_duration']]);
            }
        });
        return redirect('/admin/excel');
    }

    /**
     *
     */
    public function test()
    {
        //dd(\Redis::command('flushall'));
        $logs = PlayLog::all();
        foreach ($logs as $log) {
            $logId = 'student_course_id:' . $log->student_course_id;
            $data = \Redis::command('HGETALL', [$logId]);
            $playTimes = \Redis::command('HLEN', [$logId]);
            if ($log->play_times == $playTimes) {
                echo $log->id . ':success<br>';
                // 次数
                $playDuration = 0;
                foreach ($data as $item) {
                    $playDuration += $item;
                }
                if ($playDuration == $log->play_duration) {
                    echo 'play_duration:success';
                } else {
                    echo 'play_duration:fail;log_play_duration:' . $log->play_duration . ';redis_play_duration:' . $playDuration;
                }
                echo '<hr>';
            } else {
                // 次数
                echo $log->play_times;
                echo $playTimes;
                var_dump(\Redis::command('hgetall', [$logId]));
                echo $log->id . ':fail';
                // 时间
                $playDuration = 0;
                foreach ($data as $item) {
                    $playDuration += $item;
                }
                if ($playDuration == $log->play_duration) {
                    echo 'play_duration:success';
                } else {
                    echo 'play_duration:fail;log_play_duration:' . $log->play_duration . ';redis_play_duration:' . $playDuration;
                }

                echo '<hr>';
            }
        }
    }

    /**
     * @param $logId
     * @param $date
     */
    function getClickedAt($logId, &$date)
    {
        if (\Redis::command('HEXISTS', [$logId, $date])) {
            $date = date('Y-m-d H:i:s', strtotime('+1 second', strtotime($date)));;
            $this->getClickedAt($logId, $date);
        } else {
            return;
        }
    }

    /**
     * @param Request $request
     */
    function getLogDetail(Request $request)
    {
        //'student_course_id:' . $studentsArray[$data['phone']] .'-'.$data['thyroid_class_course_id'];
        $logId = 'student_course_id:' . $request->input('student_id') . '-' . $request->input('course_id');
        echo \Session::get('studentId');
        dd(\Redis::command('hgetall', [$logId]));
    }


    /**
     * @return \Illuminate\Http\RedirectResponse
     */
//    public function logs2Excel() {
//        $courses = ThyroidClassCourse::all();
//        $coursesArray = array();
//        foreach($courses as $course) {
//            $coursesArray[$course->id] = [
//                'course' => $course->sequence.$course->title,
//                'phase' => $course->thyroidClassPhase->title,
//            ];
//        }
//
//        $students = Student::get(['id', 'phone', 'name']);
//        $studentsArray = array();
//        foreach($students as $student) {
//            $studentsArray[$student->id] = ['name' => $student->name, 'phone' => $student->phone];
//        }
//
//        $studentCourseIds = \Redis::command('keys', ['student_course_id*']);
//        $cellData = [['单元名称', '课程名称', '学员姓名',  '学员电话', '起始观看时间', '观看时长(单位/秒)']];
//        foreach($studentCourseIds as $studentCourseId) {
//            //echo $studentCourseId.'<hr />';
//            $logs = \Redis::command('HGETAll', [$studentCourseId]);
//            $logArray = explode('-' ,substr($studentCourseId, strpos($studentCourseId, ':')+1));
//            foreach($logs as $key => $value) {
//                if($key > '2016-09-08 00:00:00' && $value > 7200) {
//                    $item = [
//                        $coursesArray[$logArray[1]]['phase'],
//                        $coursesArray[$logArray[1]]['course'],
//                        $studentsArray[$logArray[0]]['name'],
//                        $studentsArray[$logArray[0]]['phone'],
//                        $key,
//                        7200,
//                    ];
//                } else {
//                    $item = [
//                        $coursesArray[$logArray[1]]['phase'],
//                        $coursesArray[$logArray[1]]['course'],
//                        $studentsArray[$logArray[0]]['name'],
//                        $studentsArray[$logArray[0]]['phone'],
//                        $key,
//                        $value,
//                    ];
//                }
//                array_push($cellData, $item);
//            }
//        }
//
//        \Excel::create('公开课观看日志',function($excel) use ($cellData){
//            $excel->sheet(date('Y-M-D'), function($sheet) use ($cellData){
//                $sheet->rows($cellData);
//            });
//        })->export('xls');
//
//        return redirect('/admin/student');
//    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logs2Excel()
    {
        $courses = ThyroidClassCourse::all();
        $coursesArray = array();
        foreach ($courses as $course) {
            $coursesArray[$course->id] = [
                'course' => $course->sequence . $course->title,
                'phase' => $course->thyroidClassPhase->title
            ];
        }

        $students = Student::get(['id', 'phone', 'name']);
        $studentsArray = array();
        foreach ($students as $student) {
            $studentsArray[$student->id] = ['name' => $student->name, 'phone' => $student->phone];
        }

        $playLogs = PlayLog::where('updated_at', '>', '2016-10-01')->get();
        $cellData = [['单元名称', '课程名称', '学员姓名', '学员电话', '起始观看时间', '观看时长(单位/秒)']];
        foreach ($playLogs as $playLog) {
            //echo $studentCourseId.'<hr />';
            foreach ($playLog->details as $key => $value) {
                $item = [
                    $coursesArray[$playLog->thyroid_class_course_id]['phase'],
                    $coursesArray[$playLog->thyroid_class_course_id]['course'],
                    $studentsArray[$playLog->student_id]['name'],
                    $studentsArray[$playLog->student_id]['phone'],
                    $key,
                    $value
                ];
                array_push($cellData, $item);
            }
        }
        \Excel::create('公开课观看日志', function ($excel) use ($cellData) {
            $excel->sheet(date('Y-M-D'), function ($sheet) use ($cellData) {
                $sheet->rows($cellData);
            });
        })->
        export('xls');

        return redirect('/admin/student');
    }

    function exportPhone()
    {
        $students = Student::all();
        $array = [];
        foreach ($students as $student) {
            array_push($array, $student->phone);
        }
        dd(json_encode($array));
    }
}
