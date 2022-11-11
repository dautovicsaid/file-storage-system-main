<?php

use App\Http\Controllers\Auth\SocialLoginController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SharedFileController;
use App\Http\Controllers\UserController;
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
    return redirect('/login');
});

Auth::routes();
//Route::post('/files', [App\Http\Controllers\FileController::class, 'store'])->name('files.store');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home.index');
    Route::get('files/{file}/download',[FileController::class,'downloadFile'])->name('files.download');
    Route::get('/files', [FileController::class,'index'])->name('files.index');
    Route::post('/files', [FileController::class,'store'])->name('files.store');
    Route::delete('/files/delete', [FileController::class,'destroy'])->name('files.destroy');
    Route::patch('/files/rename', [FileController::class,'renameFile'])->name('files.rename');
    Route::get('/shared-files/{folder_id?}',[SharedFileController::class,'getAllSharedFiles'])->name('sharedFiles.getAllSharedFiles');
    Route::get('/folders/{folder}',[FolderController::class,'getAllFilesFromFolder'])->name('folders.index');
    Route::post('/folders',[FolderController::class,'store'])->name('folders.store');
    Route::get('users/admin-panel',[UserController::class,'getAllUsers'])->name('users.getAllUsers');
    Route::patch('users/storage-limit-update',[UserController::class,'changeStorageLimit'])->name('users.changeStorageLimit');
});

Route::get('/auth/google/login',[SocialLoginController::class,'initGoogleLogin'])->name('login.google');
Route::get('/auth/google/callback',[SocialLoginController::class,'googleLoginCallback'])->name('login.google.callback');
Route::get('/auth/twitter/login',[SocialLoginController::class,'initTwitterLogin'])->name('login.twitter');
Route::get('/auth/twitter/callback',[SocialLoginController::class,'twitterLoginCallback'])->name('login.twitter.callback');
Route::get('/auth/facebook/login',[SocialLoginController::class,'initFacebookLogin'])->name('login.facebook');
Route::get('/auth/facebook/callback',[SocialLoginController::class,'facebookLoginCallback'])->name('login.facebook.callback');


