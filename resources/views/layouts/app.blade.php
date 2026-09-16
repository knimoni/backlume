<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Backlume' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#F7F7F5] font-sans text-[#171717]">

    <div class="mx-auto max-w-6xl px-6 py-6">

        {{-- Navigation --}}
        <nav class="mb-10 flex items-center justify-between border-b border-[#E5E5E5] pb-4">

            <a href="{{ route('dashboard') }}" class="text-lg font-semibold tracking-tight">
                Backlume
            </a>

            <div class="flex items-center gap-6 text-sm">

                <a href="{{ route('dashboard') }}" class="text-[#737373] transition-colors hover:text-[#171717]">
                    Dashboard
                </a>

                <a href="{{ route('library') }}" class="text-[#737373] transition-colors hover:text-[#171717]">
                    My Library
                </a>

                <a href="{{ route('steam.games') }}" class="text-[#737373] transition-colors hover:text-[#171717]">
                    Search Steam
                </a>

            </div>

        </nav>

        @yield('content')

    </div>

</body>

</html>
