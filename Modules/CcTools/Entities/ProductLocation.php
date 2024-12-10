<?php

namespace Modules\CcTools\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductLocation extends Model
{
    // Especificar la tabla asociada al modelo
    protected $table = 'product_locations';

    // Si no usas timestamps en esta tabla, añade esta línea
    public $timestamps = false;

    // Especificar los campos que pueden ser llenados masivamente
    protected $fillable = [
        'product_id',
        'location_id',
        // otros campos si es necesario
    ];
}
