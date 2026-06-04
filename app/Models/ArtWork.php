<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtWork extends Model
{
    protected $fillable = [
        'artist_id',
        'title',
        'artist',
        'category',
        'price',
        'collector_name',
        'sold_at',
        'image_url',
        'birthplace',
        'career',
        'artist_desc',
        'art_desc',
        'painter_ref',
        'base_price',
        'sale_price',
        'images',
        'certificate_url',
        'width',
        'height',
        'unit',
        'collector_name',
        'collector_country',
        'collector_country_code',
    ];

    protected $table = 'art_works';

    protected $casts = [
        'sold_at' => 'datetime',
        'images' => 'array',
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function comments() 
    { 
        return $this->hasMany(Comment::class, 'artwork_id'); 
    }
}
