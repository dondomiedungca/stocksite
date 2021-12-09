<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel Control</title>
    <script>
        var session = <?= json_encode(Auth::user()); ?>
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Assistant&family=Quicksand&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body>
    @yield('content')
    @yield('js')
    @yield('style')
</body>
</html>