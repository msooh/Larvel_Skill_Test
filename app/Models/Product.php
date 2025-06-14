<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'price'];
    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }
}
