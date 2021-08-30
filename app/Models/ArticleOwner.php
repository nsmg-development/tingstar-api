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
}
