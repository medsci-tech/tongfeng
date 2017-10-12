<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\WebController;
use App\Models\MessageVerify;
use App\Models\Student;
use Carbon\Carbon;

class RegisterController extends WebController
{
    //
    public function create(Request $request)
    {
        $promoCode = $request->input('promo_code');
        if ($promoCode == null){
            $promoCode='';
        }
        if ($request->has('from-promo')){
            $promoCode='yes';
        }
        return view('home.register.create',['promo_code'=>$promoCode]);
    }

    public function store(Request $request)
    {
        $messages = [
            'phone.required' => '手机号未填写',
            'phone.digits' => '收获格式不正确',
            'phone.unique' => '手机号已注册',
            'auth_code.required' => '验证码未填写',
            'auth_code.digits' => '验证码格式不正确',
            'password.required' => '密码未填写',
            'password.confirmed' => '请保持密码一致',
            'password.alpha_num' => '6-16位密码，区分大小写，不可用特殊符号',
            'password.between' => '6-16位密码，区分大小写，不可用特殊符号',
            'save-area.required' => '地区不能为空',
            'hospital.required' => '医院不能为空',
            'save_hospital_level.required' => '医院级别不能为空',
            'save_office.required' => '科室不能为空',
            'save_title.required' => '职称不能为空',
            'name.required' => '姓名未填写',
            'sex.required' => '性别未填写',
            'email.required' => '邮箱未填写',
            'birthday.required' => '生日未填写',
            'email.email' => '邮箱格式不正确'
        ];

        $rules = [
            'phone' => 'required|digits:11|unique:students,phone',
            'password' => 'required|alpha_num|between:6,15',
            'auth_code' => 'required|digits:6',
            'save-area' => 'required',
            'hospital' => 'required',
            'save_hospital_level' => 'required|max:255',
            'save_office' => 'required|max:255',
            'save_title' => 'required|max:255',
            'name' => 'required',
            'sex' => 'required',
            'email' => 'required|email',
            'birthday' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules, $messages);

//        dd($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        $messageVerify = MessageVerify::where('phone', $request->input('phone'))->where('status', 0)->orderBy('created_at', 'desc')->first();
        if (!$messageVerify) {
            $validator->errors()->add('phone', '电话号码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        if ($messageVerify->phone != $request->input('phone')) {
            $validator->errors()->add('phone', '电话号码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        if ($messageVerify->code != $request->input('auth_code')) {
            $validator->errors()->add('auth_code', '验证码错误');
            return redirect()->back()->withErrors($validator->errors())->withInput();
        } /*if>*/

        $student = new Student();
        $student->phone = $request->input('phone');
        $student->password = \Hash::make($request->input('password'));

        $student->province = $request->input('save-province');
        $student->city = $request->input('save-city');
        $student->area = $request->input('save-area');
        $student->hospital_name = $request->input('hospital');
        $student->hospital_level = $request->input('save_hospital_level');
        $student->office = $request->input('save_office');
        $student->title = $request->input('save_title');
        
        $student->name = $request->input('name');
        $student->nickname = $request->input('nickname');
        $student->sex = $request->input('sex');
        $student->email = $request->input('email');
        $student->birthday = $request->input('birthday');
        $student->promo_code = $request->input('promo_code');

        $student->entered_at = Carbon::now();
        $student->save();

        $messageVerify->status = 1;
        $messageVerify->save();

        \Redis::command('INCR', ['enter_count']);
        
        \Session::set('studentId', $student->id);
        \Session::set('replenished', true);  //to show name insread of phone.
        if (!empty($request->input('promo_code'))){
            return view('home.register.success-qr');
        }
        return view('home.register.success');
    }

    public function sms(Request $request)
    {
        $messages = [
            'phone.required' => '手机号未填写',
            'phone.digits' => '手机号格式不正确',
            'phone.unique' => '手机号已注册'
        ];

        $validator = \Validator::make($request->all(), [
            'phone' => 'required|digits:11|unique:students,phone,'
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error_message' => $validator->errors()->first()]);
        } /*if>*/

        $result = \Message::createVerify($request->input('phone'));
        if ($result) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function success()
    {
        return view('home.register.success-qr');
    }

    public function error()
    {
        return view('home.register.error');
    }

}
