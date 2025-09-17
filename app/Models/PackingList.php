<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Container;
use App\Models\Material; // อย่าลืมนำเข้า Model Material

class PackingList extends Model
{
    use HasFactory;

    protected $table = 'packing_list';

    protected $fillable = [
        'storage_location',
        'item_number',
        'delivery_order',
        'delivery_item_number',
        'delivery_date',
        'container_id',
        'material_id',
        'case_number',
        'box_id',
        'quantity',
        'receive_flg',
        'container_received',
        'created_by',
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    public function container()
    {
        return $this->belongsTo(Container::class, 'container_id');
    }

    /**
     * โค้ดที่เพิ่มเข้ามา: สร้างความสัมพันธ์เพื่อเชื่อมไปยังตาราง materials
     * โดยใช้คอลัมน์ material_id เป็น key
     */
    public function material()
    {
        // สมมติว่า Model ของคุณสำหรับตาราง materials ชื่อว่า Material
        return $this->belongsTo(material::class, 'material_id');
    }
}
