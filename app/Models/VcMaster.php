<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VcMaster extends Model
{
    use HasFactory;

    // ระบุชื่อตารางให้ตรงกับใน Database
    protected $table = 'vc_master';
}
