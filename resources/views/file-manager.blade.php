<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Fast File Manager</title>

    <link rel="stylesheet" href="{{ asset('vendor/filemanager/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/filemanager/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/filemanager/css/style.css') }}">

</head>
<body>


<div id="app">
    <file-manager prefix="{{ config('filemanager.prefix', 'filemanager') }}"></file-manager>
</div>


<script src="{{ asset('vendor/filemanager/js/app.js') }}"></script>
{{--@vite(['resources/js/app.js'])--}}
</body>
</html>
