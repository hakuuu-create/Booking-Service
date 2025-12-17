<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SparePart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'name',
        'price_buy',
        'price_sell',
        'stock',
        'unit',
        'location'
    ];
}