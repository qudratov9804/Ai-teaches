<div>
    <label for="name" class="block text-sm font-medium text-slate-700">Mavzu nomi</label>
    <input type="text" id="name" name="name" value="{{ old('name', $topic?->name) }}"
           class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="description" class="block text-sm font-medium text-slate-700">Tavsif</label>
    <textarea id="description" name="description" rows="4"
              class="mt-1 block w-full rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">{{ old('description', $topic?->description) }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div>
    <label for="position" class="block text-sm font-medium text-slate-700">Tartib raqami</label>
    <input type="number" id="position" name="position" value="{{ old('position', $topic?->position) }}" min="0"
           class="mt-1 block w-32 rounded-md border-slate-300 text-sm focus:border-slate-500 focus:ring-slate-500">
    <p class="mt-1 text-xs text-slate-400">Bo'sh qoldirilsa, avtomatik ravishda oxiriga qo'shiladi.</p>
    @error('position') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<label class="flex items-center gap-2 text-sm text-slate-700">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $topic?->is_active ?? true))
           class="rounded border-slate-300 text-slate-900 focus:ring-slate-500">
    Mavzu aktiv
</label>
