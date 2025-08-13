<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_order_plan_id',
        'container_id',
        'yard_location_id',
        'status', // 1: Full, 2: Partial, 3: Empty
        'checkin_date',
        'eta_date',
        'remarks',
    ];

    protected $casts = [
        'checkin_date' => 'date',
        'eta_date' => 'date',
    ];

    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    public function yardLocation()
    {
        return $this->belongsTo(YardLocation::class);
    }
    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }
}
