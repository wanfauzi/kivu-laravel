@php
    $pageTitle = $title ?? 'KIVU — Marketplace Micro-Freelance Mahasiswa × UMKM';
    $pageDescription = $description ?? 'KIVU menghubungkan mahasiswa berbakat dengan UMKM yang butuh bantuan nyata — desain, website, konten, dan tugas kreatif lainnya.';
    $pageImage = $ogImage ?? asset('images/kivu-logo.png');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="{{ asset('images/kivu-logo-tab.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="KIVU">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta property="og:locale" content="id_ID">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $pageImage }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-kivu-bg font-sans text-kivu-text antialiased">
    {{ $slot }}
    <x-ui.toast />
</body>
</html>
