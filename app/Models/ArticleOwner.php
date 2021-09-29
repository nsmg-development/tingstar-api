<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleOwner extends Model
{
    use HasFactory;

    protected $connection = 'curator9';
    protected $keyType = 'string';

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getUrlAttribute($value): string
    {
        return $value ?? '';
    }

    public function getThumbnailUrlAttribute($value): string
    {
        return $value ?? '';
    }

    public function getStorageThumbnailUrlAttribute($value): string
    {
        return $value ?? '';
    }

    public function getThumbnailWidthAttribute($value): int
    {
        return $value ?? 0;
    }

    public function getThumbnailHeightAttribute($value): int
    {
        return $value ?? 0;
    }
}
