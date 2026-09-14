<?php

namespace Database\Seeders;

use App\Models\Curriculum;
use App\Models\Group;
use App\Models\Material;
use App\Models\Role;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with a minimal, working development dataset.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'Administrator', 'slug' => Role::ADMIN]);
        $teacherRole = Role::create(['name' => "O'qituvchi", 'slug' => Role::TEACHER]);
        $studentRole = Role::create(['name' => 'Talaba', 'slug' => Role::STUDENT]);

        $group1 = Group::create(['name' => 'Kompyuter lingvistikasi', 'code' => '301-guruh']);
        $group2 = Group::create(['name' => 'Dasturiy injiniring', 'code' => '302-guruh']);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@ai-teacher.test',
            'password' => 'password',
            'role_id' => $adminRole->id,
        ]);

        $teacher1 = User::create([
            'name' => 'Aziza Karimova',
            'email' => 'teacher@ai-teacher.test',
            'password' => 'password',
            'role_id' => $teacherRole->id,
        ]);

        $teacher2 = User::create([
            'name' => 'Bekzod Rashidov',
            'email' => 'teacher2@ai-teacher.test',
            'password' => 'password',
            'role_id' => $teacherRole->id,
        ]);

        $student1 = User::create([
            'name' => 'Javohir Tursunov',
            'email' => 'student@ai-teacher.test',
            'password' => 'password',
            'role_id' => $studentRole->id,
            'group_id' => $group1->id,
        ]);

        $student2 = User::create([
            'name' => 'Malika Otabekova',
            'email' => 'student2@ai-teacher.test',
            'password' => 'password',
            'role_id' => $studentRole->id,
            'group_id' => $group2->id,
        ]);

        $subject1 = Subject::create([
            'name' => "Sun'iy intellekt asoslari",
            'code' => 'AI-101',
            'description' => "Sun'iy intellekt va mashinali o'rganishning asosiy tushunchalari.",
            'teacher_id' => $teacher1->id,
            'semester' => 1,
            'course' => 3,
            'credit' => 5,
            'is_open' => true,
            'is_active' => true,
        ]);

        $subject2 = Subject::create([
            'name' => "Ma'lumotlar bazasini boshqarish tizimlari",
            'code' => 'DB-201',
            'description' => "Relyatsion ma'lumotlar bazalari va SQL asoslari.",
            'teacher_id' => $teacher2->id,
            'semester' => 3,
            'course' => 2,
            'credit' => 4,
            'is_open' => false,
            'is_active' => true,
        ]);

        $subject1->students()->attach($student1->id);
        $subject2->students()->attach($student2->id);

        $topics1 = collect([
            ["Kirish: sun'iy intellekt tarixi", 'AI sohasining rivojlanish bosqichlari.'],
            ['Mashinali o\'rganish asoslari', "Nazoratli va nazoratsiz o'rganish."],
            ['Neyron tarmoqlar', "Perseptron va ko'p qatlamli tarmoqlar."],
        ])->map(fn (array $topic, int $index) => $subject1->topics()->create([
            'position' => $index + 1,
            'name' => $topic[0],
            'description' => $topic[1],
            'is_active' => true,
        ]));

        $topics2 = collect([
            ['Kirish: ma\'lumotlar bazasi tushunchasi', 'MBBT nima va nima uchun kerak.'],
            ['SQL SELECT', "Ma'lumotlarni tanlab olish buyruqlari."],
            ['Normalizatsiya', '1NF, 2NF, 3NF shakllari.'],
            ['Tranzaksiyalar', 'ACID xususiyatlari.'],
        ])->map(fn (array $topic, int $index) => $subject2->topics()->create([
            'position' => $index + 1,
            'name' => $topic[0],
            'description' => $topic[1],
            'is_active' => true,
        ]));

        $this->seedCurriculumAndMaterials($subject1, $teacher1, $topics1);
        $this->seedCurriculumAndMaterials($subject2, $teacher2, $topics2);

        $this->command?->info('Test accountlar (parol: password):');
        $this->command?->info('  admin@ai-teacher.test, teacher@ai-teacher.test, teacher2@ai-teacher.test, student@ai-teacher.test, student2@ai-teacher.test');
    }

    /**
     * Seed a sample curriculum document and a couple of materials for the given subject,
     * writing small placeholder files to storage so download links work out of the box.
     *
     * @param  Collection<int, Topic>  $topics
     */
    private function seedCurriculumAndMaterials(Subject $subject, User $teacher, $topics): void
    {
        $disk = Storage::disk(config('uploads.disk'));

        $curriculumPath = "curricula/{$subject->id}/".fake()->uuid().'.txt';
        $disk->put($curriculumPath, "Ishchi o'quv dasturi (namuna) — {$subject->name}");

        Curriculum::create([
            'subject_id' => $subject->id,
            'title' => "Ishchi o'quv dasturi",
            'file_path' => $curriculumPath,
            'original_name' => 'ishchi-oquv-dasturi.txt',
            'mime_type' => 'text/plain',
            'file_size' => $disk->size($curriculumPath),
            'description' => "{$subject->name} fani uchun ishchi o'quv dasturi (namuna fayl).",
            'version' => 1,
            'is_active' => true,
            'uploaded_by' => $teacher->id,
        ]);

        $sampleMaterials = [
            ['title' => 'Asosiy darslik', 'type' => Material::TYPE_TEXTBOOK, 'topic' => null],
            ['title' => "Birinchi mavzu bo'yicha qo'shimcha maqola", 'type' => Material::TYPE_ARTICLE, 'topic' => $topics->first()],
        ];

        foreach ($sampleMaterials as $sample) {
            $path = "materials/{$subject->id}/".fake()->uuid().'.txt';
            $disk->put($path, "{$sample['title']} (namuna fayl) — {$subject->name}");

            Material::create([
                'subject_id' => $subject->id,
                'topic_id' => $sample['topic']?->id,
                'title' => $sample['title'],
                'author' => $teacher->name,
                'description' => 'Namuna material (seed orqali yaratilgan).',
                'type' => $sample['type'],
                'file_path' => $path,
                'original_name' => str($sample['title'])->slug().'.txt',
                'mime_type' => 'text/plain',
                'file_size' => $disk->size($path),
                'published_year' => now()->year,
                'is_active' => true,
                'uploaded_by' => $teacher->id,
            ]);
        }
    }
}
