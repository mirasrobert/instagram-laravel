<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'caption'
    ];

    // Reformat created-at date
    public function getCreatedAtAttribute()
    {
        $date = Carbon::parse($this->attributes['created_at'])->diffForHumans();
        return $date;
    }

    /**
     * Get the post's image.
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    // Post can like by many users
    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
