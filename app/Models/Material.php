<?php

namespace App\Models;

use Database\Factories\MaterialFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'subject_id', 'topic_id', 'title', 'author', 'description', 'type',
    'file_path', 'original_name', 'mime_type', 'file_size',
    'published_year', 'is_active', 'uploaded_by',
])]
class Material extends Model
{
    /** @use HasFactory<MaterialFactory> */
    use HasFactory, SoftDeletes;

    public const TYPE_MAIN = 'asosiy';

    public const TYPE_ADDITIONAL = 'qoshimcha';

    public const TYPE_TEXTBOOK = 'darslik';

    public const TYPE_MANUAL = 'oquv_qollanma';

    public const TYPE_ARTICLE = 'ilmiy_maqola';

    public const TYPE_METHODICAL = 'metodik_qollanma';

    public const TYPE_OTHER = 'boshqa';

    /**
     * Human-readable (Uzbek) labels for each material type.
     *
     * @return array<string, string>
     */
    public static function types(): array
    {
        return [
            self::TYPE_MAIN => 'Asosiy adabiyot',
            self::TYPE_ADDITIONAL => "Qo'shimcha adabiyot",
            self::TYPE_TEXTBOOK => 'Darslik',
            self::TYPE_MANUAL => "O'quv qo'llanma",
            self::TYPE_ARTICLE => 'Ilmiy maqola',
            self::TYPE_METHODICAL => "Metodik qo'llanma",
            self::TYPE_OTHER => 'Boshqa material',
        ];
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'file_size' => 'integer',
            'published_year' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Subject, $this>
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * @return BelongsTo<Topic, $this>
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function typeLabel(): string
    {
        return self::types()[$this->type] ?? $this->type;
    }
}
