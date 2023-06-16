<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\frontendController as front;
use App\Http\Controllers\backendController as back;
use App\Http\Controllers\reportController as report;
use App\Http\Controllers\settingController as setting;
use App\Http\Controllers\ajaxController as ajax;
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
    return redirect('/home');
});
Route::get('/home/{key?}', [front::class, 'home'])->name('home');

Route::group(['middleware' => 'keycloak-web'], function () {
    // Route::get('/{key?}', [front::class, 'home'])->name('home');
    Route::get('/logout', [back::class, 'logout'])->name('logout');

    //ลงทะเบียนวัสดุ
        Route::get('/parcel/register/{id?}', [back::class, 'parcelRegister'])->name('parcel.register');
        Route::post('/parcel/parcel_add', [back::class, 'parcel_add'])->name('parcel.add');
        Route::post('/parcel/parcel_update/{id?}', [back::class, 'parcel_update'])->name('parcel.update');
        Route::get('/parcel/parcel_delete/{id?}', [back::class, 'parcel_delete'])->name('parcel.delete');
        Route::get('/parcel_status/{id?}/{val?}', [back::class, 'parcel_status'])->name('parcel.status');
    //End
    Route::get('/parcel/detail/{id?}', [back::class, 'parcelDetail'])->name('parcel.detail');

    Route::get('/parcel/in', [back::class, 'parcelIn'])->name('parcel.in');
    Route::post('/parcel/in_store', [back::class, 'parcelInStore'])->name('parcel.in.store');

    Route::get('/parcel/out', [back::class, 'parcelOut'])->name('parcel.out');
    Route::post('/parcel/out_store', [back::class, 'parcelOutStore'])->name('parcel.out.store');

   

    // report
        Route::get('/report_in/{fyear?}', [report::class, 'parcel_in'])->name('report.in');
        Route::get('/report_in_ajax/{id?}', [report::class, 'parcel_in_ajax'])->name('report.in.ajax');

        Route::get('/report_out/{fyear?}', [report::class, 'parcel_out'])->name('report.out');
        Route::get('/report_date/{d1?}/{d2?}', [report::class, 'parcel_date'])->name('report.date');
        Route::get('/report_person/{name?}', [report::class, 'parcel_person'])->name('report.person');
        Route::get('/report_one/{parcel_id?}', [report::class, 'parcel_one'])->name('report.one');

        Route::get('/report_balance', [report::class, 'balance'])->name('report.balance');
    //End

    // setting
        Route::get('/setting/area', [setting::class, 'area'])->name('setting.area');
        Route::post('/setting/area_add', [setting::class, 'area_add'])->name('setting.area.add');
        Route::post('/setting/area_update/{id?}', [setting::class, 'area_update'])->name('setting.area.update');
        Route::get('/setting/area_del/{id?}', [setting::class, 'area_delete'])->name('setting.area.delete');

        Route::get('/setting/group', [setting::class, 'group'])->name('setting.group');
        Route::get('/setting/group_status/{id?}/{val?}', [setting::class, 'group_status'])->name('setting.group.status');

        Route::get('/setting/user', [setting::class, 'user'])->name('setting.user');
        Route::post('/setting/user_add', [setting::class, 'user_add'])->name('setting.user.add');
        Route::post('/setting/user_update/{cid?}', [setting::class, 'user_update'])->name('setting.user.update');
        Route::get('/setting/user_delte/{cid?}', [setting::class, 'user_delte'])->name('setting.user.delete');
        Route::get('/ajax/user_detail/{cid?}', [ajax::class, 'user_detail'])->name('user_detail');

        Route::get('/setting/sign_list', [setting::class, 'sign_list'])->name('setting.sign_list');
    // end setting


});