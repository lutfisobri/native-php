<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data->title }}</title>
    <!-- <script src="{{ 'resources/js/js.js' }}"></script> -->
</head>
<body>
    <img src="{{ asset('/images/Logo.png') }}" alt="Logo" width="10%">
    <h1>{{ $data->title }}</h1>
    <p>{{ $data->content }}</p>
    <a href="/test">klik me</a>
</body>
</html>