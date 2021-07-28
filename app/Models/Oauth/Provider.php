<?php

namespace App\Models\Oauth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provider extends Model
{
    use HasFactory;

    public function providerContents(): hasMany
    {
        return $this->hasMany(ProviderContent::class, 'provider_id', 'id');
    }
}
