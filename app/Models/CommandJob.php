<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'target_id', 'command', 'created_at', 'updated_at'
    ];
}
