<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'free_time',
        'checkin_date',
        'departure_date',
        'status',
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

    /**
     * Calculate the expiration date based on ETA Date.
     *
     * @return \Carbon\Carbon|null
     */
    public function getExpirationDateAttribute()
    {
        // แก้ไข: เปลี่ยนจาก checkin_date เป็น eta_date
        if ($this->checkin_date && is_numeric($this->free_time)) {
            return $this->checkin_date->copy()->addDays($this->free_time);
        }
        return null;
    }

    /**
     * Calculate the remaining free time in days from today.
     *
     * @return int|string
     */
    public function getRemainingFreeTimeAttribute()
    {
        $expirationDate = $this->getExpirationDateAttribute();

        if ($expirationDate) {
            $today = Carbon::today();
            if ($expirationDate < $today) {
                return 'Expired';
            }
            return $today->diffInDays($expirationDate);
        }

        return 'N/A';
    }

    public static function generatePlanNumber()
    {
        $prefix = 'ORDER' . date('ymd');
        $lastPlan = self::where('plan_no', 'like', $prefix . '%')->latest('id')->first();

        if ($lastPlan) {
            $lastNumber = (int) substr($lastPlan->plan_no, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function pullingPlan()
    {
        return $this->hasOne(ContainerPullingPlan::class);
    }

}
