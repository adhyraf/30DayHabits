<!DOCTYPE html>
<html lang="id" class="{{ session('theme') ?? '' }}">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', '30 Day Habits')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token untuk AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        };
    </script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 dark:text-gray-100 min-h-screen text-gray-800 font-sans transition-colors duration-300">
    <div class="container mx-auto px-4 py-8">
        @yield('content')
    </div>

    @yield('scripts')
</body>
</html>
