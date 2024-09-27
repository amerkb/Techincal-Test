<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
         'total',
        'user_id'];

    public function Products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('id', 'qty');
    }
}
