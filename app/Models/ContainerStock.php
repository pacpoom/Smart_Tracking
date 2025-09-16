<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor;

class ContainerStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'container_order_plan_id',
        'yard_location_id',
        'vendor_id',
        'checkin_date',
        'status',
        'ship_out_date',
        'remark',
    ];

    protected $casts = [
        'checkin_date' => 'datetime',
        'ship_out_date' => 'datetime',
    ];

    public function Container()
    {
        return $this->belongsTo(Container::class, 'container_id', 'id');
    }

    public function yardLocation()
    {
        return $this->belongsTo(YardLocation::class);
    }

    public function containerOrderPlan()
    {
        return $this->belongsTo(ContainerOrderPlan::class);
    }

    /**
     * เพิ่มความสัมพันธ์นี้เข้าไป
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * คำนวณวันหมดอายุ (Expired Date) จาก Check-in Date + Free Time
     * โดยดึงข้อมูล free_time จาก containerOrderPlan ที่มีความสัมพันธ์กันอยู่
     */
    public function getExpiredDateAttribute()
    {
        if ($this->checkin_date && isset($this->containerOrderPlan->free_time)) {
            return $this->checkin_date->copy()->addDays($this->containerOrderPlan->free_time);
        }
        return null;
    }

    /**
     * คำนวณอายุ (Aging) เป็นจำนวนวันจาก Check-in Date จนถึงปัจจุบัน
     */
    public function getAgingDaysAttribute()
    {
        if ($this->checkin_date) {

            $checkinDate = $this->checkin_date->copy()->startOfDay();
            $today = now()->startOfDay();

            return $checkinDate->diffInDays($today);
        }
        return null;
    }
}
