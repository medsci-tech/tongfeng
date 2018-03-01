<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'ThyroidClass\ThyroidClassController@index');


Route::group(['prefix' => 'home', 'namespace' => 'Home'], function () {

    Route::group(['prefix' => 'register'], function () {
        Route::get('/create', 'RegisterController@create');
        Route::post('/store', 'RegisterController@store');
        Route::any('/sms', 'RegisterController@sms');
        Route::get('/success', 'RegisterController@success');
        Route::get('/error', 'RegisterController@error');
    });

    Route::group(['prefix' => 'replenish'], function () {
        Route::get('/create', 'ReplenishController@create');
        Route::post('/store', 'ReplenishController@store');
        Route::post('/success', 'ReplenishController@success');
        Route::post('/error', 'ReplenishController@error');
    });

    Route::get('/login', 'LoginController@showLoginForm');
    Route::get('/incrTimes', 'LoginController@incrTimes');
    Route::any('/logout', 'LoginController@logout');
    Route::post('/login', 'LoginController@login');
    Route::get('/pwd2back_view', 'LoginController@pwd2backGet');
    Route::post('/pwd2back_post', 'LoginController@pwd2backPost');
    Route::get('/send_sms', 'LoginController@send_sms');

    Route::get('/test', function(){
        return view('test');
    });

    Route::group(['prefix' => 'question','middleware' =>'login'], function () {
        Route::get('/{nid}', 'QResultController@index');
        Route::post('/answer/{nid}', 'QResultController@answer');
        Route::get('/success/answer', 'QResultController@success');
    });
});



Route::group(['prefix' => 'thyroid-class', 'namespace' => 'ThyroidClass'], function () {

    Route::get('/index', 'ThyroidClassController@index');
    Route::get('/phases', 'ThyroidClassController@phases');
    Route::get('/teachers', 'ThyroidClassController@teachers');
    Route::get('/questions', 'ThyroidClassController@questions');
    Route::any('/enter', 'ThyroidClassController@enter');
    Route::any('/update-statistics', 'ThyroidClassController@updateStatistics');

    Route::group(['prefix' => 'sign-up'], function () {
        Route::get('/create', 'SignUpController@create');
        Route::post('/store', 'SignUpController@store');
    });

    Route::group(['prefix' => 'course'], function () {
        Route::get('/view', 'CourseController@view');
        Route::any('/timer', 'CourseController@timer');
    });

});

Route::auth();
Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'auth'], function () {
    Route::any('/student-logs', 'AdminController@studentLogs');
    Route::any('/reset-pwd', 'StudentController@resetPwd');
    Route::group(['prefix' => 'excel'], function () {
        Route::get('/', 'ExcelController@excelForm');
        Route::post('/student', 'ExcelController@student');
        Route::any('/export-student', 'ExcelController@exportStudent');
        Route::post('/play-log', 'ExcelController@playLog');
        Route::post('/play-log-detail', 'ExcelController@playLogDetail');
        Route::any('/test', 'ExcelController@test');
        Route::any('/test_log_detail', 'ExcelController@getLogDetail');
        Route::any('/logs2excel', 'ExcelController@logs2Excel');
        Route::any('/export-phone', 'ExcelController@exportPhone');
    });

    Route::get('/', 'TeacherController@index');
    Route::resource('teacher', 'TeacherController');
    Route::resource('thyroid', 'ThyroidController');
    Route::resource('phase', 'PhaseController');
    Route::resource('course', 'CourseController');
    Route::resource('banner', 'BannerController');
    Route::resource('student', 'StudentController');
    Route::resource('private-student', 'StudentController@privateinfo');
    Route::any('update','StudentController@updateDate');
    Route::group(['prefix' => 'statistic'], function () {
        Route::get('/area-map', 'StatisticController@areaMap');
        Route::get('/province-map', 'StatisticController@provinceMap');
        Route::get('/register-bar', 'StatisticController@registerBar');
        Route::get('/class-pie', 'StatisticController@classPie');
        Route::get('/update', 'StatisticController@update');
    });
    Route::group(['prefix' => 'naire'], function () {
        Route::get('/', 'QuestionnairController@index');
        Route::get('/summary', 'QuestionnairController@summary');
        Route::get('/summary/detail/{id}', 'QuestionnairController@summaryDetail');
        Route::get('/summary/users/{id}', 'QuestionnairController@summaryUsers');
        Route::get('/summary/user/{nid}/{sid}', 'QuestionnairController@voteDetail');
        Route::resource('naire', 'QuestionnairController');
        Route::post('question/save', 'QuestionController@save');
        Route::resource('question', 'QuestionController');
        Route::get('editquestion/{nid}','QuestionnairController@editQuestion');
    });
});



Route::group(['prefix' => 'charts'], function(){
    Route::get('/bar', function() {
        return view('backend.charts.charts_bar');
    });
    Route::get('/map', function() {
        return view('backend.charts.charts_map');
    });
    Route::get('/map2', function() {
        return view('backend.charts.charts_map_province');
    });
    Route::get('/pie', function() {
        return view('backend.charts.charts_pie');
    });
    Route::get('/polar', function() {
        return view('backend.charts.charts_polar');
    });
    Route::get('/line', function() {
        return view('backend.charts.charts_line');
    });
});



