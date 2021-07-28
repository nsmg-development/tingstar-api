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
        'article_id', 'type', 'url', 'width', 'height'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}