<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Media extends Model
{
    use HasFactory;

    protected $connection = 'curator9';
    protected $table = 'medias';

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeByMediaIdx($query, $media_idx)
    {
        return $query->where('media_idx', $media_idx);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
