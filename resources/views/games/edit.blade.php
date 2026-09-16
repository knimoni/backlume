@extends('layouts.app')

@section('content')

{{-- Breadcrumb --}}
<div class="mb-6 text-sm text-[#737373]">

    <a href="{{ route('library') }}" class="hover:text-[#171717]">
        My Library
    </a>

    <span class="mx-2">/</span>

    <a href="{{ route('library.show', $userGame) }}" class="hover:text-[#171717]">
        {{ $userGame->game->name }}
    </a>

    <span class="mx-2">/</span>

    <span class="text-[#171717]">
        Edit
    </span>

</div>

{{-- Header --}}
<div class="mb-8">

    <p class="mb-2 text-sm font-medium text-[#737373]">
        Library management
    </p>

    <h1 class="text-3xl font-semibold tracking-tight">
        Edit Game
    </h1>

    <p class="mt-2 text-[#737373]">
        Update your progress, rating, review, and dates.
    </p>

</div>

@if ($errors->any())

<div class="mb-6 border border-[#E5E5E5] bg-white px-5 py-4">

    <p class="text-sm font-medium">
        Please check the following:
    </p>

    <ul class="mt-2 list-inside list-disc text-sm text-[#737373]">

        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach

    </ul>

</div>

@endif

@if (session('success'))

<div class="mb-6 border border-[#D4D4D4] bg-white px-5 py-4 text-sm">
    {{ session('success') }}
</div>

@endif

{{-- Game Summary --}}
<div class="mb-8 flex items-center gap-5 border border-[#E5E5E5] bg-white p-5">

    @if ($userGame->game->image_url)

    <img src="{{ $userGame->game->image_url }}" alt="{{ $userGame->game->name }}" class="h-16 w-28 shrink-0 bg-[#F5F5F5] object-contain">


    @endif

    <div>

        <h2 class=" font-medium">
            {{ $userGame->game->name }}
        </h2>

        <p class="mt-1 text-sm text-[#737373]">
            Steam App ID: {{ $userGame->game->steam_app_id }}
        </p>

        <p class="mt-1 text-sm text-[#737373]">
            Current playtime: {{ $userGame->playtime_minutes }} minutes
        </p>

    </div>

</div>

{{-- Form --}}
<form action="{{ route('library.update', $userGame) }}" method="POST">

    @csrf
    @method('PUT')

    <div class="space-y-8">

        {{-- Status --}}
        <section>

            <h2 class="mb-4 text-lg font-semibold tracking-tight">
                Status
            </h2>

            <select name="status" class="w-full border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none focus:border-[#A3A3A3]" required>

                <option value="backlog" @selected($userGame->status === 'backlog')>
                    Backlog
                </option>

                <option value="playing" @selected($userGame->status === 'playing')>
                    Playing
                </option>

                <option value="completed" @selected($userGame->status === 'completed')>
                    Completed
                </option>

                <option value="dropped" @selected($userGame->status === 'dropped')>
                    Dropped
                </option>

            </select>

        </section>

        {{-- Rating --}}
        <section>

            <h2 class="mb-4 text-lg font-semibold tracking-tight">
                Rating
            </h2>

            <input type="number" name="rating" min="1" max="10" value="{{ old('rating', $userGame->rating) }}" placeholder="1–10" class="w-full border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none focus:border-[#A3A3A3]">

        </section>

        {{-- Review --}}
        <section>

            <h2 class="mb-4 text-lg font-semibold tracking-tight">
                Review
            </h2>

            <textarea name="review" rows="6" placeholder="Write your thoughts about the game..." class="w-full resize-y border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none focus:border-[#A3A3A3]">{{ old('review', $userGame->review) }}</textarea>

        </section>

        {{-- Dates --}}
        <section>

            <h2 class="mb-4 text-lg font-semibold tracking-tight">
                Dates
            </h2>

            <div class="grid gap-5 md:grid-cols-2">

                <div>

                    <label class="mb-2 block text-sm text-[#737373]">
                        Started
                    </label>

                    <input type="date" name="started_at" value="{{ old('started_at', $userGame->started_at?->format('Y-m-d')) }}" class="w-full border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none focus:border-[#A3A3A3]">

                </div>

                <div>

                    <label class="mb-2 block text-sm text-[#737373]">
                        Completed
                    </label>

                    <input type="date" name="completed_at" value="{{ old('completed_at', $userGame->completed_at?->format('Y-m-d')) }}" class="w-full border border-[#E5E5E5] bg-white px-4 py-3 text-sm outline-none focus:border-[#A3A3A3]">

                </div>

            </div>

        </section>

    </div>

    {{-- Actions --}}
    <div class="mt-10 flex items-center gap-3 border-t border-[#E5E5E5] pt-6">

        <button type="submit" class="border border-[#171717] bg-[#171717] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[#333333]">
            Save Changes
        </button>

        <a href="{{ route('library.show', $userGame) }}" class="border border-[#E5E5E5] bg-white px-4 py-2 text-sm font-medium text-[#737373] transition-colors hover:border-[#D4D4D4] hover:text-[#171717]">
            Cancel
        </a>

    </div>

</form>

@endsection
