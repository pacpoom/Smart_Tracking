<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    /**
     * 
     * 
     */
    protected $table = 'material';

    /**
     * 
     * (เผื่อสำหรับการสร้าง/แก้ไขข้อมูล Material ในอนาคต)
     */
    protected $fillable = [
        'material_number',
        'material_name',
        'unit',
    ];

    /**
     * ไม่มีการใช้ timestamps (created_at, updated_at) ในตารางนี้
     * (หากตาราง materials ของคุณไม่มี 2 คอลัมน์นี้)
     * public $timestamps = false; 
     */
}
