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

});



Route::group(['middleware'=>'superadmin'],function(){

    Route::get('/superadmin/dashboard',[SuperAdminController::class,'dashboard'])->name('superadmin.dashboard');   
   
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
