<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = ['name', 'birthplace', 'career', 'bio', 'profile_url'];

    public function artworks()
    {
        return $this->hasMany(ArtWork::class);
    }
}
