<x-teacher-layout :title="'Yangi material — '.$subject->name">
    <a href="{{ route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'materials']) }}"
       class="text-sm text-slate-500 hover:text-slate-700">&larr; {{ $subject->name }}</a>

    <h1 class="mt-2 text-2xl font-semibold text-slate-900">Yangi material yuklash</h1>

    <form method="POST" action="{{ route('teacher.materials.store', $subject) }}" enctype="multipart/form-data"
          class="mt-6 max-w-xl space-y-5">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium text-slate-700">Nomi</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}"
                   class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="author" class="block text-sm font-medium text-slate-700">Muallif</label>
            <input type="text" id="author" name="author" value="{{ old('author') }}"
                   class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            @error('author') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="type" class="block text-sm font-medium text-slate-700">Turi</label>
                <select id="type" name="type" class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
                    <option value="">Tanlang</option>
                    @foreach ($materialTypes as $value => $label)
                        <option value="{{ $value }}" @selected(old('type') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="published_year" class="block text-sm font-medium text-slate-700">Nashr yili</label>
                <input type="number" id="published_year" name="published_year" value="{{ old('published_year') }}" min="1900" max="{{ date('Y') + 1 }}"
                       class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
                @error('published_year') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="topic_id" class="block text-sm font-medium text-slate-700">Mavzu (ixtiyoriy)</label>
            <select id="topic_id" name="topic_id" class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
                <option value="">Umumiy (fan bo'yicha)</option>
                @foreach ($subject->topics as $topic)
                    <option value="{{ $topic->id }}" @selected((string) old('topic_id') === (string) $topic->id)>{{ $topic->name }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-400">Mavzu tanlansa, material aynan shu mavzuga bog'lanadi.</p>
            @error('topic_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Tavsif</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description') }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="file" class="block text-sm font-medium text-slate-700">Fayl</label>
            <input type="file" id="file" name="file"
                   class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm">
            <p class="mt-1 text-xs text-slate-400">PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX &middot; maksimal 50 MB</p>
            @error('file') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            Yuklash
        </button>
    </form>
</x-teacher-layout>
