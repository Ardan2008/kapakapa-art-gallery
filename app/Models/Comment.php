<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'artwork_id',
        'google_user_id',
        'name',
        'body',
        'sticker_url',
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function artwork()
    {
        return $this->belongsTo(ArtWork::class);
    }

    public function googleUser()
    {
        return $this->belongsTo(GoogleUser::class);
    }
}