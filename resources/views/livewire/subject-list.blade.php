<div>
    <input
        type="text"
        wire:model.live.debounce.300ms="search"
        placeholder="Fan nomi bo'yicha qidirish..."
        class="w-full max-w-sm rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500"
    >

    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($subjects as $subject)
            <a href="{{ route('subjects.show', $subject) }}"
               class="rounded-lg border border-slate-200 bg-white p-5 hover:border-slate-400">
                <h3 class="font-medium text-slate-900">{{ $subject->name }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $subject->teacher?->name ?? 'Belgilanmagan' }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $subject->topics_count }} mavzu</p>
            </a>
        @empty
            <p class="text-slate-500">Hech qanday ochiq fan topilmadi.</p>
        @endforelse
    </div>
</div>
