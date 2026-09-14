<x-layout title="AI Ta'lim Platformasi">
    <div class="space-y-16">
        <section class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                AI yordamida zamonaviy ta'lim platformasi
            </h1>
            <p class="mx-auto mt-4 max-w-2xl text-slate-600">
                Universitet o'qituvchilari va talabalari uchun sun'iy intellekt yordamida
                o'quv kontenti yaratish va o'zlashtirishni osonlashtiruvchi platforma.
            </p>
            <div class="mt-8 flex items-center justify-center gap-4">
                @guest
                    <a href="{{ route('login') }}"
                       class="rounded-md bg-slate-900 px-5 py-2.5 text-white hover:bg-slate-700">
                        Tizimga kirish
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="rounded-md bg-slate-900 px-5 py-2.5 text-white hover:bg-slate-700">
                        Kabinetga o'tish
                    </a>
                @endguest
                <a href="{{ route('subjects.index') }}"
                   class="rounded-md border border-slate-300 px-5 py-2.5 text-slate-700 hover:bg-slate-100">
                    Ochiq fanlarni ko'rish
                </a>
            </div>
        </section>

        <section class="grid gap-6 sm:grid-cols-2">
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-slate-900">O'qituvchilar uchun</h2>
                <p class="mt-2 text-slate-600">
                    O'quv dasturingiz va adabiyotlaringizni yuklang — fan kontentingizni
                    AI yordamida tayyorlang.
                </p>
            </div>
            <div class="rounded-lg border border-slate-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-slate-900">Talabalar uchun</h2>
                <p class="mt-2 text-slate-600">
                    Fanlaringizni o'rganing, test va topshiriqlarni bajaring.
                </p>
            </div>
        </section>
    </div>
</x-layout>
