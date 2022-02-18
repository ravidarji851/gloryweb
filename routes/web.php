<?php

use Illuminate\Support\Facades\Route;

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
})->name('home');


Route::group(['prefix'=>'employe'], function () {
    Route::get('/index','EmployeController@index')->name('employe');
    Route::get('/create','EmployeController@create')->name('employe.create');
    Route::get('/edit/{id?}','EmployeController@create')->name('employe.edit');
    Route::post('/add','EmployeController@store')->name('employe.add');
    Route::post('/update','EmployeController@update')->name('employe.update');
    Route::post('/delete','EmployeController@destroy')->name('employe.delete');
    Route::any('/ajax/get-list','EmployeController@getlist')->name('employe.list');
    
});

Route::group(['prefix'=>'company'], function () {
    Route::get('/index','CompanyController@index')->name('company');
    Route::get('/create','CompanyController@create')->name('company.create');
    Route::get('/edit/{id?}','CompanyController@create')->name('company.edit');
    Route::post('/add','CompanyController@store')->name('company.add');
    Route::post('/update','CompanyController@update')->name('company.update');
    Route::post('/delete','CompanyController@destroy')->name('company.delete');
    Route::any('/ajax/get-list','CompanyController@getlist')->name('company.list');
    
});