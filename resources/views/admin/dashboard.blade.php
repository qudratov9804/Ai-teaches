<x-layout title="Admin kabineti">
    <h1 class="text-2xl font-semibold text-slate-900">Admin kabineti</h1>
    <p class="mt-1 text-slate-600">Platforma bo'yicha umumiy ko'rsatkichlar.</p>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">O'qituvchilar</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $teacherCount }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Talabalar</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $studentCount }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Fanlar</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $subjectCount }}</p>
        </div>
        <div class="rounded-lg border border-slate-200 bg-white p-5">
            <p class="text-sm text-slate-500">Guruhlar</p>
            <p class="mt-1 text-2xl font-semibold text-slate-900">{{ $groupCount }}</p>
        </div>
    </div>

    <p class="mt-8 text-sm text-slate-500">
        O'qituvchi, talaba, fan va guruhlarni boshqarish keyingi bosqichda qo'shiladi.
    </p>
</x-layout>
