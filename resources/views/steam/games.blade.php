@extends('layouts.app')

@section('content')

{{-- Header --}}
<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#737373]">
        Steam catalog
    </p>

    <h1 class="text-3xl font-semibold tracking-tight">
        Search Steam
    </h1>

    <p class="mt-2 max-w-2xl text-[#737373]">
        Search for games on Steam and add them to your Backlume library.

    </p>

</div>

{{-- Search --}}
<form action="{{ route('steam.games') }}" method="GET" class="mb-8 flex w-full max-w-2xl gap-2">

    <input type="text" name="term" value="{{ $term }}" placeholder="Search for a game..." class="w-full border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none transition-colors focus:border-[#A3A3A3]">

    <button type="submit" class="shrink-0 border border-[#171717] bg-[#171717] px-5 py-3 text-sm font-medium text-white transition-colors hover:bg-[#333333]">
        Search
    </button>

</form>

@if ($term !== '')

{{-- Search Results --}}
@if (empty($results))

<div class="border border-[#E5E5E5] bg-white px-6 py-12 text-center">

    <h2 class="text-lg font-semibold">
        No games found
    </h2>

    <p class="mt-2 text-sm text-[#737373]">
        Try searching with a different title.
    </p>

</div>

@else

<div class="mb-4 text-sm text-[#737373]">
    Search results
</div>

<div class="divide-y divide-[#E5E5E5] border-y border-[#E5E5E5] bg-white">

    @foreach ($results as $game)

    <div class="flex flex-col gap-4 px-5 py-4 sm:flex-row sm:items-center">

        {{-- Image --}}
        @if ($game['tiny_image'])

        <img src="{{ $game['tiny_image'] }}" alt="{{ $game['name'] }}" class="h-16 w-28 shrink-0 bg-[#F5F5F5] object-contain">

        @else

        <div class="flex h-16 w-28 shrink-0 items-center justify-center bg-[#F5F5F5] text-xs text-[#A3A3A3]">
            No Image
        </div>

        @endif

        {{-- Game Information --}}
        <div class="min-w-0 flex-1">

            <h2 class="truncate font-medium">
                {{ $game['name'] }}
            </h2>

            <p class="mt-1 text-xs text-[#A3A3A3]">
                App ID {{ $game['id'] }}
            </p>

        </div>

        {{-- Action --}}
        <div class="shrink-0">

            @if ($game['in_library'])

            <span class="inline-block border border-[#E5E5E5] bg-[#F7F7F5] px-3 py-2 text-xs text-[#737373]">
                Already in Library
            </span>

            @else

            <form action="{{ route('steam.add', $game['id']) }}" method="POST">

                @csrf

                <button type="submit" class="w-full border border-[#171717] bg-[#171717] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#333333] sm:w-auto">
                    Add to Library
                </button>

            </form>

            @endif

        </div>

    </div>

    @endforeach

</div>

@endif

@else

{{-- Initial State --}}
<div class="border border-[#E5E5E5] bg-white px-6 py-12 text-center">

    <h2 class="text-lg font-semibold">
        Search the Steam catalog
    </h2>

    <p class="mt-2 text-sm text-[#737373]">
        Enter a game title above to find it on Steam.
    </p>

</div>

@endif

@endsection
