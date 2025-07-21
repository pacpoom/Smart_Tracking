<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_number',
        'part_name_thai',
        'part_name_eng',
        'unit',
        'model_no'
    ];

        /**
     * Get the stock record associated with the part.
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }
}
