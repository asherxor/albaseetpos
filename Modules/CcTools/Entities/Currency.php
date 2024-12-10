<?php
namespace Modules\CcTools\Entities;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'country', 
        'currency', 
        'code', 
        'symbol', 
        'thousand_separator', 
        'decimal_separator'
    ];

}