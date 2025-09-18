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

    public $timestamps = false;
}
