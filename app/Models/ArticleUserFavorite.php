<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ArticleUserFavorite extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'media_id', 'user_id', 'article_id'
    ];

    public function article(): HasOne
    {
        return $this->hasOne(Article::class, 'id', 'article_id');
    }
}
