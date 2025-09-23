<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContainerOrderPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_no',
        'container_id',
        'model',
        'type',
        'house_bl',
        'eta_date',
        'week_lot',
        'free_time',
        'checkin_date',
        'departure_date',
        'status',
        'depot'
    ];

    protected $casts = [
        'eta_date' => 'date',
        'checkin_date' => 'date',
        'departure_date' => 'date',
    ];

    protected $appends = ['expiration_date', 'remaining_free_time'];

    public function container()
    {
        return $this->belongsTo(Container::class);
    }

    public function containerStock()
    {
        return $this->hasOne(ContainerStock::class);
    }

    public function getExpirationDateAttribute()
    {
        if ($this->checkin_date && is_numeric($this->free_time)) {
            return $this->checkin_date->copy()->addDays($this->free_time);
        }
        return null;
    }

    public function getRemainingFreeTimeAttribute()
    {
        $expirationDate = $this->getExpirationDateAttribute();

        if ($expirationDate) {
            $today = Carbon::today();
            if ($expirationDate < $today) {
                return 'Expired';
            }

            $remainingDays = $today->diff($expirationDate)->days;

            // เงื่อนไขหลัก: ถ้าวันที่เหลือเกิน 33 วัน ให้เป็น Expired
            if ($remainingDays > 33) {
                return 'Expired';
            }

            // ถ้าไม่เข้าเงื่อนไขไหนเลย ให้คืนค่าเป็นตัวเลข
            return $remainingDays;
        }

        return 'N/A';
    }

    public function getAgeInDaysAttribute()
    {
        if ($this->checkin_date) {
            return $this->checkin_date->diff(Carbon::today())->days;
        }
        return 'N/A';
    }

    public static function generatePlanNumber()
    {
        $prefix = 'ORDER' . date('ymd');
        $lastPlan = self::where('plan_no', 'like', $prefix . '%')->latest('id')->first();
        $newNumber = $lastPlan ? ((int) substr($lastPlan->plan_no, -4)) + 1 : 1;
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function pullingPlan()
    {
        return $this->hasOne(ContainerPullingPlan::class);
    }
}
