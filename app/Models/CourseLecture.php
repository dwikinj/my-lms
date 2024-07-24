<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLecture extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class, 'course_section_id', 'id');
    }
}
