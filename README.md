# laravel-filemanager
A fast, simple and ajax file manager for Laravel created with Vuejs 3

Features:
* Its fast, light, simple, beautiful and easy to use.
* It is not complicated and consists of only one css and one js.
* It follows clean coding, SOLID principles and design patterns.
* It supports defining middleware for routes in the config file.
* it has responsive web design.
* Integration with popular WYSIWYG editors and ckeditor.
* it works with Laravel file system.
* Rename, Move, Delete, Download and create Directories by Ajax (vuejs).
* it has config file to control settings.


## How Does It look like?
![filemanager1](https://github.com/user-attachments/assets/d7ea43c4-1c01-4497-be03-5c6f32ccad48)
![filemanager2](https://github.com/user-attachments/assets/f6e9fc53-d4c0-4368-863d-dd4d6bc3e9bb)



## Requirements
 * php >= 8.0
 * Laravel >= 8


## Full Installation Guide
1- Install package:

    composer require keyvanlotfi/laravel-filemanager
    

2- Publish config file (config/filemanager.php):

    php artisan vendor:publish --tag=config


3- Publish package assets (css and js):

    php artisan vendor:publish --tag=public
    
it will create a folder named (vendor) in your laravel public path and (filemanager) folder inside it.

if you changed your public folder manually, be sure to place this vendor folder inside your public folder.


## How to use
yoursite.com/prefix

Enjoy to use 😊
