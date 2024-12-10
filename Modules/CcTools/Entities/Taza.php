<?php

namespace Modules\CcTools\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Taza extends Model
{
    protected $table = 'tazas';
    protected $fillable = [
        'business_id',
        'currency_id',
        'value',
        'alias',
        // Agrega otros campos si es necesario
    ];

    public static function getTazas($business_id)
    {
        return self::where('business_id', $business_id)->get();
    }
}
