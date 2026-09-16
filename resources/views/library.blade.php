@extends('layouts.app')
@section('content')
{{-- Header --}}
<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#737373]">
        Your collection
    </p>

    <h1 class="text-3xl font-semibold tracking-tight">
        My Library
    </h1>

    <p class="mt-2 max-w-2xl text-[#737373]">
        Browse and manage the games in your personal library.
    </p>

</div>

@if (session('success'))

<div class="mb-6 border border-[#D4D4D4] bg-white px-5 py-4 text-sm">
    {{ session('success') }}
</div>

@endif

{{-- Search and Filter --}}
<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

    <form action="{{ route('library') }}" method="GET" class="flex w-full max-w-md gap-2">

        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search your library..." class="w-full border border-[#E5E5E5] bg-white px-4 py-2 text-sm outline-none transition-colors focus:border-[#A3A3A3]">

        @if (request('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <button type="submit" class="border border-[#171717] bg-[#171717] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#333333]">
            Search
        </button>

    </form>

    <div class="flex flex-wrap items-center gap-2 text-sm">

        <a href="{{ route('library') }}" class="border px-3 py-2
                {{ !request('status')
                    ? 'border-[#171717] bg-[#171717] text-white'
                    : 'border-[#E5E5E5] bg-white text-[#737373] hover:text-[#171717]' }}">
            All
        </a>

        <a href="{{ route('library', ['status' => 'backlog']) }}" class="border px-3 py-2
                {{ request('status') === 'backlog'
                    ? 'border-[#171717] bg-[#171717] text-white'
                    : 'border-[#E5E5E5] bg-white text-[#737373] hover:text-[#171717]' }}">
            Backlog
        </a>

        <a href="{{ route('library', ['status' => 'playing']) }}" class="border px-3 py-2
                {{ request('status') === 'playing'
                    ? 'border-[#171717] bg-[#171717] text-white'
                    : 'border-[#E5E5E5] bg-white text-[#737373] hover:text-[#171717]' }}">
            Playing
        </a>

        <a href="{{ route('library', ['status' => 'completed']) }}" class="border px-3 py-2
                {{ request('status') === 'completed'
                    ? 'border-[#171717] bg-[#171717] text-white'
                    : 'border-[#E5E5E5] bg-white text-[#737373] text-[#737373] hover:text-[#171717]' }}">
            Completed
        </a>

        <a href="{{ route('library', ['status' => 'dropped']) }}" class="border px-3 py-2
                {{ request('status') === 'dropped'
                    ? 'border-[#171717] bg-[#171717] text-white'
                    : 'border-[#E5E5E5] bg-white text-[#737373] hover:text-[#171717]' }}">
            Dropped
        </a>

    </div>

</div>

{{-- Count --}}
<div class="mb-4 text-sm text-[#737373]">
    {{ $games->count() }} game{{ $games->count() !== 1 ? 's' : '' }}
</div>

{{-- Games --}}
@if ($games->isEmpty())

<div class="border border-[#E5E5E5] bg-white px-6 py-12 text-center">

    <h2 class="text-lg font-semibold">
        No games found
    </h2>

    <p class="mt-2 text-sm text-[#737373]">
        Try changing your search or filter.
    </p>

</div>

@else

<div class="divide-y divide-[#E5E5E5] border-y border-[#E5E5E5] bg-white">

    @foreach ($games as $userGame)

    @php

    $statusClasses = match ($userGame->status) {

    'backlog' =>
    'border-[#E5E5E5] bg-[#F7F7F5] text-[#737373]',

    'playing' =>
    'border-[#C7D2FE] bg-[#EEF2FF] text-[#3730A3]',

    'completed' =>
    'border-[#BFDBFE] bg-[#EFF6FF] text-[#1D4ED8]',

    'dropped' =>
    'border-[#E5E5E5] bg-[#F5F5F5] text-[#737373]',

    default =>
    'border-[#E5E5E5] bg-[#F7F7F5] text-[#737373]',
    };

    $statusLabel = ucfirst($userGame->status);

    $hours = intdiv($userGame->playtime_minutes, 60);
    $minutes = $userGame->playtime_minutes % 60;

    @endphp

    <a href="{{ route('library.show', $userGame) }}" class="group flex items-center gap-5 px-5 py-4 transition-colors hover:bg-[#FAFAFA]">

        @if ($userGame->game->image_url)

        <img src="{{ $userGame->game->image_url }}" alt="{{ $userGame->game->name }}" class="h-16 w-28 shrink-0 bg-[#F5F5F5] object-contain">


        @else

        <div class="flex h-20 w-36 shrink-0 items-center justify-center bg-[#F5F5F5] text-xs text-[#A3A3A3]">
            No Image
        </div>

        @endif

        <div class="min-w-0 flex-1">

            <h2 class="truncate font-medium">
                {{ $userGame->game->name }}
            </h2>

            <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-[#737373]">

                <span>
                    @if ($hours > 0)
                    {{ $hours }}h
                    @if ($minutes > 0)
                    {{ $minutes }}m
                    @endif
                    @else
                    {{ $minutes }}m
                    @endif
                </span>

                <span class="border px-2 py-1 text-xs {{ $statusClasses }}">
                    {{ $statusLabel }}
                </span>

                @if ($userGame->rating)

                <span>
                    {{ $userGame->rating }}/10
                </span>

                @endif

            </div>

        </div>

        <span class="shrink-0 text-xl text-[#A3A3A3] transition-transform group-hover:translate-x-0.5">
            →
        </span>

    </a>

    @endforeach

</div>

@endif
@endsection
