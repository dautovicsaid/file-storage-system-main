<?php

use App\Http\Controllers\API\FileController;
use App\Http\Controllers\API\FolderController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
    return $request->user();

});

Route::get('/files/{id}/search-shareable-users', [FileController::class, 'searchShareableUsers'])->name('files.searchShareableUsers');

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/folders/{id}/files', [FolderController::class, 'getAllFilesInFolder'])->name('folders.getAllFilesInFolder');
    Route::post('/files/{id}/share', [FileController::class, 'shareFile'])->name('files.share');
    Route::delete('/shared-files/{file_id}/{user_id}/delete', [FileController::class, 'destroy'])->name('shared-files.destroy');
    Route::get('/files/{id}/shared-users', [FileController::class, 'getSharedWithUsers'])->name('files.getSharedWithUsers');
});
