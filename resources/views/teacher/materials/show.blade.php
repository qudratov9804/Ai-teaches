<x-teacher-layout :title="$material->title">
    <a href="{{ route('teacher.subjects.show', ['subject' => $material->subject, 'tab' => 'materials']) }}"
       class="text-sm text-slate-500 hover:text-slate-700">&larr; {{ $material->subject->name }}</a>

    <div class="mt-2 flex flex-wrap items-center gap-3">
        <h1 class="text-2xl font-semibold text-slate-900">{{ $material->title }}</h1>
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">{{ $material->typeLabel() }}</span>
        @unless ($material->is_active)
            <span class="rounded-full bg-red-100 px-2 py-0.5 text-xs text-red-700">Noaktiv</span>
        @endunless
    </div>

    <dl class="mt-6 max-w-xl divide-y divide-slate-200 rounded-lg border border-slate-200 bg-white">
        @foreach ([
            'Muallif' => $material->author ?? '—',
            'Turi' => $material->typeLabel(),
            'Fan' => $material->subject->name,
            'Mavzu' => $material->topic?->name ?? 'Umumiy (fan bo\'yicha)',
            'Fayl nomi' => $material->original_name,
            'Fayl hajmi' => number_format($material->file_size / 1024, 0).' KB',
            'Nashr yili' => $material->published_year ?? '—',
            'Yuklangan sana' => $material->created_at->format('d.m.Y H:i'),
            'Yuklagan foydalanuvchi' => $material->uploader?->name ?? "Noma'lum",
            'Holati' => $material->is_active ? 'Aktiv' : 'Noaktiv',
        ] as $label => $value)
            <div class="flex justify-between gap-4 px-4 py-3 text-sm">
                <dt class="text-slate-500">{{ $label }}</dt>
                <dd class="text-right font-medium text-slate-900">{{ $value }}</dd>
            </div>
        @endforeach
        @if ($material->description)
            <div class="px-4 py-3 text-sm">
                <dt class="text-slate-500">Tavsif</dt>
                <dd class="mt-1 text-slate-900">{{ $material->description }}</dd>
            </div>
        @endif
    </dl>

    <div class="mt-6 flex items-center gap-3">
        <a href="{{ route('teacher.materials.download', $material) }}"
           class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            Yuklab olish
        </a>
        <form method="POST" action="{{ route('teacher.materials.destroy', $material) }}"
              onsubmit="return confirm('Materialni o\'chirishni tasdiqlaysizmi?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="rounded-md border border-red-300 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-50">
                O'chirish
            </button>
        </form>
    </div>
</x-teacher-layout>
