<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('people')->group(function () {
    Route::resource('clients', 'ClientController');

    Route::resource('users', 'UserController');
});
Route::resource('templates', 'TemplateController');

Route::get('questions/{id}', 'QuestionController@create')->name('questions-index');
Route::post('question', 'QuestionController@store')->name('question.save');
Route::get('question/{id}/edit', 'QuestionController@edit')->name('question.edit');
Route::get('question/{id}', 'QuestionController@show')->name('question.show');
Route::patch('question/{id}', 'QuestionController@update')->name('question.update');
Route::delete('question/{id}', 'QuestionController@destroy')->name('question.destroy');
Route::get('survey-type/{type}', 'AllocationController@getSurveyType')->name('survey-type');
//Route::get('template-status/{id}/{action}','TemplateController@changeStatus');
Route::get('approve-survey/{id}/{action}','AllocationController@approveSurvey');
Route::get('email-survey/{id}','AllocationController@emailSurvey')->name('send.survey');

//Route::get('add-question', 'QuestionController@create');
//Route::get('question-create/{id}','QuestionController@create')->name('questions.create');
//Route::get('question-create/{id}','QuestionController@templateQuestion')->name('question-create');
//Route::get('questions/{id}','QuestionController@index')->name('questions.index');


Route::resource('answers', 'AnswerController');


//Route::resource('allocations', 'AllocationController');
//
//Route::resource('allocations', 'AllocationController');

Route::resource('allocations', 'AllocationController');
Route::get('survey-response/{id}/{token}', 'SurveyController@show');


Route::resource('survey', 'SurveyController');


Route::resource('responses', 'ResponseController');
//Route::get('survey-responses/{survey}','ResponseController@showResponses');
