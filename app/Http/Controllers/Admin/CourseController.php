<?php

namespace App\Http\Controllers\Admin;

use App\Models\ThyroidClassPhase;
use Illuminate\Http\Request;
use App\Models\ThyroidClassCourse;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    /**
     * Data filtering.
     *
     * @return array
     */
    private function formatData(Request $request)
    {
        $data = [
            'title' => $request->input('title'),
            'comment' => $request->input('title'),
            'logo_url' => $request->input('logo_url'),
            'sequence' => $request->input('sequence'),
            'thyroid_class_phase_id' => $request->input('thyroid_class_phase_id'),
            'qcloud_file_id' => $request->input('qcloud_file_id'),
            'qcloud_app_id' => $request->input('qcloud_app_id'),
            'is_show' => $request->input('is_show')
        ];


        return $data;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.tables.course', [
            'courses' => ThyroidClassCourse::paginate('10'),
            'phases' => ThyroidClassPhase::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $data = $this->formatData($request);
        ThyroidClassCourse::create($data);

        \Session::flash('alert', [
            'type' => 'success',
            'title' => '添加成功',
            'message' => '添加课程成功',
        ]);

        return redirect('/admin/course');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        if (ThyroidClassCourse::find($id)->delete()) {
            \Session::flash('alert', [
                'type' => 'success',
                'title' => '删除成功',
                'message' => '删除课程成功'
            ]);
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $data = $this->formatData($request);
        $teacher = ThyroidClasscourse::find($id);
        $teacher->update($data);

        \Session::flash('alert', [
            'type' => 'success',
            'title' => '修改成功',
            'message' => '修改课程成功',
        ]);

        return redirect('/admin/course');
    }
}
