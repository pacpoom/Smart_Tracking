<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_order_plan_id',
        'user_id',
        'yard_location_id',
        'activity_type',
        'transaction_date',
        'remarks',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function yardLocation()
    {
        return $this->belongsTo(YardLocation::class);
    }
}
