<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerTacking extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_type',
        'container_type',
        'transport_type',
        'container_order_plan_id',
        'shipment',
        'user_id',
    ];

    /**
     * Get the container order plan associated with the tacking.
     */
    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ContainerTackingPhoto::class);
    }
}
