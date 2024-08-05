<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getDiscountPercentageAttribute()
    {
        if ($this->selling_price > 0 && $this->discount_price !== null && $this->discount_price < $this->selling_price) {
            $discountAmount = $this->selling_price - $this->discount_price;
            $percentage = ($discountAmount / $this->selling_price) * 100;
            return round($percentage);
        }
        return 0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function courseGoals()
    {
        return $this->hasMany(CourseGoal::class, 'course_id', 'id');
    }

    public function courseSections()
    {
        return $this->hasMany(CourseSection::class, 'course_id', 'id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }
}
