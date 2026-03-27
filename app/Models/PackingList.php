<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Container;
use App\Models\Material; 
use Illuminate\Http\Request;

class PackingList extends Model
{
    use HasFactory;

    // กำหนดให้ใช้ Connection ที่ 2
    protected $connection = 'mysql_second';
    
    protected $table = 'packing_list';

    // กำหนด Fillable หากมีการบันทึกข้อมูล (Optional)
    protected $fillable = [
        'storage_location',
        'receive_flg', 
        'delivery_order',
        'container',
        'case_number',
        'box_id',
        'temp_material',
        'quantity',
        'item_number',
        'delivery_item_number',
        'delivery_date',
        'material_id'
    ];

    public function scopeFilter($query, Request $request)
    {
        // หมายเหตุ: เนื่องจาก DB อยู่คนละ Port (3307 vs 3308) 
        // การ Join ข้าม Connection (with container/material) อาจเกิด error ได้
        // จึงแนะนำให้ดึงเฉพาะข้อมูลใน table packing_list ก่อน
        
        // $query->with(['container', 'material']); <-- ปิดไว้ก่อนถ้าอยู่คนละ server/port

        // Delivery Date Range
        if ($request->filled('delivery_date_from')) {
            $query->where('delivery_date', '>=', $request->delivery_date_from);
        }
        if ($request->filled('delivery_date_to')) {
            $query->where('delivery_date', '<=', $request->delivery_date_to);
        }

        // Search text fields
        if ($request->filled('delivery_order')) {
            $query->where('delivery_order', 'like', '%' . $request->delivery_order . '%');
        }
        if ($request->filled('container')) {
            $query->where('container', 'like', '%' . $request->container . '%');
        }
        if ($request->filled('box_id')) {
            $query->where('box_id', 'like', '%' . $request->box_id . '%');
        }

        return $query;
    }

    protected $casts = [
        'delivery_date' => 'date',
    ];

    // Relationships (อาจต้องระวังเรื่อง Cross-Database)
    public function container()
    {
        return $this->setConnection('mysql')->belongsTo(Container::class);
    }

    public function material()
    {
        return $this->setConnection('mysql')->belongsTo(Material::class);
    }
}