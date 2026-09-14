@props(['title' => null])

<x-layout :title="$title">
    <div class="flex flex-col gap-6 lg:flex-row">
        <aside class="shrink-0 lg:w-56">
            <nav class="flex gap-2 overflow-x-auto lg:flex-col lg:overflow-visible">
                @php
                    $navItems = [
                        ['label' => 'Kabinet', 'route' => 'teacher.dashboard'],
                    ];
                @endphp
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="whitespace-nowrap rounded-md px-3 py-2 text-sm font-medium
                           {{ request()->routeIs($item['route'].'*') ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>
        </aside>

        <div class="min-w-0 flex-1">
            @if (session('success'))
                <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</x-layout>
