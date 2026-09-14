# AI Ta'lim Platformasi

Universitet o'qituvchilari va talabalari uchun AI yordamida o'quv kontenti yaratadigan
web-platforma. Ushbu repository loyihaning **1- va 2-bosqich** natijasidir.

## 1. Loyiha haqida

Platforma o'qituvchiga fan bo'yicha o'quv dasturi va adabiyotlarni yuklash, talabalarni
guruh/fan bo'yicha boshqarish va kelajakda AI yordamida o'quv kontenti (ma'ruza,
taqdimot, test, amaliy topshiriq) yaratish imkonini beradi. Talabalar esa o'ziga
biriktirilgan fanlarni ko'rish, mavzularni o'rganish va (keyingi bosqichlarda) test hamda
topshiriqlarni bajarish imkoniyatiga ega bo'ladi.

- **1-bosqich:** arxitektura, rollar, ma'lumotlar bazasi va navigatsiya.
- **2-bosqich:** o'qituvchi kabineti — fan boshqaruvi, mavzular, o'quv dasturi va
  adabiyotlar (fayl yuklash). Tafsilotlar uchun [`docs/architecture.md`](docs/architecture.md)
  ga qarang.

AI generatsiyasi, matn ajratib olish (text extraction) va RAG keyingi bosqichlarda
qo'shiladi (pastdagi "Keyingi bosqichlar" bo'limiga qarang).

## 2. Texnologiyalar

| Qatlam | Texnologiya |
|---|---|
| Backend | Laravel 13, PHP 8.4 |
| Frontend | Blade, Livewire 3 (+ Alpine.js), Tailwind CSS 4 |
| Ma'lumotlar bazasi | SQLite (development) |
| Fayl saqlash | Laravel Filesystem (`storage/app/private`) |
| Autentifikatsiya | Laravel built-in auth (`Auth::attempt`) |

**Muhim:** Ma'lumotlar bazasi hozircha SQLite. Kelajakda PostgreSQL + pgvector'ga
o'tish rejalashtirilgan, shu sababli migratsiyalarda SQLite'ga xos (non-portable)
konstruksiyalardan foydalanilmagan.

## 3. O'rnatish

### Talablar

- PHP 8.3+ (`pdo_sqlite`, `sqlite3` extensionlari yoqilgan bo'lishi kerak)
- Composer
- Node.js 20+ va npm

### Qadamlar

```bash
composer install
cp .env.example .env
php artisan key:generate
npm install
```

### SQLite sozlash

`.env` faylida standart holatda SQLite ishlatiladi:

```
DB_CONNECTION=sqlite
```

SQLite fayli mavjud bo'lishi kerak (agar yo'q bo'lsa yarating):

```bash
touch database/database.sqlite   # Windows: type nul > database\database.sqlite
```

### Migration

```bash
php artisan migrate
```

### Seeder

Development uchun boshlang'ich ma'lumotlarni yuklash:

```bash
php artisan db:seed
```

Yoki bittada (bazani tozalab, qayta migratsiya qilib, seed qilish):

```bash
php artisan migrate:fresh --seed
```

### Frontend build

```bash
npm run build   # production uchun
npm run dev     # development uchun (Vite dev server)
```

### Serverni ishga tushirish

```bash
php artisan serve
```

Loyiha `http://127.0.0.1:8000` manzilida ochiladi.

## 4. Test accountlar

Seeder quyidagi accountlarni yaratadi (parol barchasida bir xil, faqat development uchun):

| Rol | Email | Parol |
|---|---|---|
| Admin | admin@ai-teacher.test | password |
| O'qituvchi | teacher@ai-teacher.test | password |
| O'qituvchi | teacher2@ai-teacher.test | password |
| Talaba | student@ai-teacher.test | password |
| Talaba | student2@ai-teacher.test | password |

> Bu — development/test uchun namunaviy parollar. Productionda haqiqiy, xavfsiz
> parollar va accountlar ishlatilishi shart.

Har bir o'qituvchiga bittadan fan biriktirilgan (`AI-101` va `DB-201`), har birida
3-4 ta mavzu, bittadan namunaviy o'quv dasturi hujjati va ikkitadan namunaviy material
bor. Namunaviy fayllar kichik matn fayllari — real PDF/DOCX emas, faqat yuklab olish
oqimini sinash uchun.

## 5. Avtomatik testlar

```bash
php artisan test
```

Testlar quyidagilarni tekshiradi:

- Landing/subjects sahifalari ochilishi, login qilmagan foydalanuvchi `/dashboard`dan
  `/login`ga yo'naltirilishi, admin panelga faqat admin kira olishi.
- O'qituvchi o'z fanini ko'ra va tahrirlay olishi, mavzu yarata/tahrirlay/o'chira olishi.
- O'quv dasturi va material fayllarini yuklash, yuklab olish, o'chirish; fayl turi va
  hajmi bo'yicha validation (ruxsat etilmagan kengaytma va 50 MB dan katta fayl rad
  etiladi).
