<x-teacher-layout title="O'qituvchi kabineti">
    <h1 class="text-2xl font-semibold text-slate-900">Mening fanlarim</h1>
    <p class="mt-1 text-slate-600">Sizga biriktirilgan fanlar va ularning statistikasi.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['label' => 'Fanlar soni', 'value' => $stats['subjects']],
            ['label' => 'Mavzular soni', 'value' => $stats['topics']],
            ['label' => "O'quv materiallari soni", 'value' => $stats['materials']],
            ['label' => 'Talabalar soni', 'value' => $stats['students']],
        ] as $stat)
            <div class="rounded-lg border border-slate-200 bg-white p-4">
                <p class="text-2xl font-semibold text-slate-900">{{ $stat['value'] }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    <h2 class="mt-8 text-lg font-semibold text-slate-900">Fanlar ro'yxati</h2>
    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($subjects as $subject)
            <div class="rounded-lg border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-medium text-slate-900">{{ $subject->name }}</h3>
                    <span class="text-xs text-slate-400">{{ $subject->code }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">
                    {{ $subject->course ? $subject->course.'-kurs' : '—' }} &middot;
                    {{ $subject->semester ? $subject->semester.'-semestr' : '—' }}
                </p>
                <dl class="mt-3 grid grid-cols-3 gap-2 text-center text-xs text-slate-500">
                    <div>
                        <dt class="font-semibold text-slate-900">{{ $subject->topics_count }}</dt>
                        <dd>mavzu</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-900">{{ $subject->materials_count }}</dt>
                        <dd>material</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-slate-900">{{ $subject->students_count }}</dt>
                        <dd>talaba</dd>
                    </div>
                </dl>
                <span class="mt-3 inline-block rounded-full px-2 py-0.5 text-xs
                    {{ $subject->is_open ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">
                    {{ $subject->is_open ? 'Ochiq' : 'Yopiq' }}
                </span>
                <a href="{{ route('teacher.subjects.show', $subject) }}"
                   class="mt-4 block rounded-md bg-slate-900 px-3 py-2 text-center text-sm font-medium text-white hover:bg-slate-700">
                    Fanga kirish
                </a>
            </div>
        @empty
            <p class="text-slate-500">Sizga hali fan biriktirilmagan.</p>
        @endforelse
    </div>
</x-teacher-layout>
