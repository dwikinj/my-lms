<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function courseGoals()
    {
        return $this->hasMany(CourseGoal::class, 'course_id', 'id');
    }

    public function courseSections() {
        return $this->hasMany(CourseSection::class,'course_id','id');
    }
}
