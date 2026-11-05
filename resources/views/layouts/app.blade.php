<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blockchain eBL - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-800 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <a href="{{ route('bl.index') }}" class="font-bold text-xl">Blockchain eBL</a>
            <div class="space-x-4">
                <a href="{{ route('bl.index') }}">Daftar BL</a>
                <a href="{{ route('bl.create') }}">Issue Baru</a>
                <a href="{{ route('bl.chain') }}">Blockchain</a>
            </div>
        </div>
    </nav>

    <main class="container mx-auto p-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
