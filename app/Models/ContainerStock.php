<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vendor; // เพิ่มบรรทัดนี้

class ContainerStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'container_id',
        'container_order_plan_id',
        'yard_location_id',
        'vendor_id', // ตรวจสอบว่ามีฟิลด์นี้
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
}
