<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Orden extends Model
{
    protected $table = 'ordenes';

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'orden_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
