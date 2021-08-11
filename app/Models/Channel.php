<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'media_id', 'platform', 'name', 'channel', 'state'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeActive($query, $platform)
    {
        return $query->where(['state' => true, 'platform' => $platform]);
    }
}
