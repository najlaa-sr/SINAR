<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['code', 'name', 'floor_or_building'];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function outgoingMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class, 'from_location_id');
    }

    public function incomingMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class, 'to_location_id');
    }
}
