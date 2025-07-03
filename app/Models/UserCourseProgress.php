<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourseProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'last_lecture_id',
        'completed_lectures',
    ];

    protected $casts = [
        'completed_lectures' => 'array',
    ];
}
