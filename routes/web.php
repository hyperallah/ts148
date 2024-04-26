<?php

use App\Http\Controllers\Web\File\CreateFileController;
use App\Http\Controllers\Web\File\DownloadFileAsZipController;
use App\Http\Controllers\Web\File\FindFileByIdController;
use App\Http\Controllers\Web\File\ListFilesController;
use App\Http\Controllers\Web\File\UploadFilesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('files/download/{filename}',DownloadFileAsZipController::class)->name('files.download');
Route::get('files',ListFilesController::class)->name('files.index');
Route::get('files/upload',CreateFileController::class)->name('files.create');
Route::post('files/store',UploadFilesController::class)->name('files.store');
Route::get('files/{id}',FindFileByIdController::class)->name('files.show');
