<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'part_id',
        'quantity',
        'required_date',
        'reason',
        'foc_no', // เพิ่มบรรทัดนี้
        'status',
        'attachment_path',
        'delivery_date',
        'arrival_date',
        'delivery_document_path',
    ];

    protected $casts = [
        'required_date' => 'date',
        'delivery_date' => 'date',
        'arrival_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
