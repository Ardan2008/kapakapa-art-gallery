<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleUser extends Model
{
    protected $fillable = ['google_id', 'name', 'email', 'avatar'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}