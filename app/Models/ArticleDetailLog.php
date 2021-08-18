<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleDetailLog extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $fillable = [
        'article_id', 'user_id', 'type'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
