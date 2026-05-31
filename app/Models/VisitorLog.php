<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address', 'country_name', 'country_code', 'city', 'url', 'user_agent'
    ];
}