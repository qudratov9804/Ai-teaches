<x-teacher-layout :title="'Yangi mavzu — '.$subject->name">
    <a href="{{ route('teacher.subjects.show', ['subject' => $subject, 'tab' => 'topics']) }}"
       class="text-sm text-slate-500 hover:text-slate-700">&larr; {{ $subject->name }}</a>

    <h1 class="mt-2 text-2xl font-semibold text-slate-900">Yangi mavzu qo'shish</h1>

    <form method="POST" action="{{ route('teacher.topics.store', $subject) }}" class="mt-6 max-w-xl space-y-5">
        @csrf
        @include('teacher.topics._form', ['topic' => null])

        <button type="submit" class="rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-700">
            Saqlash
        </button>
    </form>
</x-teacher-layout>
