# Arxitektura — 2-bosqich

Bu hujjat o'qituvchi kabineti va o'quv kontenti boshqaruvining yuqori darajadagi
arxitekturasini tasvirlaydi.

## Domen oqimi

```
Teacher
  │  (teacher_id)
  ▼
Subject ──────────────┬──────────────┬──────────────
  │ (subject_id)      │ (subject_id) │ (subject_id)
  ▼                    ▼              ▼
Topic               Curriculum     Material (topic_id NULL bo'lishi mumkin)
  │ (topic_id, nullable)
  └──────────────────────────────────► Material

                         │
                         ▼
              Future AI/RAG (3+ bosqich):
              text extraction → chunking → embedding
              → vector DB → retrieval → AI generation
```

- **Subject** — bitta o'qituvchiga tegishli (`teacher_id`). O'qituvchi fanning
  nomi, kodi, tavsifi, kurs/semestr/kredit, ochiq/yopiq va aktiv/noaktiv holatini
  boshqaradi.
- **Topic** — fanga tegishli mavzular ro'yxati, `position` bo'yicha saralanadi.
- **Curriculum** — fan uchun o'quv dasturi hujjatlari. Har bir yangi yuklash
  `version` raqamini oshiradi; eski versiyalar o'chirilmaydi (agar teacher
  ularni aniq o'chirmasa — bu holda ham SoftDeletes tufayli bazada qoladi).
- **Material** — adabiyotlar/materiallar. `subject_id` majburiy, `topic_id`
  ixtiyoriy (NULL bo'lsa — fan bo'yicha umumiy material, to'ldirilgan bo'lsa —
  aynan shu mavzuga tegishli). Kelajakdagi RAG tizimi materiallarni aynan shu
  bog'lanish orqali kontekstga oladi.

## Fayl yuklash oqimi

```
Teacher fayl yuklaydi (multipart/form-data)
        │
        ▼
Form Request validation (mime/extension/hajm — config/uploads.php)
        │
        ▼
FileUploadService::store()
        │  UUID nomi bilan storage/app/private/{curricula|materials}/{subject_id}/...
        ▼
Curriculum / Material yozuvi yaratiladi
  (file_path, original_name, mime_type, file_size, uploaded_by)
        │
        ▼
Policy-tekshiruvli download route orqaligina fayl qaytariladi
```

Hozircha fayl **ichidagi matn o'qilmaydi** — faqat xavfsiz saqlash va metadata.
Bu ataylab shunday: 3-bosqichda xuddi shu `file_path` orqali matn ajratib olish,
chunking, embedding va vector-qidiruv qo'shiladi, mavjud jadval strukturasi
o'zgarishsiz qoladi.

## Authorization qatlamlari

1. **Route middleware** — `role:teacher` faqat tegishli rolga ruxsat beradi.
2. **Policy** (`SubjectPolicy`, `TopicPolicy`, `CurriculumPolicy`, `MaterialPolicy`) —
   har bir modelning `view`/`create`/`update`/`delete` qoidalari; controller'lar
   `$this->authorize(...)` yoki Form Request'ning `authorize()` metodi orqali
   chaqiradi.
3. **Ota-bola moslik tekshiruvi** — masalan `subjects/{subject}/topics/{topic}`
   route'ida `topic->subject_id === subject->id` tekshiriladi (IDOR'ning oldini
   olish uchun), URL orqali boshqa fanning mavzusini tahrirlashga urinish 404
   qaytaradi.

Natijada: o'qituvchi faqat o'ziga tegishli fan/mavzu/dastur/material ustida
amal bajara oladi; boshqa o'qituvchining kontentiga URL orqali kirishga urinish
403 (yoki ota-bola mos kelmasa 404) bilan yakunlanadi.
