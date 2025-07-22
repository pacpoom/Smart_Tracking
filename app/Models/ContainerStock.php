<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_order_plan_id',
        'yard_location_id',
        'checkin_date',
        'remarks',
    ];

    protected $casts = [
        'checkin_date' => 'date',
    ];

    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    public function yardLocation()
    {
        return $this->belongsTo(YardLocation::class);
    }
}
