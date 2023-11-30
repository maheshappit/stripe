<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CsvController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Auth\RegisterController;

Route::get('/', function () {
    return redirect(route('login'));
});



// Route::post('login-functionality',[AdminController::class,'login_functionality'])->name('login.functionality');


Route::get('superadmin/login',[SuperAdminController::class,'login_form'])->name('superadmin.login');
Route::post('superadmin/register',[AdminController::class,'create'])->name('superadmin.register');








Route::middleware(['checkUserRole'])->group(function () {
    
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::any('upload',[CsvController::class,'upload'])->name('upload');

    Route::get('edit', [App\Http\Controllers\HomeController::class, 'edit'])->name('user.edit');
    Route::get('/conferences',[ConferenceController::class,'index'])->name('show.conferences');
    Route::post('conferenceDetails/upload',[ConferenceController::class,'store'])->name('conferencedetails.save');
    Route::any('/user/update', [App\Http\Controllers\HomeController::class, 'update'])->name('user.update');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::get('/show-report', [App\Http\Controllers\UserController::class, 'showReport'])->name('show.report');
    Route::post('/download-report', [App\Http\Controllers\UserController::class, 'downloadReport'])->name('report.download');
    Route::post('/download-emails', [App\Http\Controllers\UserController::class, 'downloadEmails'])->name('download.email');
    // Route::view('/upload', 'upload-form'); // Display the form
    Route::post('upload',[CsvController::class,'upload'])->name('user.upload');
    Route::any('show-upload-form',[CsvController::class,'show'])->name('show.upload');
    Route::get('/upload-csv-progress', [CsvController::class,'progress'])->name('progress');
    Route::any('/all-conferences/{id}', [App\Http\Controllers\HomeController::class,'allClients'])->name('all-conferences');
    Route::any('/all-articles/{id}', [App\Http\Controllers\HomeController::class,'allTopics'])->name('all-articles');

    
    

});

Route::group(['middleware'=>'admin'],function(){

    Route::get('admin/users', [AdminController::class, 'users'])->name('admin.users');
    Route::any('admin/upload',[AdminController::class,'upload'])->name('admin.upload');
    Route::any('admin/show',[AdminController::class,'show'])->name('admin.show.upload');
    Route::get('admin/conferences',[AdminController::class,'conferences'])->name('admin.show.conferences');
    Route::any('admin/dashboard',[AdminController::class,'dashboard'])->name('admin.dashboard');
    Route::get('admin/show-report', [AdminController::class, 'showReport'])->name('admin.show.report');
    Route::post('/admin/download-report', [AdminController::class, 'downloadReport'])->name('admin.report.download');
    Route::get('admin/show-users', [AdminController::class, 'AllUsers'])->name('admin.show.allusers');
    Route::get('admin/conference', [AdminController::class, 'AllUsers']);
    Route::get('admin/conference/{id}', [AdminController::class, 'UserShow'])->name('admin.conference.show');
    Route::post('admin/user/update', [AdminController::class, 'userUpdate'])->name('admin.user.update');
    Route::any('admin/user/delete', [AdminController::class, 'userDelete'])->name('admin.user.delete');
    Route::any('admin/all-conferences/{id}', [AdminController::class,'allClients'])->name('admin.all.conferences');
    Route::any('admin/all-articles/{id}', [AdminController::class,'allTopics'])->name('admin.all.articles');

});



Route::group(['middleware'=>'superadmin'],function(){

    Route::get('superadmin/dashboard',[SuperAdminController::class,'dashboard'])->name('superadmin.dashboard');   
    Route::get('superadmin/conferences',[SuperAdminController::class,'conferences'])->name('superadmin.show.conferences');
    Route::any('superadmin/show',[SuperAdminController::class,'show'])->name('superadmin.show.upload');
    Route::get('superadmin/show-report', [SuperAdminController::class, 'showReport'])->name('superadmin.show.report');
    Route::post('superadmin/create-admin', [SuperAdminController::class, 'createAdmin'])->name('superadmin.create');
    Route::get('superadmin/users', [SuperAdminController::class, 'users'])->name('superadmin.users');
    Route::any('superadmin/all-conferences/{id}', [SuperAdminController::class,'allClients'])->name('superadmin.all.conferences');
    Route::any('superadmin/all-articles/{id}', [SuperAdminController::class,'allTopics'])->name('superadmin.all.articles');
    Route::any('superadmin/upload',[SuperAdminController::class,'upload'])->name('superadmin.upload');
    Route::get('superadmin/show-users', [SuperAdminController::class, 'AllUsers'])->name('superadmin.show.allusers');
    Route::get('superadmin/conference/{id}', [SuperAdminController::class, 'UserShow'])->name('superadmin.conference.show');
    Route::post('superadmin/user/update', [SuperAdminController::class, 'userUpdate'])->name('superadmin.user.update');
    Route::any('superadmin/user/delete', [SuperAdminController::class, 'userDelete'])->name('superadmin.user.delete');

    Route::get('superadmin/show-report', [SuperAdminController::class, 'showReport'])->name('superadmin.show.report');
    Route::post('superadmin/download-report', [SuperAdminController::class, 'downloadReport'])->name('superadmin.report.download');
});






// Auth::routes();

Route::get('user/verify-otp', [App\Http\Controllers\Auth\LoginController::class,'getVerifyOTP'])->name('user.getVerifyOTP');
Route::post('user/verify-otp', [App\Http\Controllers\Auth\LoginController::class,'postVerifyOTP'])->name('user.postVerifyOTP');
Route::post('user/resend-otp', [App\Http\Controllers\Auth\LoginController::class,'resndOTP'])->name('user.resndOTP');
Route::post('user/login-with-otp', [App\Http\Controllers\Auth\LoginController::class,'loginWithOTP'])->name('user.loginWithOTP');

Route::get('login', [App\Http\Controllers\Auth\LoginController::class,'showLoginForm'])->name('login');
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class,'logout'])->name('logout');


//admin login routes

Route::get('admin/verify-otp', [App\Http\Controllers\AdminController::class,'getVerifyOTP'])->name('admin.getVerifyOTP');
Route::post('admin/verify-otp', [App\Http\Controllers\AdminController::class,'postVerifyOTP'])->name('admin.postVerifyOTP');
Route::post('admin/resend-otp', [App\Http\Controllers\AdminController::class,'resndOTP'])->name('admin.resndOTP');
Route::post('admin/login-with-otp', [App\Http\Controllers\AdminController::class,'loginWithOTP'])->name('admin.loginWithOTP');


//super admin login routes

Route::get('superadmin/verify-otp', [App\Http\Controllers\SuperAdminController::class,'getVerifyOTP'])->name('superadmin.getVerifyOTP');
Route::post('superadmin/verify-otp', [App\Http\Controllers\SuperAdminController::class,'postVerifyOTP'])->name('superadmin.postVerifyOTP');
Route::post('superadmin/resend-otp', [App\Http\Controllers\SuperAdminController::class,'resndOTP'])->name('superadmin.resndOTP');
Route::post('superadmin/login-with-otp', [App\Http\Controllers\SuperAdminController::class,'loginWithOTP'])->name('superadmin.loginWithOTP');

Route::get('/clear', function () {

 $exitCode = Artisan::call('optimize:clear');

  $exitCode = Artisan::call('config:clear');



  $exitCode = Artisan::call('cache:clear');



  $exitCode = Artisan::call('config:cache');



  return 'DONE'; //Return anything



});
