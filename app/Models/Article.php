<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $primaryKey = 'id';

    protected $fillable = [
        'media_id', 'platform', 'type', 'keyword', 'channel', 'url', 'title', 'contents',
        'thumbnail_url', 'storage_thumbnail_url', 'thumbnail_width', 'thumbnail_height', 'hashtag', 'state', 'date'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function scopeActive($query, bool $isAdmin = false)
    {
        if (!$isAdmin) {
            return $query->where('state', 1);
        }
    }

    public function articleOwner(): HasOne
    {
        return $this->hasOne(ArticleOwner::class, 'id', 'article_owner_id');
    }

    public function articleMedias(): HasMany
    {
        return $this->hasMany(ArticleMedia::class, 'article_id', 'id');
    }

    public function articleComments(): HasMany
    {
        return $this->hasMany(ArticleComment::class, 'article_id', 'id')->orderBy('id', 'DESC');
    }

    public function articleDetail(): HasOne
    {
        return $this->hasOne(ArticleDetail::class, 'article_id', 'id');
    }
}
