<x-teacher-layout :title="$subject->name">
    <div x-data="{ tab: '{{ in_array($tab, ['overview', 'topics', 'curriculum', 'materials']) ? $tab : 'overview' }}' }">
        <div class="mb-6">
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-2xl font-semibold text-slate-900">{{ $subject->name }}</h1>
                <span class="rounded-full px-2 py-0.5 text-xs
                    {{ $subject->is_open ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $subject->is_open ? 'Ochiq' : 'Yopiq' }}
                </span>
                <span class="rounded-full px-2 py-0.5 text-xs
                    {{ $subject->is_active ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}">
                    {{ $subject->is_active ? 'Aktiv' : 'Noaktiv' }}
                </span>
            </div>
            <p class="mt-1 text-sm text-slate-500">
                {{ $subject->code }}
                @if ($subject->course) &middot; {{ $subject->course }}-kurs @endif
                @if ($subject->semester) &middot; {{ $subject->semester }}-semestr @endif
                @if ($subject->credit) &middot; {{ $subject->credit }} kredit @endif
            </p>
            @if ($subject->description)
                <p class="mt-3 max-w-2xl text-slate-600">{{ $subject->description }}</p>
            @endif
        </div>

        <div class="border-b border-slate-200">
            <nav class="-mb-px flex gap-4 overflow-x-auto text-sm">
                <button type="button" @click="tab = 'overview'"
                        :class="tab === 'overview' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 font-medium">Umumiy</button>
                <button type="button" @click="tab = 'topics'"
                        :class="tab === 'topics' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 font-medium">Mavzular</button>
                <button type="button" @click="tab = 'curriculum'"
                        :class="tab === 'curriculum' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 font-medium">O'quv dasturi</button>
                <button type="button" @click="tab = 'materials'"
                        :class="tab === 'materials' ? 'border-slate-900 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700'"
                        class="whitespace-nowrap border-b-2 px-1 py-3 font-medium">Adabiyotlar</button>
            </nav>
        </div>

        <div class="py-6" x-show="tab === 'overview'">
            @include('teacher.subjects._overview')
        </div>

        <div class="py-6" x-show="tab === 'topics'" x-cloak>
            @include('teacher.subjects._topics')
        </div>

        <div class="py-6" x-show="tab === 'curriculum'" x-cloak>
            @include('teacher.subjects._curriculum')
        </div>

        <div class="py-6" x-show="tab === 'materials'" x-cloak>
            @include('teacher.subjects._materials')
        </div>
    </div>
</x-teacher-layout>
