<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use KeyvanLotfi\FileManager\Controllers\FileManagerController;
use KeyvanLotfi\FileManager\Controllers\UploadController;


Route::get('/ttest', function () {

});




Route::prefix(config('filemanager.prefix', 'filemanager'))->middleware(config('filemanager.middlewares'))->group(function () {
    Route::get('/', function () {
        return view('filemanager::file-manager');
    });
    Route::get('/getitems', [FileManagerController::class, 'getItems']);



    Route::post('/rename', [FileManagerController::class, 'rename']);
    Route::post('/move', [FileManagerController::class, 'move']);
    Route::post('/delete', [FileManagerController::class, 'delete']);
    Route::post('/download', [FileManagerController::class, 'download']);
    Route::post('/create-folder', [FileManagerController::class, 'createFolder']);
    Route::post('/upload', [UploadController::class, 'upload']);
});
