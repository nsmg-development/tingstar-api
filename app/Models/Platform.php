<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    use HasFactory;

    protected $connection = 'curator9';

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function platformAccounts(bool $value = true): hasMany
    {
        if ($value) {
            return $this->hasMany(PlatformAccount::class)
                ->where('platform_accounts.state', '=', $value);
        }

        return $this->hasMany(PlatformAccount::class);
    }
}
