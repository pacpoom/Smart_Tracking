<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_id',
        'qty',
    ];

    /**
     * Get the part that owns the stock.
     */
    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
