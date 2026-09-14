<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 sm:px-6">
            <a href="{{ url('/') }}" class="text-lg font-semibold text-slate-900">
                {{ config('app.name') }}
            </a>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('subjects.index') }}" class="text-slate-600 hover:text-slate-900">Fanlar</a>

                @auth
                    <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900">Kabinet</a>
                    <span class="text-slate-400">|</span>
                    <span class="text-slate-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-slate-600 hover:text-slate-900">Chiqish</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="rounded-md bg-slate-900 px-3 py-1.5 text-white hover:bg-slate-700">
                        Kirish
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
