<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_INSTRUCTOR = 'instructor';
    public const ROLE_USER = 'user';

    public const STATUS_ACTIVE = '1';
    public const STATUS_INACTIVE = '0';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'last_seen' => 'datetime',
        ];
    }

    public function wishlistCourses()
    {
        return $this->belongsToMany(Course::class, 'wishlists', 'user_id', 'course_id')->withTimestamps();
    }

    public function getLastSeenStatusAttribute(): string
    {
        if (!$this->last_seen) {
            return 'Never'; // 
        }

        $onlineThreshold = Carbon::now()->subMinutes(1);

        if ($this->last_seen->gt($onlineThreshold)) {
            return 'Online';
        }

        return $this->last_seen->diffForHumans();
    }
}
