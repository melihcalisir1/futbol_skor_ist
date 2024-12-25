<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futbol Skor İstatistik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white">
    <div class="flex min-h-screen">
        <!-- Sol Sidebar -->
        <div class="w-64 bg-gray-800 border-r border-gray-700">
            <div class="p-4">
                <h1 class="text-xl font-bold mb-4">
                    <i class="fas fa-futbol mr-2"></i>
                    Futbol Skor
                </h1>
                @include('partials.leagues-sidebar')
            </div>
        </div>

        <!-- Ana İçerik -->
        <div class="flex-1">
            <!-- Üst Navigasyon -->
            <nav class="bg-gray-800 p-4 border-b border-gray-700">
                @include('partials.top-nav')
            </nav>

            <!-- İçerik Alanı -->
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
