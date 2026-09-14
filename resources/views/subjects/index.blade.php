<x-layout title="Fanlar">
    @if ($mySubjects->isNotEmpty())
        <section class="mb-10">
            <h1 class="text-2xl font-semibold text-slate-900">Mening fanlarim</h1>
            <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($mySubjects as $subject)
                    <a href="{{ route('subjects.show', $subject) }}"
                       class="rounded-lg border border-slate-200 bg-white p-5 hover:border-slate-400">
                        <h3 class="font-medium text-slate-900">{{ $subject->name }}</h3>
                        <p class="mt-1 text-sm text-slate-500">{{ $subject->teacher?->name }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $subject->topics_count }} mavzu</p>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section>
        <h1 class="text-2xl font-semibold text-slate-900">Ochiq fanlar</h1>
        <p class="mt-1 text-slate-600">Login qilmasdan ko'rish mumkin bo'lgan fanlar.</p>

        <div class="mt-4">
            <livewire:subject-list />
        </div>
    </section>
</x-layout>
