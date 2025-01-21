<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gateway extends Model
{
    use HasFactory;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<\App\Models\Certificate, $this>
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'gateway_id');
    }
}
