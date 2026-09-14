<form method="POST" action="{{ route('teacher.subjects.update', $subject) }}" class="max-w-xl space-y-5">
    @csrf
    @method('PUT')

    <div>
        <label for="name" class="block text-sm font-medium text-slate-700">Fan nomi</label>
        <input type="text" id="name" name="name" value="{{ old('name', $subject->name) }}"
               class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="code" class="block text-sm font-medium text-slate-700">Fan kodi</label>
        <input type="text" id="code" name="code" value="{{ old('code', $subject->code) }}"
               class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
        @error('code') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-slate-700">Tavsif</label>
        <textarea id="description" name="description" rows="4"
                  class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description', $subject->description) }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div>
            <label for="course" class="block text-sm font-medium text-slate-700">Kurs</label>
            <input type="number" id="course" name="course" value="{{ old('course', $subject->course) }}" min="1" max="6"
                   class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            @error('course') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="semester" class="block text-sm font-medium text-slate-700">Semestr</label>
            <input type="number" id="semester" name="semester" value="{{ old('semester', $subject->semester) }}" min="1" max="12"
                   class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            @error('semester') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="credit" class="block text-sm font-medium text-slate-700">Kredit</label>
            <input type="number" id="credit" name="credit" value="{{ old('credit', $subject->credit) }}" min="1" max="30"
                   class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
            @error('credit') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:gap-8">
        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="is_open" value="0">
            <input type="checkbox" name="is_open" value="1" @checked(old('is_open', $subject->is_open))
                   class="rounded border-slate-300 text-slate-900 focus:ring-slate-500">
            Fan ochiq (login qilmagan foydalanuvchilarga ko'rinadi)
        </label>

        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $subject->is_active))
                   class="rounded border-slate-300 text-slate-900 focus:ring-slate-500">
            Fan aktiv
        </label>
    </div>

    <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
        Saqlash
    </button>
</form>
