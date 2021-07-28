<?php

namespace App\Models\Oauth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderContent extends Model
{
    use HasFactory;

    public function oauthClients(): hasMany
    {
        return $this->hasMany(OauthClient::class, 'provider_contents_id', 'id');
    }
}
