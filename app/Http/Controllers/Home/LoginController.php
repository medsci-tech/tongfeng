<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\WebController;
use App\Models\MessageVerify;
use App\Models\Student;
use App\Models\Questionnaire;
use App\Models\QuestionResult;

/**
 * Class LoginController
 * @package App\Http\Controllers\Home
 */
class LoginController extends WebController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('home.login');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login(Request $request)
    {

        $messages = [
            'phone.required' => '手机号未填写',
            'phone.digits' => '收获格式不正确',
            'password.required' => '密码未填写'
        ];

        $rules = [
            'phone' => 'required|digits:11',
            'password' => 'required'
        ];
        $validator = \Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        $student = Student::where('phone', '=', $request->input('phone'))->first();
        if (!$student) {
            $validator->errors()->add('phone', '电话号未注册');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        if (!\Hash::check($request->input('password'), $student->password)) {
            $validator->errors()->add('password', '密码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/


        if ($student->name
            && $student->sex
            && $student->email
            && $student->birthday
            && $student->office
            && $student->title
            && $student->province
            && $student->city
            && $student->area
            && $student->hospital_name
        ) {
            \Session::set('replenished', true);
        }

        \Session::set('studentId', $student->id);

        if (\Session::has('return_referer')) {
            $returnUrl = \Session::get('return_referer');
            \Session::remove('return_referer');
            return redirect($returnUrl);
        } else {
            $activeNaire = QuestionResult::getActiveQuestionNaire($student->id);
            if ($activeNaire!=null){
                return redirect("/home/question/$activeNaire->id");
            }
            return redirect('/');
        }
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function logout()
    {
        \Session::clear();
        return redirect('/');
    }

    public function pwd2backGet(){
        return view('home.pwd2back');
    }


    public function pwd2backPost(Request $request){
        $messages = [
            'phone.required' => '手机号未填写',
            'phone.digits' => '收获格式不正确',
            'auth_code.required' => '验证码未填写',
            'auth_code.digits' => '验证码格式不正确',
            'password.required' => '密码未填写',
            'password.confirmed' => '请保持密码一致',
            'password.alpha_num' => '6-16位密码，区分大小写，不可用特殊符号',
            'password.between' => '6-16位密码，区分大小写，不可用特殊符号',
        ];

        $rules = [
            'phone' => 'required|digits:11,phone',
            'password' => 'required|alpha_num|between:6,15',
            'auth_code' => 'required|digits:6',
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

//        dd($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        $messageVerify = MessageVerify::where('phone', $request->input('phone'))->where('status', 0)->orderBy('created_at', 'desc')->first();
        if (!$messageVerify) {
            $validator->errors()->add('phone', '验证码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        if ($messageVerify->code != $request->input('auth_code')) {
            $validator->errors()->add('auth_code', '验证码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        $result = Student::where('phone', $request->input('phone'))->update([
            'password' => \Hash::make($request->input('password')),
        ]);

        if($result){
            $messageVerify->status = 1;
            $messageVerify->save();
            return redirect('/home/login');
        }

    }

    public function send_sms(Request $request)
    {
        $messages = [
            'phone.required' => '手机号未填写',
            'phone.digits' => '手机号格式不正确',
        ];

        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:11,'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error_message' => $validator->errors()->first()]);
        } /*if>*/


        $student = Student::where('phone', $request->input('phone'))->first();
        if (!$student){
            return response()->json(['success' => false, 'error_message' => '手机号未注册']);
        }
        $result = \Message::createVerify($request->input('phone'));
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

} /*class*/
