<?php

namespace App\Models;

use App\Observers\ProductObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'quantity', 'price',
    ];

    protected static function boot()
    {
        parent::boot();
        static::observe(ProductObserver::class);
    }
}
