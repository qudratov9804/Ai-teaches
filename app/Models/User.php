<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'group_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @return BelongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The group this user (as a student) belongs to.
     *
     * @return BelongsTo<Group, $this>
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Subjects this user teaches (role: teacher).
     *
     * @return HasMany<Subject, $this>
     */
    public function subjectsTeaching(): HasMany
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }

    /**
     * Subjects this user is enrolled in (role: student).
     *
     * @return BelongsToMany<Subject, $this>
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'student_subject')->withTimestamps();
    }

    /**
     * Materials this user has uploaded.
     *
     * @return HasMany<Material, $this>
     */
    public function uploadedMaterials(): HasMany
    {
        return $this->hasMany(Material::class, 'uploaded_by');
    }

    /**
     * Curriculum documents this user has uploaded.
     *
     * @return HasMany<Curriculum, $this>
     */
    public function uploadedCurriculums(): HasMany
    {
        return $this->hasMany(Curriculum::class, 'uploaded_by');
    }

    public function isAdmin(): bool
    {
        return $this->role?->slug === Role::ADMIN;
    }

    public function isTeacher(): bool
    {
        return $this->role?->slug === Role::TEACHER;
    }

    public function isStudent(): bool
    {
        return $this->role?->slug === Role::STUDENT;
    }
}