- Materiallarni nom bo'yicha qidirish.
- Xavfsizlik: o'qituvchi boshqa o'qituvchining fani/mavzusi/materialini tahrirlay yoki
  o'chira olmasligi (403), talaba o'qituvchi funksiyalaridan foydalana olmasligi,
  talaba boshqa talabaning yopiq faniga kira olmasligi, mehmon (guest) teacher
  dashboardga kira olmasligi.

## 6. Project structure

```
app/
  Http/
    Controllers/
      Admin/DashboardController.php      # Admin kabineti (statistika)
      Teacher/
        DashboardController.php          # O'qituvchining kabineti (statistika + fanlar)
        SubjectController.php            # Fan ma'lumotlarini ko'rish/tahrirlash
        TopicController.php              # Mavzu CRUD
        CurriculumController.php         # O'quv dasturi hujjatlari (yuklash/o'chirish/yuklab olish)
        MaterialController.php           # Adabiyotlar/materiallar CRUD + yuklab olish
      Student/DashboardController.php    # Talabaning biriktirilgan fanlari
      Auth/LogoutController.php
      SubjectController.php              # Ochiq fanlar ro'yxati va (public) detali
      DashboardController.php            # Rol bo'yicha yo'naltiruvchi
    Requests/Teacher/                    # Form Request validationlari (o'zbek xabarlari bilan)
    Middleware/
      EnsureUserHasRole.php              # `role:admin`, `role:teacher` va h.k.
  Livewire/
    Auth/Login.php                       # Login formasi
    SubjectList.php                      # Qidiruvli ochiq fanlar ro'yxati
  Models/
    User.php, Role.php, Group.php, Subject.php, Topic.php, Curriculum.php, Material.php
  Policies/
    SubjectPolicy.php, TopicPolicy.php, CurriculumPolicy.php, MaterialPolicy.php
  Services/
    FileUploadService.php                # Xavfsiz fayl saqlash/o'chirish

config/
  uploads.php                            # Ruxsat etilgan fayl turlari, maksimal hajm

database/
  migrations/                            # roles, groups, subjects, topics, student_subject,
                                          # curriculums, materials
  factories/, seeders/DatabaseSeeder.php

resources/views/
  components/layout.blade.php            # Umumiy <x-layout>
  components/teacher-layout.blade.php    # O'qituvchi kabineti uchun sidebar + flash xabarlar
  welcome.blade.php                      # Landing page
  auth/login.blade.php
  admin|student/dashboard.blade.php
  teacher/
    dashboard.blade.php                  # Statistika + fan kartalar
    subjects/show.blade.php              # Tabli fan boshqaruv sahifasi (+ _overview/_topics/_curriculum/_materials)
    topics/create.blade.php, edit.blade.php
    materials/create.blade.php, show.blade.php
  subjects/index.blade.php, show.blade.php
  livewire/                              # Livewire komponent view'lari

routes/web.php
```

## 7. Ma'lumotlar bazasi sxemasi

