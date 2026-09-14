<h2 class="text-lg font-semibold text-slate-900">O'quv dasturi</h2>
<p class="mt-1 text-sm text-slate-500">
    Sillabus, ishchi o'quv dasturi va boshqa dasturiy hujjatlar. Yangi versiya yuklanganda eskilari saqlanib qoladi.
</p>

<form method="POST" action="{{ route('teacher.curriculum.store', $subject) }}" enctype="multipart/form-data"
      class="mt-4 max-w-xl space-y-4 rounded-lg border border-slate-200 bg-white p-4">
    @csrf

    <div>
        <label for="curriculum_title" class="block text-sm font-medium text-slate-700">Hujjat nomi</label>
        <input type="text" id="curriculum_title" name="title" value="{{ old('title') }}"
               placeholder="Masalan: Ishchi o'quv dasturi 2026"
               class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
        @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="curriculum_description" class="block text-sm font-medium text-slate-700">Tavsif (ixtiyoriy)</label>
        <textarea id="curriculum_description" name="description" rows="2"
                  class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="curriculum_file" class="block text-sm font-medium text-slate-700">Fayl</label>
        <input type="file" id="curriculum_file" name="file"
               class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm">
        <p class="mt-1 text-xs text-slate-400">PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX &middot; maksimal 50 MB</p>
        @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
        Yuklash
    </button>
</form>

<div class="mt-6 space-y-2">
    @forelse ($curriculums as $curriculum)
        <div class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-200 bg-white p-4">
            <div>
                <span class="font-medium text-slate-900">{{ $curriculum->title }}</span>
                <span class="ml-2 rounded-full bg-slate-100 px-2 py-0.5 text-xs text-slate-500">v{{ $curriculum->version }}</span>
                <p class="mt-1 text-xs text-slate-500">
                    {{ $curriculum->original_name }} &middot; {{ number_format($curriculum->file_size / 1024, 0) }} KB &middot;
                    {{ $curriculum->uploader?->name ?? "Noma'lum" }} &middot; {{ $curriculum->created_at->format('d.m.Y') }}
                </p>
                @if ($curriculum->description)
                    <p class="mt-1 text-sm text-slate-500">{{ $curriculum->description }}</p>
                @endif
            </div>
            <div class="flex shrink-0 items-center gap-3 text-sm">
                <a href="{{ route('teacher.curriculum.download', $curriculum) }}" class="text-slate-600 hover:text-slate-900">
                    Yuklab olish
                </a>
                <form method="POST" action="{{ route('teacher.curriculum.destroy', $curriculum) }}"
                      onsubmit="return confirm('Hujjatni o\'chirishni tasdiqlaysizmi?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">O'chirish</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-slate-500">Bu fan uchun hali o'quv dasturi hujjati yuklanmagan.</p>
    @endforelse
</div>
