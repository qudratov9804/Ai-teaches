<x-layout title="Talaba kabineti">
    <h1 class="text-2xl font-semibold text-slate-900">Mening fanlarim</h1>
    <p class="mt-1 text-slate-600">Sizga biriktirilgan fanlar ro'yxati.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($subjects as $subject)
            <a href="{{ route('subjects.show', $subject) }}"
               class="rounded-lg border border-slate-200 bg-white p-5 hover:border-slate-400">
                <h2 class="font-medium text-slate-900">{{ $subject->name }}</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $subject->teacher?->name }}</p>
                <p class="mt-2 text-sm text-slate-500">{{ $subject->topics_count }} mavzu</p>
            </a>
        @empty
            <p class="text-slate-500">Sizga hali fan biriktirilmagan.</p>
        @endforelse
    </div>
</x-layout>
