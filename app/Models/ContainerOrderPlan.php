<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerOrderPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'model',
        'type',
        'house_bl',
        'eta_date',
        'free_time',
        'checkin_date',
        'is_active',
    ];

    protected $casts = [
        'eta_date' => 'date',
        'checkin_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the container associated with the order plan.
     */
    public function container()
    {
        return $this->belongsTo(Container::class);
    }
}
