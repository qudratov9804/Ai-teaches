<div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold text-slate-900">Mavzular</h2>
    <a href="{{ route('teacher.topics.create', $subject) }}"
       class="rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-700">
        + Yangi mavzu
    </a>
</div>

<div class="mt-4 space-y-2">
    @forelse ($subject->topics as $topic)
        <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-white p-4">
            <div>
                <span class="text-sm text-slate-400">#{{ $topic->position }}</span>
                <span class="font-medium text-slate-900">{{ $topic->name }}</span>
                @unless ($topic->is_active)
                    <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">Noaktiv</span>
                @endunless
                @if ($topic->description)
                    <p class="mt-1 text-sm text-slate-500">{{ $topic->description }}</p>
                @endif
            </div>
            <div class="flex shrink-0 items-center gap-3 text-sm">
                <a href="{{ route('teacher.topics.edit', [$subject, $topic]) }}" class="text-slate-600 hover:text-slate-900">
                    Tahrirlash
                </a>
                <form method="POST" action="{{ route('teacher.topics.destroy', [$subject, $topic]) }}"
                      onsubmit="return confirm('Mavzuni o\'chirishni tasdiqlaysizmi?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">O'chirish</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-slate-500">Bu fan uchun hali mavzular qo'shilmagan.</p>
    @endforelse
</div>
