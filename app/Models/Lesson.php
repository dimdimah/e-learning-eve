<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order_position',
        'video_path',
        'video_original_name',
        'video_status',
    ];

    /**
     * Relasi ke course pemilik materi ini.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
