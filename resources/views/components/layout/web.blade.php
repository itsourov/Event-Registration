<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    @vite('resources/css/app.css')
    @filamentStyles

</head>

<body class="bg-fixed bg-cover font-poppins px-2 " style="background-image: url('{{ asset('images/backdrop.png') }}')">
<x-impersonate::banner />

{{ $slot }}


@livewire('notifications')

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
