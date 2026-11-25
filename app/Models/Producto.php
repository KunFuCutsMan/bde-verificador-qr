<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'SKU',
        'Proveedor',
        'cantidad',
    ];

    public function ordenes(): BelongsToMany
    {
        return $this->belongsToMany(Orden::class, 'orden_producto')
            ->withPivot('cantidad')
            ->withTimestamps();
    }
}
