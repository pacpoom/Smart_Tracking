<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ProductionPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plan_no',
        'vc_master_id',
        'production_order',
        'production_date',
        'details',
        'user_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'production_date' => 'date',
        'details' => 'array', // Automatically cast the 'details' JSON column to a PHP array
    ];

    /**
     * Get the vc_master associated with the production plan.
     */
    public function vcMaster()
    {
        return $this->belongsTo(VcMaster::class);
    }

    /**
     * Get the user who created the production plan.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique production plan number.
     *
     * @return string
     */
    public static function generatePlanNumber()
    {
        $prefix = 'PP' . date('ymd');
        $lastPlan = self::where('plan_no', 'like', $prefix . '%')->latest('id')->first();
        $newNumber = $lastPlan ? ((int) substr($lastPlan->plan_no, -4)) + 1 : 1;
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}