```
roles (admin | teacher | student)
  └─< users (role_id, group_id)

groups
  └─< users                        (talabalar guruhga biriktiriladi)

users (teacher) ──< subjects       (teacher_id orqali — bitta fan bitta asosiy o'qituvchiga tegishli)

subjects ──< topics                (subject_id, tartib raqami = position)
subjects ──< curriculums           (subject_id, version — bir nechta versiya saqlanadi)
subjects ──< materials             (subject_id, topic_id — topic_id NULL bo'lishi mumkin)
topics   ──< materials             (topic_id — mavzuga bog'langan materiallar)

users (student) >──< subjects      (student_subject pivot jadvali orqali)
users ──< curriculums, materials   (uploaded_by — kim yuklaganini bildiradi)
```

- **Ochiq fan** (`subjects.is_open = true`) — login qilmagan foydalanuvchi ham ko'ra oladi
  (mavjud, o'zgarmagan xatti-harakat).
- **Yopiq fan** — faqat admin, fanning o'qituvchisi yoki unga biriktirilgan talaba ko'ra oladi
  (`SubjectPolicy@view`).
- **O'qituvchi boshqaruv sahifasi** (`/teacher/subjects/{subject}`) — bu jamoat (public)
  `/subjects/{subject}` sahifasidan alohida: faqat fanning egasi (yoki admin) kira oladi
  (`SubjectPolicy@update`), `is_open` holatidan qat'iy nazar.
- `curriculums` va `materials` jadvallari `SoftDeletes` ishlatadi — o'chirilgan yozuvlar
  audit/tarix uchun bazada qoladi, lekin oddiy so'rovlarda ko'rinmaydi.
- Rol va vakolatlar `App\Policies` va `role` middleware orqali boshqariladi:
  o'qituvchi faqat o'zining fanlarini (va ularga tegishli mavzu/dastur/material) tahrirlay
  oladi, boshqa o'qituvchining fani/kontentiga tega olmaydi; talaba faqat o'ziga
  biriktirilgan ma'lumotlarni ko'radi.

## 8. Fayl saqlash (file storage)

- Fayllar `storage/app/private` diskida (Laravel `local` disk) saqlanadi — `public/`
  papkasiga to'g'ridan-to'g'ri nazoratsiz joylashtirilmaydi.
- Har bir fayl tasodifiy (UUID) nom bilan saqlanadi (`curricula/{subject_id}/...`,
  `materials/{subject_id}/...`); asl fayl nomi, MIME turi va hajmi bazada saqlanadi.
- Yuklab olish faqat `teacher.curriculum.download` / `teacher.materials.download`
  route'lari orqali, tegishli Policy tekshiruvidan o'tgach amalga oshadi — fayl to'g'ridan-
  to'g'ri public URL orqali ochiq emas.
- Ruxsat etilgan formatlar va maksimal hajm `config/uploads.php` faylida:
  PDF, DOC, DOCX, PPT, PPTX, TXT, XLS, XLSX; maksimal **50 MB**.
- **Eslatma:** agar production serverda PHP'ning `upload_max_filesize` yoki
  `post_max_size` qiymati 50 MB dan kichik bo'lsa, `php.ini`da oshiring — aks holda
  katta fayllar PHP darajasida (Laravel validationgacha yetmasdan) rad etiladi.

## 9. Keyingi bosqichlar (2-bosqichda amalga oshirilmagan)

Quyidagilar keyingi bosqichlarda alohida ishlab chiqiladi:

- AI kontent generatsiyasi (Gemini API / OpenAI API orqali, alohida `AIService` abstraksiyasi bilan)
- Hujjatlarni yuklash va matn ajratib olish (text extraction)
- RAG arxitekturasi: chunking → embedding → vector storage → retrieval
- Test, savol-javob, taqdimot va amaliy topshiriq generatorlari
- Virtual laboratoriya va AI o'qituvchi yordamchisi
- Admin panelida to'liq CRUD (o'qituvchi/talaba/fan/guruh boshqaruvi)
- Ochiq fanlar uchun to'liq public katalog (hozir faqat `is_open` flag to'g'ri ishlatiladi)
- Bildirishnomalar va to'lov tizimi
