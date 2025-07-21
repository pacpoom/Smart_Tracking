<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_code',
        'name',
        'address',
        'contact_person',
        'email',
        'phone',
        'attachment_path',
        'is_active',
        'register_date', // เพิ่มบรรทัดนี้
        'expire_date',   // เพิ่มบรรทัดนี้
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'register_date' => 'date', // เพิ่มบรรทัดนี้
        'expire_date' => 'date',   // เพิ่มบรรทัดนี้
    ];
}
