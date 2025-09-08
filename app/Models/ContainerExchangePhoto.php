<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerExchangePhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_exchange_id',
        'photo_type',
        'photo_path',
    ];

    public function containerExchange()
    {
        return $this->belongsTo(ContainerExchange::class);
    }
}