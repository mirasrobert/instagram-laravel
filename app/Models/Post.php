<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
      'profile_id',
      'caption'
    ];

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
}
