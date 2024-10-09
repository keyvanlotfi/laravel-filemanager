<?php

namespace KeyvanLotfi\FileManager\Providers;

use Illuminate\Support\ServiceProvider;
use KeyvanLotfi\FileManager\Contracts\FileManagerRepositoryInterface;
use KeyvanLotfi\FileManager\Contracts\UploadRepositoryInterface;
use KeyvanLotfi\FileManager\Repositories\FileManagerRepository;
use KeyvanLotfi\FileManager\Repositories\UploadRepository;


class FileManagerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/filemanager.php', 'filemanager'
        );


        $this->app->bind(FileManagerRepositoryInterface::class, FileManagerRepository::class);
        $this->app->bind(UploadRepositoryInterface::class, UploadRepository::class);
    }



    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'filemanager');



        $this->publishes([
            __DIR__.'/../../resources/assets/css/style.css' => public_path('vendor/filemanager/css/style.css'),
            __DIR__.'/../../resources/assets/css/fontawesome.css' => public_path('vendor/filemanager/css/fontawesome.css'),
            __DIR__.'/../../resources/assets/css/bootstrap.min.css' => public_path('vendor/filemanager/css/bootstrap.min.css'),
            __DIR__.'/../../resources/assets/build/app.js' => public_path('vendor/filemanager/js/app.js'),
            __DIR__.'/../../resources/assets/fonts' => public_path('vendor/filemanager/fonts'),
        ], 'public');


        $this->publishes([
            __DIR__ . '/../../config/filemanager.php' => config_path('filemanager.php'),
        ], 'config');
    }
}
