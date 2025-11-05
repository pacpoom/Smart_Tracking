<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerPullingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pulling_plan_no',
        'plan_type', // เพิ่มบรรทัดนี้
        'container_order_plan_id',
        'pulling_date',
        'pulling_order', // เพิ่มบรรทัดนี้
        'destination',
        'shop',
        'status',
        'user_id',
        'remarks',
    ];

    protected $casts = [
        'pulling_date' => 'date',
    ];

    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generatePullingPlanNumber()
    {
        $prefix = 'PULL' . date('ymd');
        $lastPlan = self::where('pulling_plan_no', 'like', $prefix . '%')->latest('id')->first();
        $newNumber = $lastPlan ? ((int) substr($lastPlan->pulling_plan_no, -4)) + 1 : 1;
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
