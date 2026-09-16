@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#737373]">
        Your gaming library
    </p>

    <h1 class="text-3xl font-semibold tracking-tight">
        Halo, {{ $user->name }}!
    </h1>

    <p class="mt-2 max-w-2xl text-[#737373]">
        Keep track of what you're playing, what you've finished,
        and what's waiting in your backlog.
    </p>

</div>

@if (session('success'))

<div class="mb-8 border border-[#D4D4D4] bg-white px-5 py-4 text-sm">
    {{ session('success') }}
</div>

@endif

{{-- Library Statistics --}}
<section>

    <h2 class="mb-4 text-lg font-semibold tracking-tight">
        Library Statistics
    </h2>

    <div class="grid grid-cols-2 gap-4 md:grid-cols-5">

        <div class="border border-[#E5E5E5] bg-white p-5">
            <p class="text-sm text-[#737373]">
                Total Games
            </p>

            <p class="mt-2 text-2xl font-semibold tracking-tight">
                {{ $totalGames }}
            </p>
        </div>

        <div class="border border-[#E5E5E5] bg-white p-5">
            <p class="text-sm text-[#737373]">
                Backlog
            </p>

            <p class="mt-2 text-2xl font-semibold tracking-tight">
                {{ $backlogGames }}
            </p>
        </div>

        <div class="border border-[#E5E5E5] bg-white p-5">
            <p class="text-sm text-[#737373]">
                Playing
            </p>

            <p class="mt-2 text-2xl font-semibold tracking-tight">
                {{ $playingGames }}
            </p>
        </div>

        <div class="border border-[#E5E5E5] bg-white p-5">
            <p class="text-sm text-[#737373]">
                Completed
            </p>

            <p class="mt-2 text-2xl font-semibold tracking-tight">
                {{ $completedGames }}
            </p>
        </div>

        <div class="border border-[#E5E5E5] bg-white p-5">
            <p class="text-sm text-[#737373]">
                Dropped
            </p>

            <p class="mt-2 text-2xl font-semibold tracking-tight">
                {{ $droppedGames }}
            </p>
        </div>

    </div>

</section>

{{-- Actions --}}
<div class="mt-8 flex items-center gap-3">

    <a href="{{ route('steam.games') }}" class="border border-[#171717] bg-[#171717] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#333333]">
        Search Steam
    </a>

    <form action="{{ route('steam.import') }}" method="POST">
        @csrf

        <button type="submit" class="border border-[#E5E5E5] bg-white px-4 py-2 text-sm font-medium text-[#737373] transition-colors hover:border-[#D4D4D4] hover:text-[#171717]">
            Import Steam Library
        </button>
    </form>

    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button type="submit" class="border border-[#E5E5E5] bg-white px-4 py-2 text-sm font-medium text-[#737373] transition-colors hover:border-[#D4D4D4] hover:text-[#171717]">
            Logout
        </button>
    </form>

</div>

@endsection
