<x-layout :title="$subject->name">
    <div class="mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-semibold text-slate-900">{{ $subject->name }}</h1>
            <span class="rounded-full px-2 py-0.5 text-xs
                {{ $subject->is_open ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                {{ $subject->is_open ? 'Ochiq' : 'Yopiq' }}
            </span>
        </div>
        <p class="mt-1 text-sm text-slate-500">
            {{ $subject->code }} &middot; {{ $subject->teacher?->name ?? 'O\'qituvchi belgilanmagan' }}
        </p>
        @if ($subject->description)
            <p class="mt-3 max-w-2xl text-slate-600">{{ $subject->description }}</p>
        @endif
    </div>

    <h2 class="text-lg font-semibold text-slate-900">Mavzular</h2>
    <ol class="mt-3 space-y-2">
        @forelse ($subject->topics as $topic)
            <li class="rounded-lg border border-slate-200 bg-white p-4">
                <span class="text-sm text-slate-400">{{ $loop->iteration }}.</span>
                <span class="font-medium text-slate-900">{{ $topic->name }}</span>
                @if ($topic->description)
                    <p class="mt-1 text-sm text-slate-500">{{ $topic->description }}</p>
                @endif
            </li>
        @empty
            <p class="text-slate-500">Bu fan uchun hali mavzular qo'shilmagan.</p>
        @endforelse
    </ol>
</x-layout>
