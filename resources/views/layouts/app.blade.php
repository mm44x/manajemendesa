<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script>
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.classList.add('dark');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-white">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow dark:bg-gray-800 dark:text-white dark:border-gray-600">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
        <!-- Footer -->
        <footer class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400 pb-4">
            <hr class="mb-2 border-gray-300 dark:border-gray-700">
            <div>
                &copy; {{ date('Y') }} Aplikasi Manajemen Warga —
                Dibuat oleh <span class="font-semibold text-blue-600 dark:text-blue-400">Pratama Ardy Prayoga</span>.
                <span class="hidden sm:inline">All rights reserved.</span>
            </div>
        </footer>
    </div>
</body>

</html>
