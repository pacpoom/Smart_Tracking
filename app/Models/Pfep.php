<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pfep extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Laravel will assume 'pfeps' (plural), so we need to specify the correct name.
     */
    protected $table = 'pfep';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'material_id',
        'model',
        'part_type',
        'uloc',
        'pull_type',
        'line_side',
    ];

    /**
     * Define the relationship to the Material model.
     * This allows us to easily get material details from a PFEP record.
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
