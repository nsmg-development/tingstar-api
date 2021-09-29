<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleMedia extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $table = 'article_medias';

    protected $guarded = [];

    protected $fillable = [
        'article_id', 'type', 'storage_url', 'url', 'width', 'height'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function getStorageUrlAttribute($value): string
    {
        return $value ?? '';
    }

    public function getUrlAttribute($value): string
    {
        return $value ?? '';
    }

    public function getWidthAttribute($value): int
    {
        return $value ?? 0;
    }

    public function getHeightAttribute($value): int
    {
        return $value ?? 0;
    }

    public function getMimeAttribute($value): string
    {
        return $value ?? '';
    }
}
