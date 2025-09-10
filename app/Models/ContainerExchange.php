<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_container_id',
        'destination_container_id',
        'user_id',
        'exchange_date',
        'remarks',
    ];

    protected $casts = [
        'exchange_date' => 'datetime',
    ];

    public function sourceStock()
    {
        return $this->belongsTo(ContainerStock::class, 'source_container_stock_id');
    }

    public function destinationStock()
    {
        return $this->belongsTo(ContainerStock::class, 'destination_container_stock_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ContainerExchangePhoto::class);
    }
}