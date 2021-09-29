<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleDetail extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'media_id', 'article_id', 'like', 'dislike', 'report'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
