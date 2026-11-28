<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = ['name', 'code', 'phone', 'email', 'address'];

    public function stockMutations(): HasMany
    {
        return $this->hasMany(StockMutation::class);
    }
}
