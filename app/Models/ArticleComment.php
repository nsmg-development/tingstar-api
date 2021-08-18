<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'media_id', 'article_id', 'user_id', 'user_name', 'article_comment_id', 'comment', 'like', 'dislike', 'report'
    ];
}
