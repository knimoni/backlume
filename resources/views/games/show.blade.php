@extends('layouts.app')

@section('content')
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

$hours = intdiv($userGame->playtime_minutes, 60);
$minutes = $userGame->playtime_minutes % 60;

@endphp

{{-- Breadcrumb --}}
<div class="mb-8 text-sm text-[#737373]">

    <a href="{{ route('library') }}" class="transition-colors hover:text-[#171717]">
        My Library
    </a>

    <span class="mx-2">
        /
    </span>

    <span class="text-[#171717]">
        {{ $userGame->game->name }}
    </span>

</div>

{{-- Game Header --}}
<div class="grid gap-8 md:grid-cols-[360px_1fr]">

    {{-- Cover --}}
    <div>

        @if ($userGame->game->image_url)

        <img src="{{ $userGame->game->image_url }}" alt="{{ $userGame->game->name }}" class="w-full object-cover">

        @else

        <div class="flex aspect-[460/215] items-center justify-center bg-[#F5F5F5] text-sm text-[#A3A3A3]">
            No Image
        </div>

        @endif

    </div>

    {{-- Information --}}
    <div>

        <p class="text-sm text-[#737373]">
            Steam App ID: {{ $userGame->game->steam_app_id }}
        </p>

        <h1 class="mt-2 text-3xl font-semibold tracking-tight">
            {{ $userGame->game->name }}
        </h1>

        <div class="mt-4">

            <span class="border px-2 py-1 text-xs {{ $statusClasses }}">
                {{ ucfirst($userGame->status) }}
            </span>

        </div>

        {{-- Game Information --}}
        <div class="mt-8 border-y border-[#E5E5E5]">

            <div class="flex items-center justify-between border-b border-[#E5E5E5] py-4">

                <span class="text-sm text-[#737373]">
                    Playtime
                </span>

                <span class="text-sm font-medium">

                    @if ($hours > 0)

                    {{ $hours }}h

                    @if ($minutes > 0)
                    {{ $minutes }}m
                    @endif

                    @else

                    {{ $minutes }}m

                    @endif

                </span>

            </div>

            <div class="flex items-center justify-between border-b border-[#E5E5E5] py-4">

                <span class="text-sm text-[#737373]">
                    Rating
                </span>

                <span class="text-sm font-medium">
                    {{ $userGame->rating ? $userGame->rating . '/10' : 'Not rated' }}
                </span>

            </div>

            <div class="flex items-center justify-between border-b border-[#E5E5E5] py-4">

                <span class="text-sm text-[#737373]">
                    Started
                </span>

                <span class="text-sm font-medium">
                    {{ $userGame->started_at?->format('d M Y') ?? 'Not set' }}
                </span>

            </div>

            <div class="flex items-center justify-between py-4">

                <span class="text-sm text-[#737373]">
                    Completed
                </span>

                <span class="text-sm font-medium">
                    {{ $userGame->completed_at?->format('d M Y') ?? 'Not set' }}
                </span>

            </div>

        </div>

    </div>

</div>

{{-- Review --}}
@if ($userGame->review)

<section class="mt-10 border-t border-[#E5E5E5] pt-8">

    <p class="text-sm font-medium text-[#737373]">Your Review</p>

    <div class="mt-2 max-w-3xl border border-[#E5E5E5] bg-white px-5 py-4">
        <p class="whitespace-pre-line text-sm leading-6">{{ $userGame->review }}</p>
    </div>

</section>


@endif



{{-- Actions --}}
<div class="mt-10 flex flex-wrap items-center gap-3 border-t border-[#E5E5E5] pt-6">

    <a href="{{ route('library.edit', $userGame) }}" class="border border-[#171717] bg-[#171717] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#333333]">
        Edit Game
    </a>

    <form action="{{ route('library.destroy', $userGame) }}" method="POST" onsubmit="return confirm('Remove this game from your library?');">

        @csrf
        @method('DELETE')

        <button type="submit" class="border border-[#E5E5E5] bg-white px-4 py-2 text-sm font-medium text-[#737373] transition-colors hover:border-[#D4D4D4] hover:text-[#171717]">
            Remove from Library
        </button>

    </form>

</div>
@endsection
