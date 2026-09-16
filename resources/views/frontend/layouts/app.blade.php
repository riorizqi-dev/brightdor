<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'BrightDor') — {{ config('app.name', 'BrightDor') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600;9..144,700;9..144,800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23c6436a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M12 3.5 15 7.5l-3 4-3-4 3-4Z'/><path d='M12 11.5v3'/><circle cx='12' cy='16.5' r='4.5'/></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="flex min-h-screen flex-col bg-white text-ink-600 font-sans antialiased selection:bg-rose-600/20 selection:text-ink-900">
    <x-frontend.navigation/>

    <main class="flex-1 pt-[96px] lg:pt-[104px]">
        @yield('content')
    </main>

    <x-frontend.footer/>

    @stack('scripts')
</body>
</html>