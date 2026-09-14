<div class="mx-auto max-w-md">
    <div class="rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
        <h1 class="mb-6 text-xl font-semibold text-slate-900">Tizimga kirish</h1>

        <form wire:submit="authenticate" class="space-y-4">
            <div>
                <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    autocomplete="username"
                    class="w-full rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Parol</label>
                <input
                    wire:model="password"
                    type="password"
                    id="password"
                    autocomplete="current-password"
                    class="w-full rounded-md border-slate-300 focus:border-slate-500 focus:ring-slate-500"
                >
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="w-full rounded-md bg-slate-900 px-4 py-2 text-white hover:bg-slate-700"
                wire:loading.attr="disabled"
            >
                Kirish
            </button>
        </form>
    </div>
</div>
