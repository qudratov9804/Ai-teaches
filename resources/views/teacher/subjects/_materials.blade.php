<div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold text-slate-900">Adabiyotlar va materiallar</h2>
    <a href="{{ route('teacher.materials.create', $subject) }}"
       class="rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-700">
        + Yangi material
    </a>
</div>

<form method="GET" action="{{ route('teacher.subjects.show', $subject) }}" class="mt-4 flex flex-wrap items-end gap-3">
    <input type="hidden" name="tab" value="materials">

    <div>
        <label for="search" class="block text-xs font-medium text-slate-500">Qidirish</label>
        <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nomi bo'yicha..."
               class="mt-1 rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
    </div>

    <div>
        <label for="type" class="block text-xs font-medium text-slate-500">Turi</label>
        <select id="type" name="type" class="mt-1 rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            <option value="">Barchasi</option>
            @foreach ($materialTypes as $value => $label)
                <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="topic_id" class="block text-xs font-medium text-slate-500">Mavzu</label>
        <select id="topic_id" name="topic_id" class="mt-1 rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            <option value="">Barchasi</option>
            @foreach ($subject->topics as $topic)
                <option value="{{ $topic->id }}" @selected((string) request('topic_id') === (string) $topic->id)>{{ $topic->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="sort" class="block text-xs font-medium text-slate-500">Saralash</label>
        <select id="sort" name="sort" class="mt-1 rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Yangi yuklangan</option>
            <option value="oldest" @selected(request('sort') === 'oldest')>Eski yuklangan</option>
            <option value="name" @selected(request('sort') === 'name')>Nomi bo'yicha</option>
        </select>
    </div>

    <button type="submit" class="rounded-md border border-slate-300 px-3 py-2 text-sm text-slate-700 hover:bg-slate-50">
        Filtrlash
    </button>
</form>

<div class="mt-4 space-y-2">
    @forelse ($materials as $material)
        <a href="{{ route('teacher.materials.show', $material) }}"
           class="block rounded-lg border border-slate-200 bg-white p-4 hover:border-slate-400">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <span class="font-medium text-slate-900">{{ $material->title }}</span>
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">{{ $material->typeLabel() }}</span>
            </div>
            <p class="mt-1 text-xs text-slate-500">
                @if ($material->author) {{ $material->author }} &middot; @endif
                {{ $material->original_name }} &middot; {{ number_format($material->file_size / 1024, 0) }} KB
                @if ($material->topic) &middot; {{ $material->topic->name }} @endif
                &middot; {{ $material->created_at->format('d.m.Y') }}
            </p>
        </a>
    @empty
        <p class="text-slate-500">Hech qanday material topilmadi.</p>
    @endforelse
</div>

<div class="mt-4">
    {{ $materials->links() }}
</div>
