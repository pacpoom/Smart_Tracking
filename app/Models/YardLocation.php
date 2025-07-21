<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YardLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_code',
        'location_type_id',
        'zone_id',
        'area_id',
        'bin_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // --- เพิ่ม Relationships เหล่านี้เข้ามา ---

    public function locationType()
    {
        return $this->belongsTo(YardCategory::class, 'location_type_id');
    }

    public function zone()
    {
        return $this->belongsTo(YardCategory::class, 'zone_id');
    }

    public function area()
    {
        return $this->belongsTo(YardCategory::class, 'area_id');
    }

    public function bin()
    {
        return $this->belongsTo(YardCategory::class, 'bin_id');
    }
}
