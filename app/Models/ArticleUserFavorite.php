<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleUserFavorite extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'media_id', 'user_id', 'article_id'
    ];
}
